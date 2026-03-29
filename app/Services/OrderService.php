<?php

namespace App\Services;

use App\Repositories\OrderRepository;
use App\Repositories\PlanRepository;
use App\Repositories\UserRepository;

final class OrderService
{
    public function __construct(
        private readonly OrderRepository $orders,
        private readonly PlanRepository $plans,
        private readonly UserRepository $users,
        private readonly PaymentService $payments,
        private readonly ProviderService $providerService,
    ) {
    }

    public function createOrder(int $userId, int $planId): int
    {
        $plan = $this->plans->findById($planId);
        if ($plan === null) {
            throw new \RuntimeException('Plano não encontrado.');
        }

        $user = $this->users->findById($userId);
        if ($user === null) {
            throw new \RuntimeException('Cliente não encontrado.');
        }

        $orderId = $this->orders->create($userId, (int) $plan['id'], (float) $plan['price'], 'pending');

        $transaction = $this->payments->createPixPayment((float) $plan['price'], (string) $user['name']);

        $this->orders->attachPaymentData(
            $orderId,
            (int) ($transaction['id'] ?? 0),
            (string) ($transaction['qr_code'] ?? ''),
            (string) ($transaction['qr_code_text'] ?? ''),
            ($transaction['qr_code_expires_at'] ?? null)
        );

        return $orderId;
    }

    public function syncPaymentStatus(int $orderId, int $userId): void
    {
        $order = $this->orders->findByIdAndUser($orderId, $userId);
        if ($order === null || $order['status'] === 'paid' || empty($order['payment_transaction_id'])) {
            return;
        }

        $tx = $this->payments->fetchTransaction((int) $order['payment_transaction_id']);
        $status = $tx['status'] ?? null;

        if ($status === 'paid') {
            $this->orders->markAsPaid($orderId);

            $plan = $this->plans->findById((int) $order['plan_id']);
            if ($plan !== null) {
                $this->providerService->provisionLine(
                    username: 'client_' . $userId . '_' . $orderId,
                    password: bin2hex(random_bytes(4)),
                    planCode: (string) $plan['provider_plan_code'],
                    expiresInDays: (int) $plan['duration_days']
                );
            }
        }
    }

    public function listByUser(int $userId): array
    {
        return $this->orders->byUser($userId);
    }
}
