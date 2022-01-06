<?php

namespace App\Repositories\HMRC\Hello;

use App\Repositories\HMRC\Request\RequestMethod;
use App\Repositories\HMRC\Request\RequestWithServerToken;

class HelloApplicationRequest extends RequestWithServerToken
{
    protected function getMethod(): string
    {
        return RequestMethod::GET;
    }

    protected function getApiPath(): string
    {
        return '/hello/application';
    }
}
