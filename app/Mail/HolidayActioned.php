<?php

namespace App\Mail;

use App\Models\Holiday;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class HolidayActioned extends Mailable
{
    use Queueable, SerializesModels;

    public $holiday;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Holiday $holiday)
    {
        $this->holiday = $holiday;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Holiday request has been '.$this->holiday->status)->view('emails.holiday-response');
    }
}
