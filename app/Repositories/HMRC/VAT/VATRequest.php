<?php

namespace App\Repositories\HMRC\VAT;

use App\Repositories\HMRC\GovernmentTestScenario\GovernmentTestScenario;
use App\Repositories\HMRC\HTTP\Header;
use App\Repositories\HMRC\Request\RequestHeader;
use App\Repositories\HMRC\Request\RequestHeaderValue;
use App\Repositories\HMRC\Request\RequestWithAccessToken;

abstract class VATRequest extends RequestWithAccessToken
{
    /** @var string VAT registration number */
    protected $vrn;

    /** @var string */
    protected $govTestScenario;

    public function __construct(string $vrn)
    {
        parent::__construct();

        $this->vrn = $vrn;
    }

    protected function getApiPath(): string
    {
        return "/organisations/vat/{$this->vrn}".$this->getVatApiPath();
    }

    protected function getHeaders(): array
    {
        $ownHeaders = [
            RequestHeader::CONTENT_TYPE => RequestHeaderValue::APPLICATION_JSON,
        ];

        if (!is_null($this->govTestScenario)) {
            $ownHeaders[Header::GOV_TEST_SCENARIO] = $this->govTestScenario;
        }

        return array_merge($ownHeaders, parent::getHeaders());
    }

    /**
     * @return mixed
     */
    public function getGovTestScenario()
    {
        return $this->govTestScenario;
    }

    /**
     * @param string $govTestScenario
     *
     * @throws \App\Repositories\HMRC\Exceptions\InvalidVariableValueException
     * @throws \ReflectionException
     *
     * @return VATRequest
     */
    public function setGovTestScenario(string $govTestScenario): self
    {
        $this->govTestScenario = $govTestScenario;

        if (!is_null($this->govTestScenario)) {
            $this->getGovTestScenarioClass()->checkValid($this->govTestScenario);
        }

        return $this;
    }

    /**
     * Get class that deal with government test scenario.
     *
     * @return GovernmentTestScenario
     */
    abstract protected function getGovTestScenarioClass(): GovernmentTestScenario;

    /**
     * Get VAT Api path, the path should be after {$this->vrn}.
     *
     * @return string
     */
    abstract protected function getVatApiPath(): string;
}
