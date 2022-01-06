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
use App\Models\Expense;
use App\Models\ExpenseType;
use App\Models\ExpenseItem;
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
use Illuminate\Support\Facades\Storage;
use Stripe\Order;
use Auth;

class ExpenseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // process filters
        $orders = Expense::query();

      //  $orders->with(['date','status','invoices','customer']);

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

        // eager load
        $expenses = Expense::join ("expense_items", "expense_items.expense_id", "=", "expenses.id")
        ->Join ("users", "users.id", "=", "expenses.created_by"  )
        -> select ("expenses.*", "users.first_name", "users.last_name", DB :: raw ("sum(expense_items.net_cost  * expense_items.qty)  as total, sum(expense_items.net_cost  * expense_items.vat_percentage * expense_items.qty)  as tax "))
        -> groupBy ("expenses.id", "expenses.created_at", "expenses.updated_at")->get();

        //dd($expenses);
    
        set_page_title('Expenses');
        return view('admin.expenses.expense-list', compact(['expenses']));
    }

    /**resource
     * Show the form for creating a new .
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

        $products = ExpenseType::all();
        $customers = Customer::active()->orderBy('first_name')->get();

        set_page_title('Create Expenses');
        return view('admin.expenses.expense-create',compact(['products','customers','duplicate_salesorder', 'sell_product']));
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
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

           $this->validate($request, [
           'image' => 'required|image|max:2048'
       ]);

       if ($request->hasFile('image')) {
           $file = $request->file('image');
           $name = time() . $file->getClientOriginalName();
           $filePath = 'images/' . $name;
           Storage::disk('s3')->put($filePath, file_get_contents($file));
       }
  
        $imageName = time().'.'.$request->image->extension();  

        $order = new Expense;
        $order->supplier_title = $request->supplier;
        $order->created_at = $request->dates;
        $order->reference = $request->reference;
        $order->payment_type = $request->type;
        $order->status = $request->status;
        $order->created_by = Auth::user()->id;
        $order->receipt =  $imageName;
        $order->description = $request->notes;
    
        $order->save();
        $order_id = $order->id;
            for($id = 0; $id < count($request->product_id); $id++){
                $expenseitems = new ExpenseItem;
                $expenseitems->expense_type_id = '1';
                $expenseitems->expense_id =  $order_id ;
                $expenseitems->title = $request->product_id[$id];
                $expenseitems->qty = $request->qty[$id];
                $expenseitems->vat_percentage = $request->vat_type_id[$id];
                $expenseitems->net_cost = $request->item_cost[$id];
                $expenseitems->gross_cost = $request->gross_cost[$id];
                $expenseitems->save();
            }

     return redirect('expenses')->with('success', 'Expenses Created');    
            
        
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

        $netcost = DB::table('expense_items')->where('expense_id', $id)->sum('net_cost') ;
        $grosscost = DB::table('expense_items')->where('expense_id', $id)->sum('gross_cost') ;

        

        $detail = Expense::find($id);

        $item = ExpenseItem::where('expense_id', $id )->get();

        if(!$detail) {
            return back()->with('warning','Sorry this expenses cannot be found.');
        }
        
        //dd($detail);
        set_page_title('Expenses #'.$id);
        return view('admin.expenses.expense', compact(['detail'], ['netcost'], ['grosscost'],  ['item']));
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
