<?php

namespace Diimolabs\OAuth;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class OAuthHttpClient
{
    private $path;

    private $host;

    private $clientId;

    private $clientSecret;

    private function getCredentialsInfo()
    {
        return collect(json_decode(
            file_get_contents($this->path)
        ));
    }

    private function requestToken()
    {
        $response = Http::acceptJson()
            ->post($this->host . '/oauth/token', [
                'grant_type' => 'client_credentials',
                'client_id' => $this->clientId,
                'client_secret' => $this->clientSecret,
            ]);

        if ($response->failed()) {
            abort(403);
        }

        Storage::put('oauth-credentials.json.key', $response->body());
    }

    private function validateToken()
    {
        if (! file_exists($this->path)) {
            $this->requestToken();
        }

        $data = $this->getCredentialsInfo();

        $expiredDate = now()->parse(date("Y-m-d H:i:s", $data['expires_in']));

        if (now()->lt($expiredDate)) {
            $this->requestToken();
        }
    }

    private function getAuthorizationToken()
    {
        $this->validateToken();

        return $this->getCredentialsInfo()['access_token'];
    }

    private function getOauthAuthorization()
    {
        return [
            'Authorization' => 'Bearer ' . $this->getAuthorizationToken()
        ];
    }

    public function request()
    {
        $this->path = storage_path('app/oauth-credentials.json.key');
        $this->host = config('oauth-client.host');
        $this->clientId = config('oauth-client.client_id');
        $this->clientSecret = config('oauth-client.client_secret');

        return Http::withHeaders(
            $this->getOauthAuthorization()
        );
    }
}
