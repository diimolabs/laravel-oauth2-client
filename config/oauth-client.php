<?php

return [

    /*
    |--------------------------------------------------------------------------
    | OAuth2 Credentials information
    |--------------------------------------------------------------------------
    |
    | Information required to request tokens from the authorization server
    |
    */

    'host' => env('OAUTH_HOST', ''),

    'client_id' => env('OAUTH_CLIENT_ID', ''),

    'client_secret' => env('OAUTH_CLIENT_SECRET', ''),

    /*
    |--------------------------------------------------------------------------
    | External services
    |--------------------------------------------------------------------------
    |
    | A key => value pair array that consists on the different external services
    | that the resource server or application wants to communicate in which can
    | be called . E.g:
    | [ 'service1' => 'https://api.service1.com/v1' ]
    | So, you can use it within your preferred http client package like this:
    | $this->http->post(config('oauth-client.services.service1') . '/blog')
    |
    */

    'external_services' => [
        // "example" => "https://myservice.com/api"
    ],

];
