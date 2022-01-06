<?php

namespace App\Repositories\HMRC\Hello;

use App\Repositories\HMRC\Request\Request;
use App\Repositories\HMRC\Request\RequestMethod;

class HelloWorldRequest extends Request
{
    protected function getMethod(): string
    {
        return RequestMethod::GET;
    }

    protected function getApiPath(): string
    {
        return '/hello/world';
    }
}
