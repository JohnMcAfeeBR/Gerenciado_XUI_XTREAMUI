<?php

namespace App\Integrations;

use App\Config\Env;

final class XuiOneClient
{
    public function __construct(private readonly HttpClient $http)
    {
    }

    public function createLine(string $username, string $password, string $packageId, int $days): array
    {
        $url = $this->baseUrl() . Env::get('XUI_CREATE_LINE_PATH', '/api/lines/create');

        $payload = [
            'username' => $username,
            'password' => $password,
            'package_id' => $packageId,
            'expires_at' => date('Y-m-d H:i:s', strtotime("+{$days} days")),
        ];

        return $this->http->postJson($url, $payload, $this->authHeaders());
    }

    public function ping(): array
    {
        $url = $this->baseUrl() . Env::get('XUI_PING_PATH', '/api/auth/me');

        return $this->http->getJson($url, $this->authHeaders());
    }

    private function authHeaders(): array
    {
        $mode = Env::get('XUI_AUTH_MODE', 'api_key');

        if ($mode === 'basic') {
            $username = Env::get('XUI_USERNAME', '');
            $password = Env::get('XUI_PASSWORD', '');
            return ['Authorization: Basic ' . base64_encode($username . ':' . $password)];
        }

        return ['X-API-KEY: ' . Env::get('XUI_API_KEY', '')];
    }

    private function baseUrl(): string
    {
        return rtrim(Env::get('XUI_BASE_URL', ''), '/');
    }
}
