<?php

namespace App\Http\Middleware;

use App\Support\ApiToken;
use Closure;
use Illuminate\Http\Request;

class EnsureApiToken
{
    public function handle(Request $request, Closure $next)
    {
        $user = ApiToken::userFromToken($request->bearerToken());

        if (! $user) {
            return response()->json(['message' => 'Token API invalide ou expire.'], 401);
        }

        auth()->setUser($user);

        return $next($request);
    }
}
