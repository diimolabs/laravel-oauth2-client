<?php

namespace Diimolabs\Oauth2Client;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class OAuthHttpClient
{
    private static $path;

    private static $host;

    private static $clientId;

    private static $clientSecret;

    private static function getCredentialsInfo() {
        return collect(json_decode(
            file_get_contents(self::$path)
        ));
    }

    private static function requestToken() {
        $response = Http::acceptJson()
            ->post(self::$host . '/oauth/token', [
                'grant_type' => 'client_credentials',
                'client_id' => self::$clientId,
                'client_secret' => self::$clientSecret,
            ]);

        if($response->failed()){
            abort(403);
        }

        Storage::put('oauth-credentials.json.key', $response->body());
    }

    private static function validateToken() {
        // check if exist file with token
        if(!file_exists(self::$path)){
            self::requestToken();
        }

        $data = self::getCredentialsInfo();

        $expiredDate = now()->parse(date("Y-m-d H:i:s", $data['expires_in']));

        if(now()->lt($expiredDate)){
            self::requestToken();
        }
    }

    private static function getAuthorizationToken() {
        self::validateToken();

        return self::getCredentialsInfo()['access_token'];
    }

    private static function getOauthAuthorization(){
        return [
            'Authorization' => 'Bearer ' . self::getAuthorizationToken()
        ];
    }

    public static function oauthRequest() {
        self::$path = storage_path('app/oauth-credentials.json.key');
        self::$host = config('oauth-client.host');
        self::$clientId = config('oauth-client.client_id');
        self::$clientSecret = config('oauth-client.client_secret');

        info(self::$path);
        return Http::withHeaders(
            self::getOauthAuthorization()
        );
    }
}
