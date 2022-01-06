<?php

namespace App\Listeners;

use App\Events\ManualStockAdded;
use App\Events\ProductionOrderProcessed;
use App\Events\PurchaseOrderReceived;
use App\Events\SalesOrderDispatched;
use App\Models\SalesOrderDispatch;
use App\Models\Stock;
use App\Models\StockMovement;
use App\Models\UnitOfMeasure;
use http\Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class UpdateStock
{

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param object $event
     * @return void
     */
    public function handle($event)
    {
        // Production order event
        if($event instanceof ProductionOrderProcessed) {

            $build_stock = Stock::stockExists($event->production_order->product_id);

            if(isset($event->production_order->buildProducts) && $event->production_order->buildProducts->count())
            {
                foreach($event->production_order->buildProducts as $item)
                {
                    if(!$item->product_id) {
                        continue;
                    }

                    $build_product_updated = false;

                    // Raw stock
                    $stock = Stock::stockExists($item->product_id);

                    // Get Unit of measure
                    $movement = UnitOfMeasure::convert($item->unit_of_measure_id, $item->unit_of_measure_id, $item->qty_used);

                    if($movement->success)
                    {
                        // Stock movement
                        $stock_movement = StockMovement::create([
                            'stock_id'              => $stock->id,
                            'unit_movement'         => -$movement->value,
                            'build_product_id'      => $item->id,
                            'production_order_id'   => $event->production_order->id
                        ]);

                        $stock->unit_level_stock = $stock->unit_level_stock-$movement->value;

                        if($stock->save())
                        {
                            $build_product_updated = true;
                        }
                    }
                    else
                    {
                        // Remove from normal movement field
                        $stock_movement = StockMovement::create([
                            'stock_id'              => $stock->id,
                            'movement'              => -$item->qty_used,
                            'build_product_id'      => $item->id,
                            'production_order_id'   => $event->production_order->id
                        ]);

                        $stock->stock = $stock->stock-$item->qty_used;

                        if($stock->save())
                        {
                            $build_product_updated = true;
                        }
                    }

                    if($build_product_updated)
                    {
                        $item->stock_updated = 1;
                        $item->save();
                    }

                }
            }

            $build_stock->stock = $build_stock->stock+$event->production_order->qty;

            if($build_stock->save())
            {
                $stock_movement = StockMovement::create([
                    'stock_id'              => $build_stock->id,
                    'movement'              => $event->production_order->qty,
                    'production_order_id'   => $event->production_order->id
                ]);
            }

        }

        // Dispatch event
        /** @TODO update boxes / pallets used too */
        if($event instanceof SalesOrderDispatched) {

            if(!empty($event->dispatch->items)) {

                foreach($event->dispatch->items as $item)
                {
                    if(!$item->product_id) {
                        continue;
                    }

                    // Main stock
                    $stock = Stock::stockExists($item->product_id);

                    // Stock movement
                    $stock_movement = StockMovement::create([
                        'stock_id'                  => $stock->id,
                        'movement'                  => -$item->qty,
                        'sales_order_item_id'       => $item->sales_order_item_id
                    ]);

                    $stock->stock = $stock->stock-$item->qty;
                    $stock->save();
                }
            }
        }

        /** @TODO implement purchase order received for stock */
        // Purchase Order received
        if($event instanceof PurchaseOrderReceived)
        {

        }

        // Stock manually added
        if($event instanceof ManualStockAdded)
        {
            $movement = $event->movement;

            if(isset($movement->stock) && $movement->stock->count())
            {
                $stock = $movement->stock;
                $stock->stock = $stock->stock + $movement->movement;

                if($movement->unit_movement) {
                    $stock->unit_level_stock = $stock->unit_level_stock + $movement->unit_movement;
                }
                $stock->save();
            }

        }

    }

    public function failed($event, $exception)
    {
        Log::error('UpdateOrderTotals event failed, the event'.print_r($event, true).' : the exception '. print_r($exception, true));

    }
}
