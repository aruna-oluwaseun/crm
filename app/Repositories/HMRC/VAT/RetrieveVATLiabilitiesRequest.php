<?php

namespace App\Repositories\HMRC\VAT;

use App\Repositories\HMRC\GovernmentTestScenario\GovernmentTestScenario;
use App\Repositories\HMRC\Helpers\DateChecker;
use HMRC\VAT\VATGetRequest;

class RetrieveVATLiabilitiesRequest extends VATGetRequest
{
    private $from;

    private $to;

    /**
     * RetrieveVATLiabilities constructor.
     *
     * @param string $vrn
     * @param string $from
     * @param string $to
     *
     * @throws \App\Repositories\HMRC\Exceptions\InvalidDateFormatException
     */
    public function __construct(string $vrn, string $from, string $to)
    {
        parent::__construct($vrn);

        DateChecker::checkDateStringFormat($from, 'Y-m-d');
        DateChecker::checkDateStringFormat($to, 'Y-m-d');

        $this->from = $from;
        $this->to = $to;
    }

    /**
     * @return array
     */
    protected function getQueryString(): array
    {
        return [
            'from' => $this->from,
            'to'   => $this->to,
        ];
    }

    /**
     * Get class that deal with government test scenario.
     *
     * @return GovernmentTestScenario
     */
    protected function getGovTestScenarioClass(): GovernmentTestScenario
    {
        return new RetrieveVATLiabilitiesGovTestScenario();
    }

    /**
     * Get VAT Api path, the path should be after {$this->vrn}.
     *
     * @return string
     */
    protected function getVatApiPath(): string
    {
        return '/liabilities';
    }
}
