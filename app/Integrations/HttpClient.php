<?php

namespace App\Integrations;

final class HttpClient
{
    public function postJson(string $url, array $payload, array $headers = []): array
    {
        $headers = array_merge(['Content-Type: application/json'], $headers);
        return $this->request('POST', $url, json_encode($payload, JSON_THROW_ON_ERROR), $headers);
    }

    public function postForm(string $url, array $payload, array $headers = []): array
    {
        $headers = array_merge(['Content-Type: application/x-www-form-urlencoded'], $headers);
        return $this->request('POST', $url, http_build_query($payload), $headers);
    }

    public function getJson(string $url, array $headers = []): array
    {
        return $this->request('GET', $url, null, $headers);
    }

    private function request(string $method, string $url, ?string $body, array $headers): array
    {
        $ch = curl_init($url);

        $options = [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_TIMEOUT => 20,
            CURLOPT_CUSTOMREQUEST => $method,
        ];

        if ($body !== null) {
            $options[CURLOPT_POSTFIELDS] = $body;
        }

        curl_setopt_array($ch, $options);

        $response = curl_exec($ch);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if ($response === false) {
            $error = curl_error($ch);
            curl_close($ch);
            throw new \RuntimeException('HTTP error: ' . $error);
        }

        curl_close($ch);
        $decoded = json_decode($response, true);

        return [
            'status' => $status,
            'body' => is_array($decoded) ? $decoded : ['raw' => $response],
        ];
    }
}
