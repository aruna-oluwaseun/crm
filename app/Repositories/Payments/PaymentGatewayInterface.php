<?php

namespace App\Repositories\Payments;

interface PaymentGatewayInterface {

	public function create($amount, $metadata = []);

	public function charge($amount, $metadata = []);

	public function refund($originalCharge, $amount, $metadata = []);

	public function retrieve($type, $id);

}
