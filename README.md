# OAuth2 between Laravel projects

A package that allows secure communication between two or more projects, focused mainly for use in microservices architectures, adding the Oauth2 authorization standard in addition to security at the network level by IP addresses and whitelists, which may already be owned.

## Features

- Simple implementation
- It does not increase the latency of requests between microservices.
- High level of security

## Prerequisites

1. Having an authorization server, it is recommended to use [Laravel Passport](https://laravel.com/docs/8.x/passport#client-credentials-grant-tokens) for this, specifically in the [Client Credentials Grant Tokens](https://laravel.com/docs/8.x/passport#client-credentials-grant-tokens) section

2. Store the file `oauth-public.key` at folder `storage/app/` in the microservices to communicate, this file is provided by the authorization server

## Installation

1. Import the library
    ```
    composer require diimolabs/laravel-oauth2-client
    ```

2. Add the following environment variables:
    ```
    OAUTH_HOST=
    OAUTH_CLIENT_ID=
    OAUTH_CLIENT_SECRET=
    ```
    And fill with the data provided by the authorization server when creating the client corresponding to the project

3. Implement the `middleware` that validates the authorization of the input requests, in the file `app/Http/kernel.php`
    ```php
    protected $routeMiddleware = [
        // Other middleware...
        'jwt' => \Diimolabs\Oauth2Client\Middleware\EnsureJwtIsValid::class
    ];
    ```

## Use

Example of requesting a resource to a microservice

```php
use Diimolabs\Oauth2Client\OAuthHttpClient;

Route::prefix('v1')->group(function(){
    Route::get('message', function(){
        return OAuthHttpClient::oauthRequest()
            ->get('http://msa-2.test/api/v1/hello-world')
            ->body();
    });
});

```

Example of a request from a microservice client

```php
Route::prefix('v1')->middleware('jwt')->group(function ()
{
    Route::get('/hello-world', function ()
    {
        return 'Hello world from microservice 2';
    });
});
```

# Extra

import the configuration file using:

```
php artisan vendor:publish --tag=oauth-client
```

in `external_services` you can manage the urls of your different services
