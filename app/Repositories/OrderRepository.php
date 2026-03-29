<?php

namespace App\Repositories;

use App\Config\Database;
use PDO;

final class OrderRepository
{
    public function create(int $userId, int $planId, float $amount, string $status = 'pending'): int
    {
        $sql = 'INSERT INTO orders (user_id, plan_id, amount, status) VALUES (:user_id, :plan_id, :amount, :status)';
        $stmt = Database::connection()->prepare($sql);
        $stmt->execute([
            ':user_id' => $userId,
            ':plan_id' => $planId,
            ':amount' => $amount,
            ':status' => $status,
        ]);

        return (int) Database::connection()->lastInsertId();
    }

    public function attachPaymentData(int $orderId, int $externalTxId, string $qrCode, string $qrCodeText, ?string $expiresAt): void
    {
        $sql = 'UPDATE orders
                SET payment_provider = :payment_provider,
                    payment_transaction_id = :payment_transaction_id,
                    pix_qr_code = :pix_qr_code,
                    pix_qr_code_text = :pix_qr_code_text,
                    pix_expires_at = :pix_expires_at
                WHERE id = :id';

        $stmt = Database::connection()->prepare($sql);
        $stmt->execute([
            ':payment_provider' => 'fastdepix',
            ':payment_transaction_id' => (string) $externalTxId,
            ':pix_qr_code' => $qrCode,
            ':pix_qr_code_text' => $qrCodeText,
            ':pix_expires_at' => $expiresAt,
            ':id' => $orderId,
        ]);
    }

    public function markAsPaid(int $orderId): void
    {
        $stmt = Database::connection()->prepare('UPDATE orders SET status = :status, paid_at = NOW() WHERE id = :id');
        $stmt->execute([':status' => 'paid', ':id' => $orderId]);
    }

    public function findByIdAndUser(int $orderId, int $userId): ?array
    {
        $stmt = Database::connection()->prepare('SELECT * FROM orders WHERE id = :id AND user_id = :user_id LIMIT 1');
        $stmt->execute([':id' => $orderId, ':user_id' => $userId]);
        $order = $stmt->fetch(PDO::FETCH_ASSOC);

        return $order ?: null;
    }

    public function byUser(int $userId): array
    {
        $sql = 'SELECT o.*, p.name AS plan_name FROM orders o JOIN plans p ON p.id = o.plan_id WHERE o.user_id = :user_id ORDER BY o.id DESC';
        $stmt = Database::connection()->prepare($sql);
        $stmt->execute([':user_id' => $userId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
