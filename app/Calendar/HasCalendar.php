<?php

namespace App\Calendar;

use App\Models\User;
use Google_Client;

trait HasCalendar
{
    /**
     * Authorise google to manage account
     * @throws \Google\Exception
     */
    public function requestCalendarAccess()
    {
        // Initialise the client.
        $client = new Google_Client();
        // Set the application name, this is included in the User-Agent HTTP header.
        $client->setApplicationName('Calendar integration');
        // Set the authentication credentials we downloaded from Google.
        $client->setAuthConfig('[private-path]/client_id.json');
        // Setting offline here means we can pull data from the venue's calendar when they are not actively using the site.
        $client->setAccessType("offline");
        // This will include any other scopes (Google APIs) previously granted by the venue
        $client->setIncludeGrantedScopes(true);
        // Set this to force to consent form to display.
        $client->setApprovalPrompt('force');
        // Add the Google Calendar scope to the request.
        $client->addScope(Google_Service_Calendar::CALENDAR);
        // Set the redirect URL back to the site to handle the OAuth2 response. This handles both the success and failure journeys.
        $client->setRedirectUri(url('/oauth2callback'));
    }

    /**/
    public function handleGoogleResponse(Request $request)
    {

    }
}
