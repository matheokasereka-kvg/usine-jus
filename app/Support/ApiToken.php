<?php

namespace App\Support;

use App\Models\User;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;

class ApiToken
{
    public static function issue(User $user): string
    {
        $payload = [
            'sub' => $user->id,
            'role' => $user->role,
            'exp' => now()->addHours(12)->timestamp,
        ];

        $body = rtrim(strtr(base64_encode(json_encode($payload)), '+/', '-_'), '=');
        $signature = hash_hmac('sha256', $body, Config::get('app.key'));

        return $body.'.'.$signature;
    }

    public static function userFromToken(?string $token): ?User
    {
        if (! $token || ! Str::contains($token, '.')) {
            return null;
        }

        [$body, $signature] = explode('.', $token, 2);
        $expected = hash_hmac('sha256', $body, Config::get('app.key'));

        if (! hash_equals($expected, $signature)) {
            return null;
        }

        $json = base64_decode(strtr($body, '-_', '+/'));
        $payload = json_decode($json ?: '', true);

        if (! is_array($payload) || ($payload['exp'] ?? 0) < now()->timestamp) {
            return null;
        }

        return User::find($payload['sub'] ?? null);
    }
}
