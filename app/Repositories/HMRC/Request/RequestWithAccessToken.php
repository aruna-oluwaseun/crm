<?php

namespace App\Repositories\HMRC\Request;

use App\Repositories\HMRC\Exceptions\MissingAccessTokenException;
use App\Repositories\HMRC\Oauth2\AccessToken;
use App\Repositories\HMRC\Response\Response;
use League\OAuth2\Client\Token\AccessTokenInterface;

abstract class RequestWithAccessToken extends Request
{
    /** @var AccessTokenInterface */
    protected $accessToken;

    /**
     * RequestWithAccessToken constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->accessToken = AccessToken::get();
    }

    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws MissingAccessTokenException
     *
     * @return mixed|Response
     */
    public function fire()
    {
        if (is_null($this->accessToken)) {
            throw new MissingAccessTokenException('No access token, please set one using AccessToken class.');
        }

        if($this->accessToken->hasExpired()) {
            AccessToken::destroyToken();
            throw new MissingAccessTokenException('Your access token has expired.');
        }

        return parent::fire();
    }

    protected function getHeaders(): array
    {
        return array_merge(parent::getHeaders(), [
            RequestHeader::AUTHORIZATION => $this->getAuthorizationHeader($this->accessToken),
        ]);
    }
}
