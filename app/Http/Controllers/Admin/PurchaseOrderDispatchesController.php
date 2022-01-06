<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Courier;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderDispatch;
use App\Models\PurchaseOrderDispatchItem;
use App\Models\PurchaseOrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PurchaseOrderDispatchesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        // redirect back with #tab-order-items
        $request->validate([
            'purchase_order_id' => 'required',
            'dispatch_items' => 'required|array',
            'courier_title' => 'exclude_unless:courier_id,new|exclude_if:is_collection,1|required'
        ]);

        $dispatch = $request->except(['_token','dispatch_items','courier_title']);
        $dispatch_items = $request->input('dispatch_items');

        $order = PurchaseOrder::find($request->input('purchase_order_id'));

        if($order) {
            $dispatch['company_address_data'] = $order->delivery_address_data;
        }

        DB::beginTransaction();
        try {
            if($request->exists('is_collection')) {
                unset($dispatch['courier_id']);
            } else {
                if($dispatch['courier_id'] == 'new')
                {
                    // add the new courier
                    $dispatch['courier_id'] = Courier::create(['title' => $request->input('courier_title')])->id;
                    $dispatch['courier_title'] = $request->input('courier_title');
                }
                else
                {
                    $courier = Courier::find($dispatch['courier_id']);
                    if($courier)
                    {
                        $dispatch['courier_title'] = $courier->title;
                    }
                }
            }

            $dispatch_data = PurchaseOrderDispatch::create($dispatch)->fresh();

            $success = 0; $items_sent = [];
            foreach ($dispatch_items as $order_item_id => $qty)
            {
                $order_item = PurchaseOrderItem::getItemForDispatch($order_item_id);

                if($order_item)
                {
                    $items_sent[] = PurchaseOrderDispatchItem::create([
                        'purchase_order_dispatch_id' => $dispatch_data->id,
                        'purchase_order_item_id' => $order_item_id,
                        'product_id' => $order_item->product_id,
                        'product_title' => $order_item->product_title,
                        'qty' => $qty
                    ]);
                }
                else
                {
                    throw new \Exception('No items found for dispatch');
                }
            }

            DB::commit();

            return back()->with('success', 'Dispatch processed, check back here to mark it as received');

        } catch (\Throwable $e) {
            report($e);
            DB::rollBack();
        }

        return back()->with('error', 'Dispatch request failed. Error has been logged.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
}
