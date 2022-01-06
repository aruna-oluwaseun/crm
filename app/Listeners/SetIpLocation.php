<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SetIpLocation
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        // Store in session to eliminate repeat calls to the IPSTACK api
        if( $ip_location = ip_locate() )
        {
            session()->put('ip_location', $ip_location);
        }
    }
}
