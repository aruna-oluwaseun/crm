<?php

namespace App\Listeners;

use App\Models\PurchaseOrder;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class UpdatePurchaseOrderTotals
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
        // Update the totals
        if($event->order instanceof PurchaseOrder)
        {
            $order = $event->order;

            $data = \App\Models\PurchaseOrderItem::where('purchase_order_id',$order->id)
                ->groupBy('purchase_order_id')
                ->selectRaw('SUM(net_cost) as net_cost,
                SUM(discount_cost) as discount_cost,
                SUM(vat_cost) as vat_cost,
                SUM(gross_cost) as gross_cost,
                SUM(weight_kg) as weight_kg')
                ->first();

            if($data && $data->count()) {

                // Shipping costs
                $shipping_cost = $order->shipping_cost;
                $shipping_vat = $order->shipping_vat;

                $order->net_cost = $data->net_cost;
                $order->discount_cost = $data->discount_cost;
                $order->vat_cost = $data->vat_cost;
                $order->weight_kg = $data->weight_kg;

                // data->gross_cost already has VAT
                $order->gross_cost = $data->gross_cost + ($shipping_cost + $shipping_vat);
                $order->outstanding = $order->gross_cost-$order->paid;

                $order->save();
            }
        }
    }
}
