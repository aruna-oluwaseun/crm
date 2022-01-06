<?php

namespace App\Listeners;

use App\Models\Payment;
use App\Models\Refund;
use App\Repositories\Payments\Exceptions\InvalidAPIKeyException;
use App\Repositories\Payments\PaymentGateway;
use App\Repositories\Payments\Providers\StripeGateway;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class ProcessRefund
{

    protected $key;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        if(get_setting('testing_payments'))
        {
            $this->key = env('STRIPE_TEST_SECRET');
        }
        else
        {
            $this->key = env('STRIPE_SECRET');
        }
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        if($event->refund instanceof Refund)
        {
            $refund = $event->refund;

            if(!$invoice = $refund->invoice) {
                info('No invoice associated with this refund');
                return;
            }

            if(!$invoice->payments()->income()->complete()->count()) {
                info('There are payments against invoice id #'.$invoice->id.' to refund');
                return;
            }

            $no_can_be_refunded_to_card = 0;
            $total_can_be_refunded_to_card = 0;
            $no_can_be_refunded_manually = 0;
            $total_can_be_refunded_manually = 0;
            $payment_refs = [];

            foreach($invoice->payments()->income()->complete()->get() as $value) {
                if($value->payment_ref == 'MANUAL-PAYMENT') {
                    $no_can_be_refunded_manually++;
                    $total_can_be_refunded_manually += $value->amount;
                } else {
                    $no_can_be_refunded_to_card++;
                    $total_can_be_refunded_to_card += $value->amount;
                    $payment_refs[$value->payment_ref] = $value->amount;
                }
            }

            if(!$total_can_be_refunded_to_card) {
                info('Refunds need to be made manually');
                // add in house notification to refund manually
                return;
            }

            if(empty($payment_refs)) {
                info('There are no payment refs to automatically refund, refund manually');
                // add in house notification to refund manually
                return;
            }

            try {
                $provider = new PaymentGateway(new StripeGateway($this->key));
            } catch (InvalidAPIKeyException $e) {
                custom_reporter($e);
                return;
            }

            // grab the original payment intent
            foreach($payment_refs as $ref => $amount) {
                if ($amount <= $total_can_be_refunded_to_card) {
                    try {
                        if(!$refunded = $provider->refund($ref, $amount))
                        {
                            info('Failed to refund via payment gateway');
                            return;
                        }

                        Payment::create([
                            'invoice_id'            => $invoice->id,
                            'refund_id'             => $refund->id,
                            'payment_ref'           => $refunded->id,
                            'amount'                => $amount,
                            'status'	            => 'complete',
                            'type'                  => 'refund',
                            'test_payment'          => get_setting('testing_payments')
                        ]);

                        $total_can_be_refunded_to_card-= $amount;

                    } catch (\Throwable $exception) {
                        info('An error occurred processing the refunds via the payment provider');
                        return;
                    }
                }
            }

            $refund->status = 'complete';
            $refund->save();
            // send email to customer about the refund
            info('Refund email would have fired here');

        }
    }
}
