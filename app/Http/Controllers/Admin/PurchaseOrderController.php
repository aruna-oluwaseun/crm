<?php

namespace App\Http\Controllers\Admin;

use App\Events\PurchaseOrderUpdated;
use App\Http\Controllers\Controller;
use App\Models\Franchise;
use App\Models\Product;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use App\Models\Supplier;
use App\Models\UnitOfMeasure;
use App\Models\VatType;
use Dompdf\Dompdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PurchaseOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $purchases = PurchaseOrder::orderBy('id','DESC')->paginate(100);

        return view('admin.purchases.purchase-list', compact(['purchases']));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function create()
    {
        $franchise = Franchise::with('addresses')->find(current_user_franchise_id());
        $suppliers = Supplier::active()->get();

        // Duplicate whole sales order
        $duplicate_purchase = null;
        if(\request()->exists('id'))
        {
            $duplicate_purchase = PurchaseOrder::find(\request()->input('id'));
        }

        set_page_title('Create purchase order');
        return view('admin.purchases.purchase-create', compact(['duplicate_purchase','suppliers','franchise']));
    }

    /**
     * Store a newly created resource in storage.
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'supplier_id'               => 'required',
            'supplier_invoice_number'   => 'nullable',
            'expected_delivery_date'    => 'required',
            'billing_address_data'      => 'nullable',
            'delivery_address_data'     => 'nullable',
            'purchase_order_status_id'  => 'required',
            'items'                     => 'array',
            'shipping_cost'             => 'nullable'
        ]);

        $data = $request->except('_token','items');

        //dd($data);

        DB::beginTransaction();
        try {

            $items = $request->input('items');

            $purchase = PurchaseOrder::createOrder($data,$items);

            DB::commit();

            PurchaseOrderUpdated::dispatch($purchase);
            return redirect('purchases/'.$purchase->id)->with('success','Purchase order created');

        } catch(\Throwable $exception) {
            custom_reporter($exception);
            DB::rollBack();
        }

        return back()->withInput()->with('error','Sorry an error occurred '.(isset($exception) ? $exception->getMessage() : ''));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $detail = PurchaseOrder::with(['items','status','dispatches','supplier.products.unitOfMeasure'])->find($id);
        $products = $detail->supplier->products;

        //dd($products[0]);

        set_page_title('Purchase Order '.$id);
        return view('admin.purchases.purchase',compact(['detail','products']));
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
        $request->validate([
            'supplier_id'               => 'required',
            'supplier_invoice_number'   => 'nullable',
            'expected_delivery_date'    => 'required',
            'billing_address_data'      => 'nullable',
            'delivery_address_data'     => 'nullable',
            'purchase_order_status_id'  => 'required',
            'shipping_cost'             => 'nullable'
        ]);

        $data = $request->except(['_token','_method']);

        if(PurchaseOrder::find($id)->update($data))
        {
            if($request->ajax()) {
                return response()->json([
                    'success' => true
                ]);
            }

            return back()->with('success', 'Purchase order updated');
        }

        if($request->ajax()) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred updating, please re-fresh and try again.'
            ]);
        }

        return back()->with('error', 'Error updating purchase order');
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

    /**
     * Remove order item
     */
    public function destroyOrderItem()
    {

    }

    public function storePurchaseItem(Request $request)
    {
        //dd($request->all());
        $request->validate([
            'purchase_order_id' => 'required',
            'product_id'        => 'required_unless:none_stock_item,1',
            'product_title'     => 'required_if:none_stock_item,1',
            'product_code'      => 'required_if:none_stock_item,1',
            'qty'               => 'required|min:1',
            'vat_type_id'       => 'required',
            'weight'            => 'nullable',
            'item_cost'         => 'required'
        ]);

        DB::beginTransaction();
        try {

            $purchase = PurchaseOrderItem::store($request);

            DB::commit();

            PurchaseOrderUpdated::dispatch($purchase);
            return back()->with('success','Item added to purchase order');

        } catch (\Throwable $exception) {
            custom_reporter($exception);
            DB::rollBack();
        }

        return back()->withInput()->with('error','Failed to add item to this purchase order'.(isset($exception) ? $exception->getMessage() : ''));
    }


    /**
     * Get items ready for dispatch
     * @param Request $request
     */
    public function loadItemsForDispatch(Request $request)
    {
        $data = $request->validate([
            'purchase_order_id' => 'required',
            'dispatch'       => 'required'
        ]);

        $items = [];
        foreach($data['dispatch'] as $item_id => $qty_to_dispatch)
        {
            // make sure they are not already dispatched
            $item = PurchaseOrderItem::getItemForDispatch($item_id);

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
                    'purchase_order_item_id' => $item_id,
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
                'message' => 'There are no items for dispatch, please re-fresh and try again.'
            ]);
        }

        return false;
    }

    /**
     * Show the purchase order
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function purchaseOrder($id)
    {
        $detail = PurchaseOrder::with(['supplier.billingAddress','items','dispatches.items'])->findOrFail($id);

        set_page_title('Purchase Order #'. $id);
        return view('admin.purchases.purchase-order', compact(['detail']));
    }

    /**
     * Email the invoice
     * @param Request $request
     */
    public function emailPurchaseOrder(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required',
            'email'      => 'email|required'
        ]);

        $order = PurchaseOrder::findOrFail($validated['id']);

        if(PurchaseOrder::emailPurchase($order,$validated['email']))
        {
            return back()->with('success','Purchase order has been sent to '.$validated['email']);
        }

        return back()->with('error','Failed to email the purchase order');
    }


    public function pdf($type = 'print', $id)
    {
        $detail = PurchaseOrder::findOrFail($id);

        $dompdf = new Dompdf();
        $options = $dompdf->getOptions();
        $options->isRemoteEnabled(true);
        $options->setIsHtml5ParserEnabled(true);
        $dompdf->setOptions($options);

        $dompdf->loadHtml(view('admin.purchases.template.purchase-order-pdf', compact(['detail'])));
        $dompdf->setPaper('A4');

        $dompdf->render();

        if($type == 'print') {
            return view('admin.purchases.template.purchase-order-pdf',['detail' => $detail,'print' => true]);
        }
        else
        {
            // Works locally not on traditional servers
            //return $dompdf->stream('quote-'.$detail->id.'.pdf');

            // Vapor solution
            $pdf = $dompdf->output();
            return response()->streamDownload(function () use ($pdf) {
                echo $pdf;
            }, 'purchase-order-'.$detail->id.'.pdf', [
                'X-Vapor-Base64-Encode' => 'True',
                'mime' => 'application/pdf',
            ]);
        }

    }
}
