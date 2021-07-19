<?php

namespace Diimolabs\Oauth2Client;

use Diimolabs\Oauth2Client\OAuthHttpClient;
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
        $this->app->singleton(OAuthHttpClient::class, function(){
            return new OAuthHttpClient();
        });
    }
}
