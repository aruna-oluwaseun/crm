<?php

namespace App\Http\Controllers\Admin;

use App\Events\SalesOrderDispatched;
use App\Http\Controllers\Controller;
use App\Models\Courier;
use App\Models\Product;
use App\Models\SalesOrder;
use App\Models\SalesOrderDispatch;
use App\Models\SalesOrderDispatchItem;
use App\Models\SalesOrderItem;
use App\Models\UnitOfMeasure;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;

class SalesOrderDispatchesController extends Controller
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
            'sales_order_id' => 'required',
            'dispatch_items' => 'required|array',
            'courier_title' => 'exclude_unless:courier_id,new|exclude_if:is_collection,1|required'
        ]);

        $dispatch = $request->except(['_token','dispatch_items','courier_title','box_ids','box_qtys']);
        $dispatch_items = $request->input('dispatch_items');
        $box_ids = $request->input('box_ids');
        $box_qtys = $request->input('box_qtys');

        $sales_order = SalesOrder::find($request->input('sales_order_id'));

        if($sales_order) {
            $dispatch['delivery_address_data'] = $sales_order->delivery_address_data;
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


            $dispatch_data = SalesOrderDispatch::create($dispatch)->fresh();

            $success = 0; $items_sent = [];
            foreach ($dispatch_items as $sale_item_id => $qty)
            {
                $sale_item = SalesOrderItem::getItemForDispatch($sale_item_id);

                if($sale_item)
                {
                    $items_sent[] = SalesOrderDispatchItem::create([
                        'sales_order_dispatch_id' => $dispatch_data->id,
                        'sales_order_item_id' => $sale_item_id,
                        'product_id' => $sale_item->product_id,
                        'product_title' => $sale_item->product_title,
                        'qty' => $qty
                    ]);
                }
                else
                {
                    throw new \Exception('No items found for dispatch');
                }
            }

            // Store boxes used
            if(!empty($box_qtys)) {
                foreach ($box_qtys as $key => $qty) {
                    if($qty >= 1) {
                        $product_id = $box_ids[$key];
                        if(!$item = Product::find($product_id)->select(['title','weight','unit_of_measure_id'])->find($product_id)) {
                            continue;
                        }

                        // convert weight
                        if($item->weight && $item->unit_of_measure_id) {
                            $weight = $item->weight*$qty;

                            $convert = UnitOfMeasure::convert($item->unit_of_measure_id, 'Kg', $weight);
                            if($convert->success)
                            {
                                $weight_kg = $convert->value;
                            }
                        }

                        $dispatch_data->boxes()->create([
                            'product_id'    => $product_id,
                            'product_title' => $item->title,
                            'qty'           => $qty,
                            'weight'        => isset($weight) ? $weight : null,
                            'weight_kg'     => isset($weight_kg) ? $weight_kg : null
                        ]);
                    }
                }
            }

            SalesOrderDispatched::dispatch( $dispatch_data ); // update stock // send mail

            DB::commit();

            return back()->with('success', 'Dispatch processed');

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
