<?php

namespace App\Http\Controllers\Admin;

use App\Events\InvoiceCreated;
use App\Events\OrderUpdated;
use App\Events\PaymentReceived;
use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\SalesOrder;
use App\Models\SalesOrderItem;
use App\Models\VatType;
use App\Repositories\Payments\Exceptions\InvalidAPIKeyException;
use App\Repositories\Payments\PaymentGateway;
use App\Repositories\Payments\Providers\StripeGateway;
use App\Scopes\FranchiseScope;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Dompdf\Dompdf;
use Illuminate\Support\Facades\Log;

class InvoiceController extends Controller
{

    protected $paymentProvider;

    public function __construct()
    {
        if(get_setting('testing_payments'))
        {
            $key = env('STRIPE_TEST_SECRET');
        }
        else
        {
            $key = env('STRIPE_SECRET');
        }

        try {
            $this->paymentProvider = new PaymentGateway(new StripeGateway($key));
        } catch (InvalidAPIKeyException $e) {
            custom_reporter($e);
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $invoices = Invoice::with(['status','salesOrder','items'])->orderBy('id','DESC')->paginate(100);

        set_page_title('Invoices');
        return view('admin.invoices.invoice-list',compact('invoices',$invoices));
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
        //shipping_cost
        $request->validate([
            'sales_order_id' => 'required',
            'invoice'        => 'array|required',
            'invoice_email' => 'exclude_unless:email_invoice,1|required',
            'additional_shipping_cost' => 'exclude_unless:additional_shipping,1|required'
        ]);

        DB::beginTransaction();
        try {

            // get the sales order
            $order = SalesOrder::find($request->input('sales_order_id'));

            if(!$order)
            {
                return back()->with('error','The sales order can\'t be found, please make sure it exists');
            }

            $dt = new \DateTime();
            $due_in = 0;
            if($order->payment_term_id && $order->paymentTerm) {
                $due_in = $order->paymentTerm->value;
            }

            $dt->modify('+ '.$due_in.' days');

            $invoice = Invoice::create([
                'sales_order_id' => $order->id,
                'billing_address_data' => $order->billing_address_data,
                'delivery_address_data' => $order->delivery_address_data,
                'invoice_date'   => date('Y-m-d H:i:s'),
                'invoice_due'    => $dt->format('Y-m-d'),
                'invoice_status_id' => 1, // pending
                'franchise_id'      => current_user_franchise_id()
            ]);

            // loop items and mark as invoiced
            foreach ($request->input('invoice') as $sales_order_item_id => $qty)
            {
                SalesOrderItem::find($sales_order_item_id)->update([
                    'invoice_id' => $invoice->id
                ]);
            }

            // Find Posted Value
            $vat_type = VatType::find($request->input('shipping_vat_type_id'));
            $vat_id = $vat_type->id ?: null;
            $vat_value = $vat_type->value ?: 20;

           // Create shipping line item
            if($request->exists('additional_shipping')) {

                $cost = $request->input('additional_shipping_cost');
                $vat = ($vat_value / 100) * $cost;
                $gross = $cost+$vat;

                // create shipping line item
                $sale_order_item = SalesOrderItem::create([
                    'sales_order_id'            => $order->id,
                    'product_title'             => 'Additional shipping cost',
                    'invoice_id'                => $invoice->id,
                    'qty'                       => 1,
                    'vat_type_id'               => $vat_id,
                    'vat_percentage'            => $vat_value,
                    'item_cost'                 => $cost,
                    'net_cost'                  => $cost,
                    'vat_cost'                  => $vat,
                    'gross_cost'                => $gross,
                    'is_additional_shipping'    => 1
                ]);

                // Update order totals
                OrderUpdated::dispatch($order);
            }

            // Shipping hasn't been invoiced so invoice it
            elseif($request->exists('shipping_cost'))
            {
                $cost = $request->input('shipping_cost');
                $vat = ($vat_value / 100) * $cost;
                $gross = $cost+$vat;

                $order->shipping_cost = $cost;
                $order->shipping_vat_type_id = $vat_id;
                $order->shipping_vat = $vat;
                $order->shipping_gross = $gross;
                $order->shipping_invoice = $invoice->id;

                $order->save();

                OrderUpdated::dispatch($order);
            }

            InvoiceCreated::dispatch($invoice);

            // invoice created event
            if($request->input('email_invoice')) {
                $email = $request->input('invoice_email');

                Invoice::email($invoice->id,$email);
            }

            DB::commit();

            return back()->with('success', 'Invoice #'.$invoice->id.' created.');

        } catch(\Throwable $e) {
            report($e);
            DB::rollBack();
        }

        return back()->with('error', 'Invoice failed. Error has been logged.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function show($id)
    {
        $detail = Invoice::with(['salesOrder'])->findOrFail($id);

        set_page_title('Invoice '. $id);
        return view('admin.invoices.invoice', compact(['detail']));
    }

    /**
     * View details about invoice
     * Such as items payments, discounts, refunds
     */
    public function detail($id)
    {
        $detail = Invoice::with(['items','salesOrder','status','payments'])->findOrFail($id);

        set_page_title('Invoice Detail');
        return view('admin.invoices.invoice-detail', compact(['detail']));
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
        //
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


    public function pdf($type = 'print', $id)
    {
        $detail = Invoice::with(['salesOrder'])->find($id);

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
        }

    }

    /**
     * Email the invoice
     * @param Request $request
     */
    public function email(Request $request)
    {
        $validated = $request->validate([
           'invoice_id' => 'required',
           'email'      => 'email|required'
        ]);

        if(Invoice::email($validated['invoice_id'],$validated['email']))
        {
            return back()->with('success','Email has been sent to '.$validated['email']);
        }

        return back()->with('error','Failed to email the invoice');
    }


    /**
     * @param $hash
     */
    public function pay($hash)
    {
        $detail = Invoice::withoutGlobalScopes()->with(['salesOrder' => function($query) {
            $query->withoutGlobalScopes([FranchiseScope::class]);
        }])->where(\DB::raw('sha1(id)'), $hash)->first();

        //dd($detail);
        if(!$detail) {
            abort(404);
        }

        // check if link has expired
        //if($detail->created)

        $detail->is_paid = false;
        $detail->link_expired = false;

        // loop payments made
        $payments = Payment::where('invoice_id', $detail->id)->get();

        $total_paid = 0;
        if($payments)
        {
            foreach($payments as $payment)
            {
                // Test payments
                if(get_setting('testing_payments'))
                {
                    if(!$payment->test_payment) {
                        continue;
                    }
                }

                // Live payments
                else
                {
                    if($payment->test_payment) {
                        continue;
                    }
                }

                if($payment->status == 'complete')
                {
                    $total_paid += $payment->amount;
                }
            }
        }

        $total_due = $detail->gross_cost - $total_paid;
        $detail->total_due = $total_due;

        // If invoice is not paid
        if( $total_paid < $detail->gross_cost ) {

            try {

                // Grab a pending payment intent
                $payment = Payment::where('invoice_id', $detail->id)
                    ->where('status','pending')
                    ->whereNotNull('payment_ref')
                    ->where('amount',$total_due)
                    ->first();

                if($payment)
                {
                    // Fetch
                    $intent = $stripe->paymentIntents->retrieve(
                        $payment->payment_ref
                    );
                }
                else
                {
                    $intent = $this->paymentProvider->create($total_due*100, [
                        'currency' => 'gbp',
                        'payment_method_types' => ['card'],
                        'description' => 'Invoice #'.$detail->id,
                        'metadata' => [
                            'order_id' => $detail->sales_order_id,
                            'invoice_id' => $detail->id
                        ]
                    ]);

                    $payment = Payment::create([
                        'invoice_id'    => $detail->id,
                        'payment_ref'   => $intent->id,
                        'type'          => 'income',
                        'amount'        => $total_due,
                        'status'        => 'pending'
                    ]);
                }

                // check if the link is expired
                $dt = new \DateTime($detail->created_at);
                $dt->modify('+ 24 Hours');

                if(date('Y-m-d H:i:s') > $dt->format('Y-m-d H:i:s'))
                {
                    $detail->link_expired = true;
                }

                $detail->payment_id = $payment->id;
                $detail->client_secret = $intent->client_secret;

            } catch (\Throwable $exception) {
                report($exception);
            }


        } else {
            // Show them thank you
            $detail->is_paid = true;
        }

        set_page_title('Pay invoice');
        return view('admin.invoices.pay-invoice', compact(['detail',]));
    }

    /**
     * Check payment received
     */
    public function processPayment(Request $request)
    {
        $request->validate([
            'payment_id' => 'required',
            'invoice_id' => 'required'
        ]);

        DB::beginTransaction();
        try {

            $payment = Payment::find($request->input('payment_id'));
            $invoice = Invoice::find($request->input('invoice_id'));

            $intent = $this->paymentProvider->retrieve(
                'intent',
                $payment->payment_ref
            );

            if($intent->status == 'succeeded' && $intent->amount_received)
            {
                if($invoice->gross_cost == ($intent->amount_received / 100))
                {
                    $invoice->invoice_status_id = 3; // paid
                }
                else
                {
                    $invoice->invoice_status_id = 2; // part paid
                }

                if(!$invoice->save()) {
                    Throw new \Exception('Failed to update invoice.');
                }

                $payment->test_payment = !boolval($intent->livemode);
                $payment->status = 'complete';
                $payment->terms_agreed = date('Y-m-d H:i:s');

                if(!$payment->save()) {
                    Throw new \Exception('Failed to save the payment status.');
                }

                // Mark items as paid
                if(!SalesOrderItem::where('invoice_id', $invoice->id)->update(['is_paid' => 1]))
                {
                    Throw new \Exception('Invoice paid but items not mark as paid.');
                }

                // check to see what been paid, whats outstanding and notify teams
                PaymentReceived::dispatch($invoice);

                // Commit changes
                DB::commit();

                if($request->ajax()) {
                    return response()->json([
                        'success' => true,
                        'payment_ref' => $payment->payment_ref
                    ]);
                }

                return view('admin.invoices.payment-status',['success' => true, 'payment' => $payment, 'email' => $invoice->salesOrder->email]);
            }

            // Error man nothing was captured
            else
            {
                Throw new \Exception('Payment failed, nothing was captured');
            }

        }
        catch (\Throwable $e)
        {
            report($e);
            DB::rollBack();
        }

        if($request->ajax()) {
            return response()->json([
                'success' => false,
                'payment_ref' => $payment->payment_ref,
                'message' => 'Payment, please try an alternative payment method, error received : '.$e->getMessage()
            ]);
        }

        return view('admin.invoices.payment-status',['success' => false, 'message' => $e->getCode()]);
    }

    /**
     * Show the payment response
     */
    public function paymentResponse($payment_ref)
    {
        $payment = Payment::where('payment_ref',$payment_ref)->first();

        if(!$payment) {
            abort(404);
        }

        $email = null;
        $success = false;

        if($payment && $invoice = Invoice::find($payment->invoice_id)) {
            $email = $invoice->salesOrder->email;

            if($payment->status == 'complete' && in_array($invoice->invoice_status_id, [2,3]) )
            {
                $success = true;
            }
        }
        else
        {
            $payment = false;
        }

        return view('admin.invoices.payment-status', ['success' => $success, 'payment' => $payment, 'email' => $invoice->salesOrder->email]);
    }

    /**
     * Create payment against invoice
     * @param Request $request
     */
    public function manualPayment(Request $request, $id)
    {
        $validated = $request->validate([
            'payment_method_id'     => 'required|numeric',
            'amount'                => 'required'
        ]);

        $invoice = Invoice::with('payments')->findOrFail($id);

        DB::beginTransaction();
        try {

            $validated['invoice_id'] = $id;
            $validated['payment_ref'] = 'MANUAL-PAYMENT';
            $validated['type'] = 'income';
            $validated['status'] = 'complete';

            $payment = Payment::create($validated);

            $invoice->refresh();

            if(isset($invoice->payments) && $invoice->payments->count())
            {
                $paid = $invoice->payments()->income()->complete()->sum('amount');

                if($paid >= $invoice->gross_cost) {
                    // complete
                    $invoice->invoice_status_id = 3;
                } else {
                    $invoice->invoice_status_id = 2;
                }
            }
            else
            {
                $invoice->invoice_status_id = 1;
            }

            if(!$invoice->save())
            {
                Throw new \Exception('Failed to update the invoice status');
            }

            // check to see what been paid, whats outstanding and notify teams
            PaymentReceived::dispatch($payment, $invoice);

            DB::commit();

            return back()->with('success','Payment has been added against invoice #'.$id);

        } catch(\Throwable $exception) {
            custom_reporter($exception);
            DB::rollBack();
        }

        return back()->withInput()->with('error','Failed to save payment against invoice. ' . (isset($exception) ? $exception->getMessage() : ''));
    }


}
