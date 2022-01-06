<?php
/* A very simple payment gateway
 * Build around stripe so may need modifying for other gateways
 */
namespace App\Repositories\Payments;

class PaymentGateway implements PaymentGatewayInterface
{
    public $provider;
    public function __construct($provider)
    {
        $this->provider = $provider;
        return $this;
    }

    public function create($amount, $metadata = [])
    {
        return $this->provider->create($amount, $metadata);
    }

    public function charge($amount, $metadata = [])
    {
        return $this->provider->charge($amount, $metadata = []);
    }

    public function refund($originalCharge, $amount, $metadata = [])
    {
        return $this->provider->refund($originalCharge, $amount, $metadata = []);
    }

    public function retrieve($type, $id)
    {
        return $this->provider->retrieve($type, $id);
    }
}
