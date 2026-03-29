<?php

namespace App\Integrations;

use App\Config\Env;

final class FastDePixClient
{
    public function __construct(private readonly HttpClient $http)
    {
    }

    public function createTransaction(float $amount, ?array $user = null): array
    {
        $base = rtrim(Env::get('FASTDEPIX_BASE_URL', 'https://fastdepix.space/api/v1'), '/');
        $url = $base . '/transactions';

        $payload = ['amount' => $amount];
        if ($user !== null) {
            $payload['user'] = $user;
        }

        return $this->http->postJson($url, $payload, $this->headers());
    }

    public function getTransaction(int $transactionId): array
    {
        $base = rtrim(Env::get('FASTDEPIX_BASE_URL', 'https://fastdepix.space/api/v1'), '/');
        $url = $base . '/transactions/' . $transactionId;

        return $this->http->getJson($url, $this->headers());
    }

    private function headers(): array
    {
        $key = Env::get('FASTDEPIX_API_KEY', '');
        return ['Authorization: Bearer ' . $key];
    }
}
