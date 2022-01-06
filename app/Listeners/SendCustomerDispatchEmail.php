<?php

namespace App\Listeners;

use App\Mail\OrderDispacthed;
use App\Models\SalesOrderDispatch;
use App\Models\SalesOrderDispatchEmail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendCustomerDispatchEmail
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
            $order = $event->dispatch->salesOrder;

            if($order && $order->email)
            {
                Mail::to($order->email)->queue(new OrderDispacthed($event->dispatch));

                if(count(Mail::failures()) <= 0)
                {
                    SalesOrderDispatchEmail::create([
                        'sales_order_dispatch_id' => $event->dispatch->id,
                        'email' => $order->email
                    ]);
                }
            }
        }
    }
}
