<?php

namespace Diimolabs\OAuth;

use Diimolabs\OAuth\Facades\OAuthClient;
use Illuminate\Support\ServiceProvider;

class OAuthServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/oauth-client.php' => config_path('oauth-client.php')
        ], 'oauth-client');
    }

    public function register()
    {
        $this->app->bind(OAuthClient::class, function(){
            return new OAuthClient;
        });
    }
}
