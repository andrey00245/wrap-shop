<?php

namespace App\Services\Apple;

use Firebase\JWT\JWT;
use SocialiteProviders\Apple\Provider as AppleProvider; // ✅ правильный путь

class CustomAppleProvider extends AppleProvider
{
    protected function getTokenFields($code)
    {
        return array_merge(parent::getTokenFields($code), [
            'client_secret' => $this->generateClientSecret(),
        ]);
    }

    protected function generateClientSecret()
    {
        $privateKey = file_get_contents(storage_path('AuthKey_F9FV3YB4V8.p8'));

        $payload = [
            'iss' => config('services.apple.team_id'),
            'iat' => time(),
            'exp' => time() + 86400 * 180,
            'aud' => 'https://appleid.apple.com',
            'sub' => config('services.apple.client_id'),
        ];

        return JWT::encode($payload, $privateKey, 'ES256', config('services.apple.key_id'));
    }
}
