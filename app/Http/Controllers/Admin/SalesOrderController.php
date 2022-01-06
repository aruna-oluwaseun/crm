<?php

namespace App\Http\Controllers\Admin;

use App\Events\OrderCreated;
use App\Events\OrderItemAdded;
use App\Events\OrderUpdated;
use App\Http\Controllers\Controller;
use App\Mail\EmailQuote;
use App\Models\Customer;
use App\Models\Product;
use App\Models\QuoteEmail;
use App\Models\SalesOrder;
use App\Models\SalesOrderDispatch;
use App\Models\SalesOrderDispatchItem;
use App\Models\SalesOrderItem;
use App\Models\TrainingDate;
use App\Models\UnitOfMeasure;
use App\Models\User;
use App\Models\VatType;
use Dompdf\Dompdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Stripe\Order;

class SalesOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // process filters
        //$sales_orders = SalesOrder::with(['orderType','status','invoices','customer'])->orderBy('id','desc')->paginate(100);
        $orders = SalesOrder::query();
        $orders->with(['orderType','status','invoices','customer']);

        if(\request()->exists('type')) {
            if(\request()->input('type') != 'all') {
                $orders->where('order_type_id',\request()->input('type'));
            }
        }

        if(\request()->exists('status')) {
            if(\request()->input('status') != 'all') {
                $orders->where('sales_order_status_id',\request()->input('status'));
            }
        }

        if(\request()->exists('created')) {
            if(\request()->input('created') != '') {
                $orders->where(DB::raw('DATE(created_at)'),\request()->input('created'));
            }
        }

        if(\request()->exists('customer')) {
            if(\request()->input('customer') != 'all') {
                $orders->where('customer_id',\request()->input('customer'));
            }
        }

        $per_page = 50;
        if(\request()->exists('rows')) {
            $per_page = \request()->input('rows');
        }

        $page_sort = 'desc';
        if(\request()->exists('sort')) {
            $page_sort = \request()->input('sort');
        }

        $orders->orderBy('id',$page_sort);
        $sales_orders = $orders->paginate($per_page);

        //dump($sales_orders);
        set_page_title('Sales orders');
        return view('admin.sales-orders.sales-order-list', compact(['sales_orders']));
    }

    /**
     * Show the form for creating a new resource.
     * @TODO Check if customer is eligible for any discount
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(get_user()->cannot('create-sale-orders')) {
            abort(403);
        }

        // Duplicate whole sales order
        $duplicate_salesorder = null;
        if(\request()->query('salesorder'))
        {
            $duplicate_salesorder = SalesOrder::find(\request()->get('salesorder'));
        }

        // Sell single item
        $sell_product = null;
        if(\request()->query('product'))
        {
            $sell_product = \request()->get('product');
        }

        $products = Product::active()->orderBy('title')->isAvailableOnline()->get();
        $customers = Customer::active()->orderBy('first_name')->get();

        set_page_title('Create sales order');
        return view('admin.sales-orders.sales-order-create',compact(['products','customers','duplicate_salesorder', 'sell_product']));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
           'first_name' => 'required',
           'last_name'  => 'required',
            'email'     => 'required',
            'items'     => 'required|array'
        ]);

        //dd($request->input('items'));

        $email_quote = $request->input('send_quote_email');
        $items = $request->input('items');
        $data = $request->except(['_token','send_quote_email','items']);

        // for now discard items with no product id
        foreach($items as $key => $item) {
            if(!$item['product_id'] || $item['product_id'] == 0) {
                unset($items[$key]);
            }
        }

        if(get_user() instanceof User) {
            $data['order_type_id'] = 2; // phone
        }
        else {
            $data['order_type_id'] = 1; // online
        }

        // Franchise ID
        $data['franchise_id'] = current_user_franchise_id();

        DB::beginTransaction();
        try {
            $order = SalesOrder::createOrder($data,$items);

            DB::commit();

            OrderCreated::dispatch($order);

            if($email_quote) {
                SalesOrder::emailQuote($order, $data['email']);
            }

            if($request->ajax()) {
                return response()->json([
                    'success' => true
                ]);
            }

            return redirect('salesorders/'.$order->id)->with('success', 'Sales order created');

        } catch (\Throwable $exception) {
            custom_reporter($exception);
            DB::rollBack();
        }

        if($request->ajax()) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred creating sales order, please re-fresh and try again.'.(isset($exception) ? $exception->getMessage() : '')
            ]);
        }

        return back()->withInput()->with('error', 'Error creating sales order'.(isset($exception) ? $exception->getMessage() : ''));
    }

    /**
     * Display the specified resource.
     * @TODO Add deposit option when adding sales item | also remove status COMPLETED, let system complete based on payments
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if(get_user()->cannot('create-sale-orders')) {
            abort(403);
        }

        $detail = SalesOrder::with(['items','invoices','dispatches'])->find($id);
        if(!$detail) {
            return back()->with('warning','Sorry this sales order cannot be found.');
        }
        $products = Product::active()->isAvailableOnline()->get();
        $customers = Customer::active()->get();

        set_page_title('Sales order #'.$id);
        return view('admin.sales-orders.sales-order', compact(['detail','customers','products']));
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
        $status = $request->input('sales_order_status_id');

        if(in_array($status, [1,2]))
        {
            $request->validate([
                'sales_order_status_id' => 'required',
                'cancelled_reason'      => 'exclude_unless:sales_order_status_id,8|required'
            ]);
        } else {
            $request->validate([
                'first_name'            => 'required',
                'last_name'             => 'required',
                'email'                 => 'required',
                'sales_order_status_id' => 'required',
                'cancelled_reason'      => 'exclude_unless:sales_order_status_id,8|required'
            ]);
        }

        $data = $request->except(['_token','_method']);

        // Not urgent
        if(!$request->exists('is_urgent')) {
            $data['is_urgent'] = 0;
        }

        if(SalesOrder::find($id)->update($data))
        {
            if($request->ajax()) {
                return response()->json([
                   'success' => true
                ]);
            }

            return back()->with('success', 'Sales order updated');
        }

        if($request->ajax()) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred updating, please re-fresh and try again.'
            ]);
        }

        return back()->with('error', 'Error updating sales order');
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

    // End resource methods --------------


    /**
     * Add item to sales order
     * @param Request $request
     */
    public function storeSalesItem(Request $request)
    {
        $request->validate([
            'sales_order_id' => 'required',
            'qty'            => 'required|integer',
            'product_id'     => 'exclude_if:none_stock_item,1|required',
            'product_title'  => 'exclude_unless:none_stock_item,1|required',
        ]);

        DB::beginTransaction();
        try {
            $item = SalesOrderItem::store($request);

            $data['id'] = $item->id;

            // Update Order Totals
            OrderUpdated::dispatch(SalesOrder::find($request->input('sales_order_id')));

            DB::commit();

            if($request->ajax()) {
                return response()->json(['success' => true, 'data' => $data]);
            }

            return back()->with('success', 'Sales item added to order');

        } catch (\Throwable $e) {
            report($e);
            DB::rollBack();
        }

        if($request->ajax()) {
            return response()->json(['success' => false, 'message' => 'Failed to add sales item to order. Please try again.']);
        }

        return back()->with('error','Failed to add sales item to order. Please try again.');
    }

    /**
     * Remove order item
     */
    public function destroyOrderItem()
    {

    }


    /**
     * Get items ready for dispatch
     * @param Request $request
     */
    public function loadItemsForDispatch(Request $request)
    {
        $data = $request->validate([
           'sales_order_id' => 'required',
           'dispatch'       => 'required'
        ]);

        $items = [];
        foreach($data['dispatch'] as $item_id => $qty_to_dispatch)
        {
            // make sure they are not already dispatched
            $item = SalesOrderItem::getItemForDispatch($item_id);

            if($item) {

                $order_qty = $item->qty;
                $qty_sent = $item->qty_sent;
                $max_can_send = $order_qty-$item->qty_sent;

                $send_qty = $qty_to_dispatch;

                // Item fully sent
                if($qty_sent >= $order_qty) {
                    $send_qty = 0;
                }
                elseif( $qty_to_dispatch > $max_can_send ) {
                    $send_qty = $qty_to_dispatch - $max_can_send;
                }

                $items[] = [
                    'sales_order_item_id' => $item_id,
                    'product_title' => $item->product_title,
                    'sent' => $qty_sent,
                    'qty' => $send_qty
                ];
            }
        }

        if(!empty($items))
        {
            if($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'data'  => $items
                ]);
            }

            return $items;
        }

        if($request->ajax()) {
            return response()->json([
                'success' => false,
                'message' => 'There are no items to ship, please re-fresh and try again.'
            ]);
        }

        return false;
    }

    /**
     * Show the quote
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function quote($id)
    {
        $detail = SalesOrder::findOrFail($id);

        set_page_title('Quote #'. $id);
        return view('admin.invoices.quote', compact(['detail']));
    }

    /**
     * Email the invoice
     * @param Request $request
     */
    public function emailQuote(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required',
            'email'      => 'email|required'
        ]);

        if(SalesOrder::emailQuote(SalesOrder::find($validated['id']),$validated['email']))
        {
            return back()->with('success','Email has been sent to '.$validated['email']);
        }

        return back()->with('error','Failed to email the quote');
    }


    public function quotePdf($type = 'print', $id)
    {
        $detail = SalesOrder::find($id);

        $dompdf = new Dompdf();
        $options = $dompdf->getOptions();
        $options->isRemoteEnabled(true);
        $options->setIsHtml5ParserEnabled(true);
        $dompdf->setOptions($options);

        $dompdf->loadHtml(view('admin.invoices.templates.quote-pdf', compact(['detail'])));
        $dompdf->setPaper('A4');

        $dompdf->render();

        if($type == 'print') {
            return view('admin.invoices.templates.quote-pdf',['detail' => $detail,'print' => true]);
        }
        else
        {
            // Works locally not on traditional servers
            //return $dompdf->stream('quote-'.$detail->id.'.pdf');

            // Vapor solution
            $pdf = $dompdf->output();
            return response()->streamDownload(function () use ($pdf) {
                echo $pdf;
            }, 'quote-'.$detail->id.'.pdf', [
                'X-Vapor-Base64-Encode' => 'True',
                'mime' => 'application/pdf',
            ]);
        }
    }

    /**
     * Print commercial invoice from Dispatch
     * @param $dispatch_id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function commercialInvoice($dispatch_id)
    {
        $detail = SalesOrderDispatch::with(['items.orderedItem','courier','salesOrder'])->findOrFail($dispatch_id);

        $print = true;
        set_page_title('Commercial Invoice #'.$dispatch_id);
        return view('admin.invoices.templates.commercial-invoice-pdf',compact(['detail','print']));
    }
}
