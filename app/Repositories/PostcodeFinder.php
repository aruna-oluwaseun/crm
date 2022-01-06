<?php

namespace App\Repositories;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class PostcodeFinder
{
    protected $key;

    /**
     * PostcodeFinder constructor.
     */
    public function __construct() {
        $this->key = env('IDEAL_KEY', null);
    }

    /**
     * Make sure key valid
     * @return array
     */
    protected function checkKey()
    {
        if(is_null($this->key)) {
            $msg = 'Ideal postcodes api key is required obtain one here https://ideal-postcodes.co.uk/tokens and create an ENV variable IDEAL_KEY ';
            Log::error($msg);

            return [
                'success' => false,
                'message' => $msg
            ];
        }
    }

    /**
     * Retrieve addresses for postcode
     * @param string $postcode
     * @return object
     */
    public function getPostcode(string $postcode)
    {
        $this->checkKey();

        $url = env('IDEAL_POSTCODE_URL', 'https://api.ideal-postcodes.co.uk/v1/postcodes/').rawurlencode($postcode);
        $response = Http::get($url, [
            'api_key' => $this->key
        ]);

        return $this->response($response);
    }

    /**
     * Find address
     * @param string $address
     * @return array
     */
    public function findAddress(string $address)
    {
        $this->checkKey();

        $response = Http::get(env('IDEAL_ADDRESS_URL', 'https://api.ideal-postcodes.co.uk/v1/addresses'), [
            'query' => rawurlencode($address),
        ]);

        return $this->response($response);
    }

    /**
     * Return
     * @TODO Potential store postcode results for one week and call from DB to save calls to API
     * @param $response
     * @return array
     */
    protected function response($response) : array
    {
        // Determine if the status code is >= 200 and < 300...
        if( $response->successful() )
        {
            return [
                'success' => true,
                'result'  => $response->json()['result']
            ];
        }

        // Determine if the status code is >= 400...
        if( $response->failed() )
        {
            Log::error('Failed to find addresses, code: '.$response->json()['code'].' '.$response->json()['message']);
            return [
                'success' => false,
                'message'  => 'Failed to find addresses, code : '.$response->json()['code']
            ];
        }

        // Determine if the response has a 400 level status code...
        if( $response->clientError() )
        {
            Log::error('Failed to find addresses, code: '.$response->json()['code'].' '.$response->json()['message']);
            return [
                'success' => false,
                'message'  => 'Failed to find addresses, code : '.$response->json()['code']
            ];
        }

        // Determine if the response has a 500 level status code...
        if($response->serverError())
        {
            Log::error('Failed to find addresses, code: '.$response->json()['code'].' '.$response->json()['message']);
            return [
                'success' => false,
                'message'  => 'Server error could not fetch addresses, code : '.$response->json()['code']
            ];
        }

        return $response;
    }
}
