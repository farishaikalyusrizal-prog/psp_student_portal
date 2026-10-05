<?php
namespace App\Models;

use App\Core\Database;

final class Program
{
    public static function all(): array
    {
        $stmt = Database::connection()->prepare('SELECT id, code, name FROM programs ORDER BY code');
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public static function exists(int $id): bool
    {
        $stmt = Database::connection()->prepare('SELECT 1 FROM programs WHERE id = :id');
        $stmt->execute(['id' => $id]);
        return (bool) $stmt->fetchColumn();
    }
}
