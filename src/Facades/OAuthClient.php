<?php

namespace Diimolabs\OAuth\Facades;

use Diimolabs\OAuth\OAuthHttpClient;
use Illuminate\Support\Facades\Facade;

class OAuthClient extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return OAuthHttpClient::class;
    }
}
