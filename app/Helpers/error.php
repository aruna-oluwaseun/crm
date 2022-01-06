<?php

use Bugsnag\BugsnagLaravel\Facades\Bugsnag;

if(!function_exists('custom_reporter'))
{
    function custom_reporter($exception)
    {
        if(env('APP_DEBUG') === true)
        {
            report($exception);
        }

        // Report via Bugsnag
        else
        {
            Bugsnag::notifyException($exception);
        }

    }
}
