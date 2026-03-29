<?php

namespace App\Services;

use App\Repositories\UserRepository;

final class AuthService
{
    public function __construct(private readonly UserRepository $users)
    {
    }

    public function register(string $name, string $email, string $password): int
    {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        return $this->users->create($name, $email, $hash, 'client');
    }

    public function attempt(string $email, string $password, string $role = 'client'): ?array
    {
        $user = $this->users->findByEmail($email);
        if ($user === null || $user['role'] !== $role) {
            return null;
        }

        if (!password_verify($password, $user['password_hash'])) {
            return null;
        }

        return $user;
    }
}
