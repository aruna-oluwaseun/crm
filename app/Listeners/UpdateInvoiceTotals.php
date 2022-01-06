<?php

namespace App\Listeners;

use App\Events\InvoiceCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class UpdateInvoiceTotals
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
     * @param  InvoiceCreated  $event
     * @return void
     */
    public function handle(InvoiceCreated $event)
    {
        $invoice = $event->invoice;

        $net_cost = 0;
        $discount_cost = 0;
        $vat_cost = 0;
        $gross_cost = 0;

        foreach($invoice->items as $item)
        {
            $net_cost += $item->net_cost;
            $vat_cost += $item->vat_cost;
            $gross_cost += $item->gross_cost;
        }

        // check the see if original shipping is on this invoice
        if($invoice->shipping) {
            $vat_cost += $invoice->shipping->shipping_vat;
            $net_cost += $invoice->shipping->shipping_cost;
            $gross_cost += $invoice->shipping->shipping_gross;
        }

        $invoice->net_cost = $net_cost;
        $invoice->vat_cost = $vat_cost;
        $invoice->gross_cost = $gross_cost;
        $invoice->save();
    }
}
