<?php
namespace App\Models;

use App\Core\Database;

final class User
{
    public static function findByNric(string $nric): ?array
    {
        $sql = 'SELECT id, nric, password_hash, role, must_change_password 
        FROM users WHERE nric = :nric LIMIT 1';
        $stmt = Database::connection()->prepare($sql);
        $stmt->execute(['nric' => $nric]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public static function findById(int $id): ?array
    {
        $stmt = Database::connection()->prepare(
            'SELECT id, nric, password_hash, role, must_change_password 
            FROM users WHERE id = :id LIMIT 1'
        );
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public static function updatePassword(int $id, string $passwordHash): bool
    {
        $stmt = Database::connection()->prepare(
            'UPDATE users SET password_hash = :password_hash, must_change_password = 0, updated_at = NOW() WHERE id = :id'
        );
        return $stmt->execute([
            'password_hash' => $passwordHash,
            'id' => $id,
        ]);
    }
}
