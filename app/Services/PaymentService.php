<?php

namespace App\Services;

use App\Integrations\FastDePixClient;

final class PaymentService
{
    public function __construct(private readonly FastDePixClient $client)
    {
    }

    public function createPixPayment(float $amount, string $customerName): array
    {
        $user = ['name' => $customerName];
        $response = $this->client->createTransaction($amount, $user);

        if (($response['status'] ?? 0) !== 201 || !($response['body']['success'] ?? false)) {
            throw new \RuntimeException('Falha ao criar cobrança PIX no FastDePix.');
        }

        return $response['body']['data'] ?? [];
    }

    public function fetchTransaction(int $externalTransactionId): array
    {
        $response = $this->client->getTransaction($externalTransactionId);

        if (($response['status'] ?? 0) !== 200 || !($response['body']['success'] ?? false)) {
            throw new \RuntimeException('Falha ao consultar transação PIX no FastDePix.');
        }

        return $response['body']['data'] ?? [];
    }
}
