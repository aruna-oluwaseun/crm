<?php

namespace App\Listeners;

use App\Models\Refund;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class UpdateRefundTotals
{
    public $tries = 5;

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
     * @param $event
     * @return void
     */
    public function handle($event)
    {
        $refund = $event->refund;

        if($refund instanceof Refund)
        {
            $data = \App\Models\RefundItem::where('refund_id',$refund->id)
                ->groupBy('refund_id')
                ->selectRaw('SUM(refund_net_cost) as net_cost,
                SUM(refund_vat_cost) as vat_cost,
                SUM(refund_gross_cost) as gross_cost')
                ->first();

            if($data && $data->count()) {

                $refund->net_cost = $data->net_cost;
                $refund->vat_cost = $data->vat_cost;
                $refund->gross_cost = $data->gross_cost;

                $refund->save();
            }
        }

    }

    public function failed($event, $exception)
    {
        Log::error('UpdateRefundTotals event failed, the event'.print_r($event, true).' : the exception '. print_r($exception, true));
    }
}
