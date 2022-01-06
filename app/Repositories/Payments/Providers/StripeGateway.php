<?php

namespace App\Repositories\Payments\Providers;

use App\Repositories\Payments\Exceptions\InvalidAccessTypeException;
use App\Repositories\Payments\Exceptions\InvalidAPIKeyException;
use App\Repositories\Payments\PaymentGatewayInterface;

class StripeGateway implements PaymentGatewayInterface
{
    protected $client;
    protected $accessTypes = ['charge' => 'charges','refund' => 'refunds','intent' => 'paymentIntents'];

    /**
     * StripeGateway constructor.
     * @param $key
     * @throws InvalidAPIKeyException
     */
    public function __construct($key)
    {
        if(empty($key)) {
            throw new InvalidAPIKeyException('Your api key is missing, please add when initiating the gateway');
        }
        $this->key = $key;
        try {
            $this->client = new \Stripe\StripeClient($this->key);
        } catch (\Throwable $exception) {
            custom_reporter($exception);
        }
    }

    /**
     * Create payment intent
     * @param $amount
     * @param array $metadata
     * @return mixed
     */
    public function create($amount, $metadata = [])
    {
        $access = $this->accessTypes['intent'];

        $data = array_merge([
            'amount' => $amount*100, // smallest available currency
            'currency' => 'gbp',
            'payment_method_types' => ['card'],
        ], $metadata);

        return $this->client->{$access}->create($data);
    }

    /* using intents for stripe */
    public function charge($amount, $metadata = [])
    {
        throw new \Exception('Please use create method for the Stripe API, The create method generates a payment intent.');
    }

    /**
     * Refund a payment intent
     * @param $paymentIntent
     * @param $amount
     * @param array $metadata
     * @return mixed
     */
    public function refund($paymentIntent, $amount, $metadata = [])
    {
        $access = $this->accessTypes['refund'];

        $data = array_merge([
            'payment_intent' => $paymentIntent,
            'amount' => $amount*100 // in pence
        ],$metadata);

        return $this->client->{$access}->create($data);
    }

    /**
     * Retrieve a Stripe resource
     * @param $type
     * @param $id
     * @return mixed
     * @throws InvalidAccessTypeException
     */
    public function retrieve($type, $id)
    {
        $retrievable = array_keys($this->accessTypes);
        if(!$type || !in_array($type,$retrievable)) {
            throw new InvalidAccessTypeException('What are you retrieving... the source you are accessing is invalid or not found, the types allowed are '.implode(',',$this->accessTypes));
        }

        $access = $this->accessTypes[$type];

        return $this->client->{$access}->retrieve($id);
    }

}
