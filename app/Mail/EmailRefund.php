<?php

namespace App\Mail;

use App\Models\Refund;
use Dompdf\Dompdf;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EmailRefund extends Mailable
{
    use Queueable, SerializesModels;

    public $refund;
    public $pdf;

    /**
     * Create a new message instance.
     * @TODO finish implementation
     * @return void
     */
    public function __construct(Refund $refund)
    {
        $this->refund = $refund;

        $dompdf = new Dompdf();
        $options = $dompdf->getOptions();
        $options->isRemoteEnabled(true);
        $options->setIsHtml5ParserEnabled(true);
        $dompdf->setOptions($options);

        $dompdf->loadHtml(view('admin.refunds.templates.refund-pdf', ['detail' => $refund]));
        $dompdf->setPaper('A4');

        $dompdf->render();

        // Encode pdf data so it can be queued
        $this->pdf = base64_encode($dompdf->output());
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $filename = env('APP_NAME').'-refund-'.$this->refund->id.'.pdf';

        return $this->subject('You have a refund')->view('emails.send-customer-refund')
            ->attachData(base64_decode($this->pdf), $filename, [
                'mime' => 'application/pdf',
            ]);
    }
}
