<?php

namespace App\Repositories\HMRC\Hello;

use App\Repositories\HMRC\Request\RequestMethod;
use App\Repositories\HMRC\Request\RequestWithAccessToken;

class HelloUserRequest extends RequestWithAccessToken
{
    protected function getMethod(): string
    {
        return RequestMethod::GET;
    }

    protected function getApiPath(): string
    {
        return '/hello/user';
    }
}
