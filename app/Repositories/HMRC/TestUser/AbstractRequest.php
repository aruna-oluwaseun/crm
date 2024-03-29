<?php

namespace App\Repositories\HMRC\TestUser;

use App\Repositories\HMRC\Request\RequestHeader;
use App\Repositories\HMRC\Request\RequestHeaderValue;
use App\Repositories\HMRC\Request\RequestMethod;
use App\Repositories\HMRC\Request\RequestWithServerToken;

abstract class AbstractRequest extends RequestWithServerToken
{
    /** @var PostBody */
    protected $postBody;

    public function __construct(PostBody $postBody)
    {
        parent::__construct();

        $this->postBody = $postBody;
    }

    protected function getMethod(): string
    {
        return RequestMethod::POST;
    }

    protected function getHeaders(): array
    {
        return array_merge(parent::getHeaders(), [
            RequestHeader::CONTENT_TYPE => RequestHeaderValue::APPLICATION_JSON,
        ]);
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
