<?php

namespace App\Listeners;

use App\Models\SalesOrderDispatch;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class UpdateDispatchWeight
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
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        if($event->dispatch instanceof SalesOrderDispatch)
        {
            $dispatch = $event->dispatch;
            $weight_kg = 0;

            // Items weight
            if(isset($dispatch->items) && $dispatch->items->count())
            {
                info('Found sale order items');
                foreach ($dispatch->items as $item)
                {
                    if(isset($item->orderedItem) && $item->orderedItem->count())
                    {
                        $order_item = $item->orderedItem;
                        $weight_kg+= $order_item->weight_kg;
                        info('Order item found for getting sale order item weight');
                    }
                }
            }

            // Boxes pallet weight
            if(isset($dispatch->boxes) && $dispatch->boxes->count())
            {
                foreach($dispatch->boxes as $box)
                {
                    $weight_kg+= $box->weight_kg;
                }
            }

            $dispatch->weight_kg = $weight_kg;
            $dispatch->save();
        }
    }
}
