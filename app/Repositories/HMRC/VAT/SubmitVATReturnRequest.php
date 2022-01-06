<?php

namespace App\Repositories\HMRC\VAT;

use App\Repositories\HMRC\GovernmentTestScenario\GovernmentTestScenario;
use App\Repositories\HMRC\VAT\VATPostRequest;

class SubmitVATReturnRequest extends VATPostRequest
{
    public function __construct(string $vrn, SubmitVATReturnPostBody $postBody)
    {
        parent::__construct($vrn, $postBody);
    }

    protected function getVatApiPath(): string
    {
        return '/returns';
    }

    /**
     * Get class that deal with government test scenario.
     *
     * @return GovernmentTestScenario
     */
    protected function getGovTestScenarioClass(): GovernmentTestScenario
    {
        return new SubmitVATReturnGovTestScenario();
    }
}
