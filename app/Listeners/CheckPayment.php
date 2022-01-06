<?php

namespace App\Listeners;

use App\Events\PaymentReceived;
use App\Mail\InvoicePaid;
use App\Models\Franchise;
use App\Models\Invoice;
use App\Models\Notification;
use App\Models\Payment;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class CheckPayment
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
     * @param  $event
     * @return void
     */
    public function handle($event)
    {
        if($event->invoice instanceof Invoice)
        {
            $invoice = $event->invoice;

            if(isset($invoice->payments) && $invoice->payments()->income()->complete()->count())
            {
                $completed = $invoice->payments()->income()->complete()->get();

                $paid_amount = 0;
                foreach($completed as $item)
                {
                    $paid_amount += $item->amount;
                }

                $order = $invoice->salesOrder;

                if($order)
                {
                    if($paid_amount >= $order->gross_cost)
                    {
                        $order->sales_order_status_id = 11; // paid
                    }
                    else
                    {
                        $order->sales_order_status_id = 10; // part paid
                    }

                    $order->paid = $paid_amount;

                    $order->save();
                }

                info('The franchise ID '. $invoice->franchise_id);
                $franchise = Franchise::with(['users'])->find($invoice->franchise_id);
                $franchise_users = $franchise->users;

                if($franchise_users->count())
                {

                    foreach($franchise_users as $franchise_user)
                    {
                        if($franchise_user->can('view-invoices'))
                        {
                            Mail::to($franchise_user->email)->queue(new InvoicePaid($invoice));

                            // Add in house notification
                            Notification::store([
                                'title'    => 'Payment received for invoice : <span class="text-primary">#'.$invoice->id.'</span>',
                                'user_id'  => $franchise_user->id,
                                'payload'  => null,
                                'url'      => 'invoices/detail/'.$invoice->id,
                            ]);
                        }
                    }
                }
            }
        }
    }
}
