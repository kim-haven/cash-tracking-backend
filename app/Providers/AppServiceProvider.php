<?php

namespace App\Providers;

use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;
use Laravel\Sanctum\Sanctum;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Resolve API tokens from common client patterns (Bearer is standard).
        Sanctum::getAccessTokenFromRequestUsing(function (Request $request): ?string {
            $token = $request->bearerToken();
            if ($token !== null && $token !== '') {
                return $token;
            }

            $auth = $request->header('Authorization', '');
            if (preg_match('/^Token\s+(\S+)/i', $auth, $m)) {
                return $m[1];
            }

            $queryToken = $request->query('token');
            if (is_string($queryToken) && $queryToken !== '') {
                return $queryToken;
            }

            $header = $request->header('X-Api-Token');
            if (is_string($header) && $header !== '') {
                return $header;
            }

            return null;
        });
    }
}
