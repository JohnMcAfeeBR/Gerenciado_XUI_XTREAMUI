<?php

namespace App\Repositories;

use App\Config\Database;
use PDO;

final class UserRepository
{
    public function create(string $name, string $email, string $passwordHash, string $role = 'client'): int
    {
        $sql = 'INSERT INTO users (name, email, password_hash, role) VALUES (:name, :email, :password_hash, :role)';
        $stmt = Database::connection()->prepare($sql);
        $stmt->execute([
            ':name' => $name,
            ':email' => $email,
            ':password_hash' => $passwordHash,
            ':role' => $role,
        ]);

        return (int) Database::connection()->lastInsertId();
    }

    public function findByEmail(string $email): ?array
    {
        $stmt = Database::connection()->prepare('SELECT * FROM users WHERE email = :email LIMIT 1');
        $stmt->execute([':email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        return $user ?: null;
    }

    public function findById(int $id): ?array
    {
        $stmt = Database::connection()->prepare('SELECT * FROM users WHERE id = :id LIMIT 1');
        $stmt->execute([':id' => $id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        return $user ?: null;
    }
}
