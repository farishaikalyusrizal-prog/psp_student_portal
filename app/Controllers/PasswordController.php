<?php
namespace App\Controllers;

use App\Models\User;

final class PasswordController
{
    public function edit(): void
    {
        require_login();
        $pageTitle = 'Change Password';
        require __DIR__ . '/../Views/profile/password.php';
    }

    public function update(): void
    {
        require_login();
        verify_csrf();

        $oldPassword = (string) ($_POST['old_password'] ?? '');
        $newPassword = (string) ($_POST['new_password'] ?? '');
        $confirmPassword = (string) ($_POST['confirm_password'] ?? '');
        $errors = [];

        $user = User::findById((int) $_SESSION['user_id']);
        if (!$user || !password_verify($oldPassword, $user['password_hash'])) {
            $errors[] = 'Current password is incorrect.';
        }
        if (strlen($newPassword) < 8 ||
            !preg_match('/[A-Z]/', $newPassword) ||
            !preg_match('/[a-z]/', $newPassword) ||
            !preg_match('/\d/', $newPassword)) {
            $errors[] = 'New password must be at least 8 characters and include 
            uppercase, lowercase, and a number.';
        }
        if ($newPassword !== $confirmPassword) {
            $errors[] = 'New password and confirm password do not match.';
        }
        if ($oldPassword !== '' && hash_equals($oldPassword, $newPassword)) {
            $errors[] = 'New password must be different from the old password.';
        }

        if ($errors) {
            $_SESSION['form_errors'] = $errors;
            redirect('change-password');
        }

        $hash = password_hash($newPassword, PASSWORD_DEFAULT);
        User::updatePassword((int) $_SESSION['user_id'], $hash);
        set_flash('success', 'Password updated successfully.');
        redirect($_SESSION['role'] === 'admin' ? 'students' : 'profile');
    }
}
