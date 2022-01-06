<?php

namespace App\Repositories\HMRC\VAT;

use App\Repositories\HMRC\Request\PostBody;
use App\Repositories\HMRC\Request\RequestMethod;
use App\Repositories\HMRC\Response\Response;
use App\Repositories\HMRC\VAT\VATRequest;

abstract class VATPostRequest extends VATRequest
{
    /** @var PostBody */
    protected $postBody;

    public function __construct(string $vrn, PostBody $postBody)
    {
        parent::__construct($vrn);

        $this->postBody = $postBody;
    }

    protected function getMethod(): string
    {
        return RequestMethod::POST;
    }

    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \App\Repositories\HMRC\Exceptions\InvalidPostBodyException
     * @throws \App\Repositories\HMRC\Exceptions\MissingAccessTokenException
     *
     * @return mixed|Response
     */
    public function fire()
    {
        $this->postBody->validate();

        return parent::fire();
    }

    protected function getHTTPClientOptions(): array
    {
        return array_merge([
            'json' => $this->postBody->toArray(),
        ], parent::getHTTPClientOptions());
    }
}
