<?php

namespace App\Models;

use App\Core\Database;
use PDO;
use Throwable;

final class Student
{
    // =====================================================
    // FIND STUDENT BY LOGGED-IN USER ID
    // =====================================================
    public static function findByUserId(int $userId): ?array
    {
        $sql = 'SELECT 
                    s.id,
                    s.name,
                    s.profile_picture,
                    u.nric,
                    p.code AS program_code,
                    p.name AS program_name,
                    s.marks
                FROM students s
                INNER JOIN users u ON u.id = s.user_id
                INNER JOIN programs p ON p.id = s.program_id
                WHERE s.user_id = :user_id
                LIMIT 1';

        $stmt = Database::connection()->prepare($sql);

        $stmt->execute([
            'user_id' => $userId
        ]);

        $row = $stmt->fetch();

        return $row ?: null;
    }


    // =====================================================
    // GET ALL STUDENTS
    // =====================================================
    public static function all(): array
    {
        $sql = 'SELECT 
                    s.id,
                    s.user_id,
                    s.name,
                    u.nric,
                    p.code AS program_code,
                    p.name AS program_name,
                    s.marks
                FROM students s
                INNER JOIN users u ON u.id = s.user_id
                INNER JOIN programs p ON p.id = s.program_id
                ORDER BY s.name';

        $stmt = Database::connection()->prepare($sql);

        $stmt->execute();

        return $stmt->fetchAll();
    }


    // =====================================================
    // FIND STUDENT BY STUDENT ID
    // =====================================================
    public static function find(int $id): ?array
    {
        $sql = 'SELECT 
                    s.id,
                    s.user_id,
                    s.name,
                    s.program_id,
                    u.nric,
                    s.marks,
                    s.profile_picture
                FROM students s
                INNER JOIN users u ON u.id = s.user_id
                WHERE s.id = :id
                LIMIT 1';

        $stmt = Database::connection()->prepare($sql);

        $stmt->execute([
            'id' => $id
        ]);

        $row = $stmt->fetch();

        return $row ?: null;
    }


    // =====================================================
    // CHECK NRIC
    // =====================================================
    public static function nricExists(
        string $nric,
        ?int $excludeUserId = null
    ): bool
    {
        $sql = 'SELECT COUNT(*) FROM users WHERE nric = :nric';

        $params = [
            'nric' => $nric
        ];

        if ($excludeUserId !== null) {

            $sql .= ' AND id <> :exclude_user_id';

            $params['exclude_user_id'] = $excludeUserId;
        }

        $stmt = Database::connection()->prepare($sql);

        $stmt->execute($params);

        return (int) $stmt->fetchColumn() > 0;
    }


    // =====================================================
    // CREATE STUDENT
    // =====================================================
    public static function create(
        array $data,
        string $passwordHash
    ): int
    {
        $pdo = Database::connection();

        $pdo->beginTransaction();

        try {

            $userStmt = $pdo->prepare(
                'INSERT INTO users
                (
                    nric,
                    password_hash,
                    role,
                    must_change_password
                )
                VALUES
                (
                    :nric,
                    :password_hash,
                    :role,
                    1
                )'
            );

            $userStmt->execute([
                'nric' => $data['nric'],
                'password_hash' => $passwordHash,
                'role' => 'student',
            ]);

            $userId = (int) $pdo->lastInsertId();


            $studentStmt = $pdo->prepare(
                'INSERT INTO students
                (
                    user_id,
                    name,
                    program_id,
                    marks
                )
                VALUES
                (
                    :user_id,
                    :name,
                    :program_id,
                    :marks
                )'
            );

            $studentStmt->execute([
                'user_id' => $userId,
                'name' => $data['name'],
                'program_id' => $data['program_id'],
                'marks' => $data['marks'],
            ]);

            $studentId = (int) $pdo->lastInsertId();

            $pdo->commit();

            return $studentId;

        } catch (Throwable $e) {

            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }

            throw $e;
        }
    }


    // =====================================================
    // UPDATE STUDENT
    // =====================================================
    public static function update(
        int $id,
        array $data
    ): bool
    {
        $pdo = Database::connection();

        $pdo->beginTransaction();

        try {

            $student = self::find($id);

            if (!$student) {

                $pdo->rollBack();

                return false;
            }


            $userStmt = $pdo->prepare(
                'UPDATE users
                SET
                    nric = :nric,
                    updated_at = NOW()
                WHERE id = :user_id'
            );

            $userStmt->execute([
                'nric' => $data['nric'],
                'user_id' => $student['user_id'],
            ]);


            $studentStmt = $pdo->prepare(
                'UPDATE students
                SET
                    name = :name,
                    program_id = :program_id,
                    marks = :marks,
                    updated_at = NOW()
                WHERE id = :id'
            );

            $studentStmt->execute([
                'name' => $data['name'],
                'program_id' => $data['program_id'],
                'marks' => $data['marks'],
                'id' => $id,
            ]);


            $pdo->commit();

            return true;

        } catch (Throwable $e) {

            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }

            throw $e;
        }
    }


    // =====================================================
    // UPDATE PROFILE PICTURE
    // =====================================================
    public static function updateProfilePicture(
        int $userId,
        string $filename
    ): bool
    {
        $sql = 'UPDATE students
                SET
                    profile_picture = :profile_picture,
                    updated_at = NOW()
                WHERE user_id = :user_id';

        $stmt = Database::connection()->prepare($sql);

        return $stmt->execute([
            'profile_picture' => $filename,
            'user_id' => $userId
        ]);
    }


    // =====================================================
    // DELETE STUDENT
    // =====================================================
    public static function delete(int $id): bool
    {
        $student = self::find($id);

        if (!$student) {
            return false;
        }

        $stmt = Database::connection()->prepare(
            'DELETE FROM users
            WHERE id = :user_id
            AND role = :role'
        );

        return $stmt->execute([
            'user_id' => $student['user_id'],
            'role' => 'student',
        ]);
    }
}