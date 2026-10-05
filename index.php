<?php
declare(strict_types=1);

$secureCookie = !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off';
session_set_cookie_params([
    'lifetime' => 0,
    'path' => '/',
    'secure' => $secureCookie,
    'httponly' => true,
    'samesite' => 'Lax',
]);
session_start();

spl_autoload_register(function (string $class): void {
    $prefix = 'App\\';
    if (!str_starts_with($class, $prefix)) {
        return;
    }
    $relative = substr($class, strlen($prefix));
    $file = __DIR__ . '/app/' . str_replace('\\', '/', $relative) . '.php';
    if (is_file($file)) {
        require $file;
    }
});

require __DIR__ . '/app/Helpers/common.php';
require __DIR__ . '/app/Helpers/auth.php';
require __DIR__ . '/app/Helpers/csrf.php';

if (isset($_SESSION['last_activity']) && time() - (int) $_SESSION['last_activity'] > 1800) {
    $_SESSION = [];
    session_regenerate_id(true);
    set_flash('warning', 'Your session expired after 30 minutes of inactivity. Please log in again.');
    redirect('login');
}
if (is_logged_in()) {
    $_SESSION['last_activity'] = time();
}

use App\Controllers\AuthController;
use App\Controllers\PasswordController;
use App\Controllers\ProfileController;
use App\Controllers\StudentController;

$page = $_GET['page'] ?? (is_logged_in() ? ($_SESSION['role'] === 'admin' ? 'students' : 'profile') : 'login');

$routes = [
    'login' => [AuthController::class, 'showLogin', 'GET'],
    'login-submit' => [AuthController::class, 'login', 'POST'],
    'logout' => [AuthController::class, 'logout', 'POST'],

    'profile' => [ProfileController::class, 'show', 'GET'],
    'profile-upload' => [ProfileController::class, 'upload', 'POST'],

    'change-password' => [PasswordController::class, 'edit', 'GET'],
    'password-update' => [PasswordController::class, 'update', 'POST'],

    'students' => [StudentController::class, 'index', 'GET'],
    'student-create' => [StudentController::class, 'create', 'GET'],
    'student-store' => [StudentController::class, 'store', 'POST'],
    'student-edit' => [StudentController::class, 'edit', 'GET'],
    'student-update' => [StudentController::class, 'update', 'POST'],
    'student-delete' => [StudentController::class, 'destroy', 'POST'],
];

if (!isset($routes[$page])) {
    http_response_code(404);
    require __DIR__ . '/app/Views/errors/404.php';
    exit;
}

[$controllerClass, $method, $httpMethod] = $routes[$page];
if ($_SERVER['REQUEST_METHOD'] !== $httpMethod) {
    http_response_code(405);
    exit('Method Not Allowed');
}

$controller = new $controllerClass();
$controller->$method();
