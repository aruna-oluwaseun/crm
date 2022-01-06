<?php

namespace App\Mail;

use App\Models\SalesOrderDispatch;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderDispacthed extends Mailable
{
    use Queueable, SerializesModels;

    public $dispatch;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(SalesOrderDispatch $dispatch)
    {
        $this->dispatch = $dispatch;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Order dispatched')->view('emails.customer-order-dispatched');
    }
}
