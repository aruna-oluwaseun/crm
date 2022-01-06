<?php

namespace App\Mail;

use App\Models\Holiday;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class HolidayRequest extends Mailable
{
    use Queueable, SerializesModels;

    public $holiday;

    /**
     * Create a new message instance.
     *
     * @param Holiday $holiday
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
        return $this->subject('Please review holiday request')->view('emails.holiday-request');
    }
}
