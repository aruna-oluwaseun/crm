<?php

namespace App\Http\Controllers\Admin;

use App\Events\RefundUpdated;
use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Refund;
use App\Models\SalesOrderItem;
use App\Models\VatType;
use Dompdf\Dompdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RefundController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $refunds = Refund::paginate(50);

        set_page_title('Refunds');
        return view('admin.refunds.refund-list',compact(['refunds']));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id'   => 'required',
            'first_name'    => 'required',
            'last_name'     => 'required',
            'contact_number'=> 'nullable',
            'email'         => 'required|email',
            'reason'        => 'required'
        ]);

        $validated['franchise_id'] = current_user_franchise_id();
        if($refund = Refund::create($validated))
        {
            return redirect('refunds/detail/'.$refund->id)->with('success','You refund has been created, please now add items to process the refund');
        }

        return back()->withInput()->with('error','An error occurred adding the refund');
    }

    /**
     * The refund detail for editing etc
     * @param $id
     */
    public function detail($id)
    {
        $detail = Refund::with(['items.saleOrderItem','customer.invoices'])->findOrFail($id);

        $selected_invoice = $detail->invoice_id;
        if(\request()->exists('invoice') && is_null($selected_invoice)) {
            $selected_invoice = Invoice::with(['items','salesOrder'])->find(\request()->get('invoice'));
        } else {
            $selected_invoice = Invoice::with(['items','salesOrder'])->find($selected_invoice);
        }

        set_page_title('Refund detail');
        return view('admin.refunds.refund-detail',compact(['detail', 'selected_invoice']));
    }

    /**
     * Show the refund in A4 style
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $detail = Refund::with(['items'])->findOrFail($id);

        set_page_title('Refund '. $id);
        return view('admin.refunds.refund', compact(['detail']));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        dd($request->all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function refundItems(Request $request, $id)
    {
        $validated = $request->validate([
            'invoice_id'=> 'required',
            'refund' => 'required|array'
        ]);

        if(empty($request)) {
            return back()->withInput()->with('error','You need to check the items you want to refund');
        }

        DB::beginTransaction();
        $refund = Refund::findOrFail($id);
        $refund->invoice_id = $request->input('invoice_id');
        $refund->status = 'processing';

        if(!$refund->save()) {
            DB::rollBack();
            return back()->withInput()->with('error','Error updating the refund');
        }

        foreach ($validated['refund'] as $sale_order_item_id => $details)
        {
            if($sale_order_item_id == 'original_shipping') {
                $title = 'Shipping';
                $sale_order_item_id = null;
            }
            else {
                $sale_order_item = SalesOrderItem::find($sale_order_item_id);
                $title = $sale_order_item->product_title;
            }

            $qty = $details['qty'];
            $item_cost = $details['item_cost'];
            $net_cost = $qty*$item_cost;
            $gross_cost = $details['gross_cost'];
            $vat_cost = $gross_cost-$net_cost;

            $vat = VatType::find($details['vat_type_id']);

            $item = [
                'sales_order_item_id'           => $sale_order_item_id,
                'title'                         => $title,
                'qty'                           => $qty,
                'vat_type_id'                   => $vat->id,
                'vat_percentage'                => $vat->value,
                'refund_item_cost'              => $item_cost,
                'refund_net_cost'               => $net_cost,
                'refund_vat_cost'               => $vat_cost,
                'refund_gross_cost'             => $gross_cost
            ];

            if(!$refund->items()->create($item))
            {
                DB::rollBack();
                return back()->withInput()->with('error','Error updating the refund');
            }
        }

        // Process the actual payment if applicable and email customer
        RefundUpdated::dispatch($refund->fresh());
        DB::commit();

        return back()->with('success','Refund items added, the refund is now processing');

    }

    public function pdf($type = 'print', $id)
    {
        /*$detail = Refund::with(['items','customer'])->find($id);

        $dompdf = new Dompdf();
        $options = $dompdf->getOptions();
        $options->isRemoteEnabled(true);
        $options->setIsHtml5ParserEnabled(true);
        $dompdf->setOptions($options);

        $dompdf->loadHtml(view('admin.invoices.templates.invoice-pdf', compact(['detail'])));
        $dompdf->setPaper('A4');

        $dompdf->render();

        if($type == 'print') {
            return view('admin.invoices.templates.invoice-pdf',['detail' => $detail,'print' => true]);
        }
        else
        {
            // Works locally on traditional servers
            //return $dompdf->stream('invoice-'.$detail->id.'.pdf');

            // Vapor solution
            $pdf = $dompdf->output();
            return response()->streamDownload(function () use ($pdf) {
                echo $pdf;
            }, 'invoice-'.$detail->id.'.pdf', [
                'X-Vapor-Base64-Encode' => 'True',
                'mime' => 'application/pdf',
            ]);
        }*/

    }

    /**
     * Email the invoice
     * @param Request $request
     */
    public function email(Request $request)
    {
        $validated = $request->validate([
            'refund_id' => 'required',
            'email'      => 'email|required'
        ]);

        if(Refund::email($validated['refund_id'],$validated['email']))
        {
            return back()->with('success','Email has been sent to '.$validated['email']);
        }

        return back()->with('error','Failed to email the refund');
    }

    public function manualPayment(Request $request, $id)
    {
        $validated = $request->validate([
            'payment_method_id'     => 'required|numeric',
            'amount'                => 'required'
        ]);

        $refund = Refund::with('payments')->findOrFail($id);

        DB::beginTransaction();
        try {

            $validated['refund_id'] = $id;
            $validated['payment_ref'] = 'MANUAL-REFUND';
            $validated['type'] = 'refund';
            $validated['status'] = 'complete';

            $payment = Payment::create($validated);

            $refund->refresh();

            if(isset($refund->payments) && $refund->payments->count())
            {
                $amount = $refund->payments()->refund()->complete()->sum('amount');

                if($amount >= $refund->gross_cost)
                {
                    $refund->status = 'complete';
                }
                else
                {
                    $refund->status = 'pending';
                }
            }
            else
            {
                $refund->status = 'error';
            }

            if(!$refund->save())
            {
                Throw new \Exception('Failed to update the refund status');
            }

            // check to see what been paid, whats outstanding and notify teams

            DB::commit();

            return back()->with('success','Payment marked as paid against refund #'.$id);

        } catch(\Throwable $exception) {
            custom_reporter($exception);
            DB::rollBack();
        }

        return back()->withInput()->with('error','Failed to save payment against refund. ' . (isset($exception) ? $exception->getMessage() : ''));
    }

}
