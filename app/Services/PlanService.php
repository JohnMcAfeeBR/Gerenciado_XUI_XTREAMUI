<?php

namespace App\Services;

use App\Repositories\PlanRepository;

final class PlanService
{
    public function __construct(private readonly PlanRepository $plans)
    {
    }

    public function listForClient(): array
    {
        return $this->plans->allActive();
    }

    public function listForAdmin(): array
    {
        return $this->plans->all();
    }

    public function create(string $name, float $price, int $durationDays, string $providerPlanCode): int
    {
        return $this->plans->create($name, $price, $durationDays, $providerPlanCode);
    }
}
