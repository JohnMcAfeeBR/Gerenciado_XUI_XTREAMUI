<?php

namespace App\Repositories;

use App\Config\Database;
use PDO;

final class PlanRepository
{
    public function allActive(): array
    {
        $stmt = Database::connection()->query('SELECT * FROM plans WHERE is_active = 1 ORDER BY price ASC');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function all(): array
    {
        $stmt = Database::connection()->query('SELECT * FROM plans ORDER BY id DESC');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create(string $name, float $price, int $durationDays, string $providerPlanCode): int
    {
        $sql = 'INSERT INTO plans (name, price, duration_days, provider_plan_code, is_active) VALUES (:name, :price, :duration_days, :provider_plan_code, 1)';
        $stmt = Database::connection()->prepare($sql);
        $stmt->execute([
            ':name' => $name,
            ':price' => $price,
            ':duration_days' => $durationDays,
            ':provider_plan_code' => $providerPlanCode,
        ]);

        return (int) Database::connection()->lastInsertId();
    }

    public function findById(int $id): ?array
    {
        $stmt = Database::connection()->prepare('SELECT * FROM plans WHERE id = :id LIMIT 1');
        $stmt->execute([':id' => $id]);
        $plan = $stmt->fetch(PDO::FETCH_ASSOC);

        return $plan ?: null;
    }
}
