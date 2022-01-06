<?php

namespace App\Mail;

use App\Models\Invoice;
use Dompdf\Dompdf;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EmailInvoice extends Mailable
{
    use Queueable, SerializesModels;

    public $invoice;
    public $pdf;

    /**
     * Create a new message instance.
     * @TODO add terms and conditions
     * @return void
     */
    public function __construct(Invoice $invoice)
    {
        $this->invoice = $invoice;

        $dompdf = new Dompdf();
        $options = $dompdf->getOptions();
        $options->isRemoteEnabled(true);
        $options->setIsHtml5ParserEnabled(true);
        $dompdf->setOptions($options);

        $dompdf->loadHtml(view('admin.invoices.templates.invoice-pdf', ['detail' => $invoice]));
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
        $filename = env('APP_NAME').'-invoice-'.$this->invoice->id.'.pdf';

        return $this->subject('You have an invoice')->view('emails.send-customer-invoice')
            ->attachData(base64_decode($this->pdf), $filename, [
                'mime' => 'application/pdf',
            ]);
    }
}
