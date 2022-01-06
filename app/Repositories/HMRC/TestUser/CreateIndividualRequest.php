<?php

namespace App\Repositories\HMRC\TestUser;

class CreateIndividualRequest extends AbstractRequest
{
    protected function getApiPath(): string
    {
        return '/create-test-user/individuals';
    }
}
