<?php

namespace App\Integrations;

use App\Config\Env;

final class XtreamUiClient
{
    public function __construct(private readonly HttpClient $http)
    {
    }

    public function createLine(string $username, string $password, string $packageId, int $days): array
    {
        $url = $this->baseUrl() . Env::get('XTREAM_API_PATH', '/api.php');

        $payload = [
            'username' => Env::get('XTREAM_USERNAME', ''),
            'password' => Env::get('XTREAM_PASSWORD', ''),
            'action' => 'user',
            'sub' => 'create',
            'user_data[username]' => $username,
            'user_data[password]' => $password,
            'user_data[bouquet][]' => $packageId,
            'user_data[exp_date]' => date('Y-m-d H:i:s', strtotime("+{$days} days")),
        ];

        return $this->http->postForm($url, $payload);
    }

    public function ping(): array
    {
        $url = $this->baseUrl() . Env::get('XTREAM_API_PATH', '/api.php');

        return $this->http->postForm($url, [
            'username' => Env::get('XTREAM_USERNAME', ''),
            'password' => Env::get('XTREAM_PASSWORD', ''),
            'action' => 'stats',
        ]);
    }

    private function baseUrl(): string
    {
        return rtrim(Env::get('XTREAM_BASE_URL', ''), '/');
    }
}
