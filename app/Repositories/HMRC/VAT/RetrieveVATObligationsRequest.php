<?php

namespace App\Repositories\HMRC\VAT;

use App\Repositories\HMRC\GovernmentTestScenario\GovernmentTestScenario;
use App\Repositories\HMRC\Helpers\DateChecker;
use App\Repositories\HMRC\Helpers\VariableChecker;
use App\Repositories\HMRC\VAT\VATGetRequest;

class RetrieveVATObligationsRequest extends VATGetRequest
{
    /** @var array possible statuses, O is open and F is fulfilled */
    const POSSIBLE_STATUSES = [RetrieveVATObligationStatus::OPEN, RetrieveVATObligationStatus::FULFILLED];

    /** @var string from */
    protected $from;

    /** @var string to */
    protected $to;

    /** @var string status */
    protected $status;

    /**
     * VATObligationsRequest constructor.
     *
     * @param string $vrn VAT registration number
     * @param string|null $from correct format is YYYY-MM-DD, example 2021-01-25
     * @param string|null $to correct format is YYYY-MM-DD, example 2021-01-25
     * @param string|null $status correct status is O or F
     *
     * @throws \App\Repositories\HMRC\Exceptions\InvalidVariableValueException
     */
    public function __construct(string $vrn, string $from = null, string $to = null, string $status = null)
    {
        parent::__construct($vrn);

        if($status != 'O') {
            DateChecker::checkDateStringFormat($from, 'Y-m-d');
            DateChecker::checkDateStringFormat($to, 'Y-m-d');
        }

        $this->from = $from;
        $this->to = $to;
        $this->status = $status;

        if (!is_null($this->status)) {
            VariableChecker::checkPossibleValue($status, self::POSSIBLE_STATUSES);
        }
    }

    protected function getVatApiPath(): string
    {
        return '/obligations';
    }

    protected function getQueryString(): array
    {
        $queryArray = [
            'from' => $this->from,
            'to'   => $this->to,
        ];

        if (!is_null($this->status)) {
            $queryArray['status'] = $this->status;
        }

        return $queryArray;
    }

    /**
     * Get class that deal with government test scenario.
     *
     * @return GovernmentTestScenario
     */
    protected function getGovTestScenarioClass(): GovernmentTestScenario
    {
        return new RetrieveVATObligationsGovTestScenario();
    }
}
