<?php

namespace App\Http\Livewire\Admin;

use App\Models\Expense;
use App\Models\Franchise;
use App\Models\Invoice;
use App\Models\PurchaseOrder;
use App\Models\Refund;
use App\Repositories\HMRC\VAT\SubmitVATReturnPostBody;
use App\Repositories\HMRC\VAT\SubmitVATReturnRequest;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class VatReturn extends Component
{

    public $franchise_id = null;
    public $invoice_ids = [];
    public $purchase_ids = [];
    public $refund_ids = [];
    public $expense_ids = [];

    // Response
    public $response = [];

    // Fillable
    public $vrn = null;
    public $period_key = null;
    public $vat_due_sales = null;
    public $vat_due_acquisitions = null;
    public $total_vat_due = null;
    public $vat_reclaimed_curr_period = null;
    public $net_vat_due = null;
    public $total_value_sales_ex_vat = null;
    public $total_value_purchases_ex_vat = null;
    public $total_value_good_supplied_ex_vat = null;
    public $total_acquisitions_ex_vat = null;
    public $finalised = 0;

    public $gov_test_scenario = null;

    // Filling in vat
    //https://www.gov.uk/guidance/how-to-fill-in-and-submit-your-vat-return-vat-notice-70012#section3

    protected $rules = [
        'vrn'                               => 'required|min:9',
        'period_key'                        => 'required',
        'vat_due_sales'                     => 'required', // Box 1 // Remove any refunds
        'vat_due_acquisitions'              => 'required', // Box 2
        'total_vat_due'                     => 'required', // Box 3 = Sum box 1 and 2
        'vat_reclaimed_curr_period'         => 'required', // Box 4 = reclaimed vat on purchases / expenses : req vat invoice / vat receipt
        'net_vat_due'                       => 'required', // Box 5 = take box's 3 & 4 deduct the smaller from larger and add diff here
        'total_value_sales_ex_vat'          => 'required', // Box 6 sum of net sales
        'total_value_purchases_ex_vat'      => 'required', // Box 7 sum of net purchases / expenses
        'total_value_good_supplied_ex_vat'  => 'required', // Box 8 sum of purchases include acquisitions of goods bought in fron Northern Ireland aka anything from box 9
        'total_acquisitions_ex_vat'         => 'required', // Box 9
        'finalised'                         => 'required|numeric',
    ];

    protected $listeners = [
        'set:period_key' => 'setPeriodKey',
        'set:vrn'        => 'setVrn'
    ];

    public function setPeriodKey($vrn)
    {
        $this->period_key = $vrn;
    }

    public function mount($franchise = null)
    {
        if(!$franchise) {
            $franchise = Franchise::find(current_user_franchise_id());
        }

        $this->franchise_id = $franchise->id;
        if($franchise->vat_number) {
            $this->vrn = str_replace(' ','',$franchise->vat_number);
        }

        // Paid invoices
        $invoices = Invoice::paid()->vatNotFiled();
        if($invoices->count()) {
            $this->invoice_ids = $invoices->pluck('id')->toArray();
        }

        /** @TODO Add scope for only purchases that have received an invoice maybe, add new column for storing invoice */
        $purchases = PurchaseOrder::ordered()->vatNotFiled();
        if($purchases->count()) {
            $this->purchase_ids = $purchases->pluck('id')->toArray();
        }

        $refunds = Refund::complete()->vatNotFiled();
        if($refunds->count()) {
            $this->refund_ids = $refunds->pluck('id')->toArray();
        }

        /** @TODO Add scope for only expenses that have a receipt maybe */
        $expenses = Expense::vatNotFiled();
        if($expenses->count()) {
            $this->expense_ids = $expenses->pluck('id')->toArray();
        }


        // Box 1
        $invoice_vat = $invoices->sum('vat_cost');
        $refund_vat = $refunds->sum('vat_cost');
        $this->vat_due_sales = $invoice_vat-$refund_vat;

        //-------------

        // Box 2
        $this->vat_due_acquisitions = $this->formatNumber(0);

        // Box 3
        $this->total_vat_due = $this->formatNumber($this->vat_due_sales + $this->vat_due_acquisitions);

        // Box 4
        $this->vat_reclaimed_curr_period = $this->formatNumber($purchases->sum('vat_cost') + $expenses->sum('vat_cost'));

        // Box 5 ---
        if($this->total_vat_due > $this->vat_reclaimed_curr_period) {
            $this->net_vat_due = $this->formatNumber($this->total_vat_due-$this->vat_reclaimed_curr_period);
        }
        if($this->total_vat_due < $this->vat_reclaimed_curr_period) {
            $this->net_vat_due = $this->formatNumber($this->vat_reclaimed_curr_period-$this->total_vat_due);
        }
        if($this->total_vat_due == $this->vat_reclaimed_curr_period) {
            $this->net_vat_due = $this->formatNumber($this->vat_reclaimed_curr_period-$this->total_vat_due);
        }
        // -------

        // Box 6
        $this->total_value_sales_ex_vat = $this->formatNumber($invoices->sum('net_cost'),false);

        // Box 7
        $this->total_value_purchases_ex_vat = $this->formatNumber($purchases->sum('net_cost')+$expenses->sum('net_cost'),false);

        // Box 8
        $this->total_value_good_supplied_ex_vat = $this->formatNumber(0,false);

        // Box 9
        $this->total_acquisitions_ex_vat = $this->formatNumber(0,false);
    }

    public function render()
    {
        return view('livewire.admin.vat-return');
    }

    private function formatNumber($str,$decimals = true)
    {
        if($decimals) {
            return number_format($str,2,'.','');
        }

        return substr($str,0,strpos($str,'.')) ?: $str;
    }

    public function save()
    {
        $validated = $this->validate();

        DB::beginTransaction();
        try {
            $postBody = new SubmitVATReturnPostBody();
            $postBody->setPeriodKey($this->period_key);
            // Box 1
            $postBody->setVatDueSales($this->formatNumber($this->vat_due_sales)); // between -9999999999999.99 and 9999999999999.99.
            // Box 2
            $postBody->setVatDueAcquisitions($this->formatNumber($this->vat_due_acquisitions)); // between -9999999999999.99 and 9999999999999.99.
            // Box 3
            $postBody->setTotalVatDue($this->formatNumber($this->total_vat_due)); // between -9999999999999.99 and 9999999999999.99.
            // Box 4
            $postBody->setVatReclaimedCurrPeriod($this->formatNumber($this->vat_reclaimed_curr_period)); // between -9999999999999.99 and 9999999999999.99.
            // Box 5
            $postBody->setNetVatDue($this->formatNumber($this->net_vat_due)); // between -9999999999999.99 and 9999999999999.99.
            // Box 6
            $postBody->setTotalValueSalesExVAT($this->formatNumber($this->total_value_sales_ex_vat, false)); // between -9999999999999 and 9999999999999.
            // Box 7
            $postBody->setTotalValuePurchasesExVAT($this->formatNumber($this->total_value_purchases_ex_vat, false)); // between -9999999999999 and 9999999999999.
            // Box 8
            $postBody->setTotalValueGoodsSuppliedExVAT($this->formatNumber($this->total_value_good_supplied_ex_vat, false)); // between -9999999999999 and 9999999999999.
            // Box 9
            $postBody->setTotalAcquisitionsExVAT($this->formatNumber($this->total_acquisitions_ex_vat), false); // between -9999999999999 and 9999999999999.
            $postBody->setFinalised($this->finalised);

            //info(print_r($postBody,true));

            $request = new SubmitVATReturnRequest($this->vrn, $postBody);
            // Set test here $request->gov_test_scenario();

            $response = $request->fire();
            $result = $response->getJson(true);

            // Success
            if(!$result['processingDate'] && $result['formBundleNumber'])
            {
                Throw new \Exception('VAT Return failed, we didnt receive a processing date or form bundle number from HMRC');
            }

            if(!$vat_return = \App\Models\VatReturn::create([
                'vat_number'                        => $this->vrn,
                'period_key'                        => $this->period_key,
                'vat_due_sales'                     => $this->vat_due_sales,
                'vat_due_acquisitions'              => $this->vat_due_acquisitions,
                'total_vat_due'                     => $this->total_vat_due,
                'vat_reclaimed_curr_period'         => $this->vat_reclaimed_curr_period,
                'net_vat_due'                       => $this->net_vat_due,
                'total_value_sales_ex_vat'          => $this->total_value_sales_ex_vat,
                'total_value_purchases_ex_vat'      => $this->total_value_purchases_ex_vat,
                'total_value_good_supplied_ex_vat'  => $this->total_value_good_supplied_ex_vat,
                'total_acquisitions_ex_vat'         => $this->total_acquisitions_ex_vat,
                'finalised'                         => $this->finalised,
                'correlation_id'                    => null,
                'receipt_id'                        => null,
                'receipt_timestamp'                 => null,
                'processing_date'                   => $result['processingDate'],
                'form_bundle_number'                => $result['formBundleNumber'],
                'payment_indicator'                 => isset($result['paymentIndicator']) ? $result['paymentIndicator'] : null,
                'charge_ref_number'                 => isset($result['chargeRefNumber']) ? $result['chargeRefNumber'] : null,
                'franchise_id'                      => $this->franchise_id ?: current_user_franchise_id()
            ])->fresh()) {
                Throw new \Exception('VAT return submitted but we could not save the record to our database, please note these records down. Processing date : '.$result['processingDate'].' Form bundle number '.$result['formBundleNumber'] );
            };

            if(is_array($this->invoice_ids) && !empty($this->invoice_ids)) {
                Invoice::whereIn('id',$this->invoice_ids)->update(['vat_return_id' => $vat_return->id]);
            }
            if(is_array($this->purchase_ids) && !empty($this->purchase_ids)) {
                PurchaseOrder::whereIn('id', $this->purchase_ids)->update(['vat_return_id' => $vat_return->id]);
            }
            if(is_array($this->refund_ids) && !empty($this->refund_ids)) {
                Refund::whereIn('id', $this->refund_ids)->update(['vat_return_id' => $vat_return->id]);
            }
            if(is_array($this->expense_ids) && !empty($this->expense_ids)) {
                Expense::whereIn('id', $this->expense_ids)->update(['vat_return_id' => $vat_return->id]);
            }

            DB::commit();

            $this->reset();

            $this->response = [
                'processingDate'    => $result['processingDate'],
                'formBundleNumber'  => $result['formBundleNumber'],
                'paymentIndicator'  => isset($result['paymentIndicator']) ? $result['paymentIndicator'] : null,
                'chargeRefNumber'   => isset($result['chargeRefNumber']) ? $result['chargeRefNumber'] : null
            ];

            session()->flash('success', 'VAT return submitted.');
            return;

        } catch (\Throwable $exception) {
            custom_reporter($exception);
            DB::rollBack();
        }

        session()->flash('error', 'Failed submit vat return. '.(isset($exception) ? $exception->getMessage() : ''));

    }
}
