<?php

namespace Diimolabs\Oauth2Client\Middleware;

use Closure;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class EnsureJwtIsValidMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param string $scopes
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $scopes = "")
    {
        try {
            if (! $request->bearerToken()){
                return response()->json([
                    'message' => 'You are not authorized'
                ], 401);
            }

            $authorizationHeader = $request->header('Authorization');
            $jwt = explode(' ', $authorizationHeader)[1];

            $publicKey = file_get_contents(storage_path('app/oauth-public.key'));

            JWT::decode($jwt, $publicKey, ['RS256']);
        } catch (\Throwable $th) {
            Log::error("[JWT Valid Error]: " . $th->getMessage() . '. On line:' . $th->getLine());

            return response('Internal server error', 500);
        }

        return $next($request);
    }
}
