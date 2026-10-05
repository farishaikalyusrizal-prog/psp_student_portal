<?php
namespace App\Controllers;

use App\Models\User;

final class AuthController
{
    public function showLogin(): void
    {
        if (is_logged_in()) {
            redirect($_SESSION['role'] === 'admin' ? 'students' : 'profile');
        }
        $pageTitle = 'Login';
        require __DIR__ . '/../Views/auth/login.php';
    }

    public function login(): void
    {
        verify_csrf();

        $nric = preg_replace('/\D+/', '', trim($_POST['nric'] ?? ''));
        $password = (string) ($_POST['password'] ?? '');
        $errors = [];

        if (!preg_match('/^\d{12}$/', $nric)) {
            $errors[] = 'NRIC must contain exactly 12 digits.';
        }
        if ($password === '') {
            $errors[] = 'Password is required.';
        }

        $user = empty($errors) ? User::findByNric($nric) : null;
        if (!$user || !password_verify($password, $user['password_hash'])) {
            $errors[] = 'Invalid NRIC or password.';
        }

        if ($errors) {
            set_old(['nric' => $nric]);
            $_SESSION['form_errors'] = $errors;
            redirect('login');
        }

        session_regenerate_id(true);
        $_SESSION['user_id'] = (int) $user['id'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['last_activity'] = time();
        clear_old();

        if ($user['role'] === 'student' && (int) $user['must_change_password'] === 1) {
            set_flash('warning', 'Please change your temporary password before continuing.');
            redirect('change-password');
        }

        set_flash('success', 'Welcome back.');
        redirect($user['role'] === 'admin' ? 'students' : 'profile');
    }

    public function logout(): void
    {
        require_login();
        verify_csrf();
        $_SESSION = [];
        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
        }
        session_destroy();
        session_start();
        set_flash('success', 'You have been signed out.');
        redirect('login');
    }
}
