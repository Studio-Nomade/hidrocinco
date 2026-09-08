<?php

declare(strict_types=1);

namespace App;

final class RecaptchaVerifier
{
    public function verify(?string $token, ?string $ip = null): bool
    {
        if (config('app.env') === 'development' && getenv('RECAPTCHA_TEST_MODE') === '1') {
            return hash_equals('test-pass', (string) $token);
        }
        $secret = (string) config('recaptcha.secret_key', '');
        if ($secret === '' || !$token) {
            return false;
        }
        $payload = http_build_query(array_filter(['secret' => $secret, 'response' => $token, 'remoteip' => $ip]));
        $context = stream_context_create(['http' => [
            'method' => 'POST', 'header' => "Content-Type: application/x-www-form-urlencoded\r\n",
            'content' => $payload, 'timeout' => 8, 'ignore_errors' => true,
        ]]);
        $response = @file_get_contents('https://www.google.com/recaptcha/api/siteverify', false, $context);
        if ($response === false) {
            return false;
        }
        $data = json_decode($response, true);
        return is_array($data) && ($data['success'] ?? false) === true;
    }
}
