<?php
$flash = get_flash();
$formErrors = $_SESSION['form_errors'] ?? [];
unset($_SESSION['form_errors']);
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($pageTitle ?? 'PSP Student Portal') ?> | PSP Student Portal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="public/assets/css/style.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold" href="<?= is_logged_in() ? url($_SESSION['role'] === 'admin' ? 'students' : 'profile') : url('login') ?>">
            <i class="bi bi-mortarboard-fill me-2"></i>PSP Student Portal
        </a>
        <?php if (is_logged_in()): ?>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav" aria-controls="mainNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="mainNav">
                <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-2">
                    <?php if ($_SESSION['role'] === 'student'): ?>
                        <li class="nav-item"><a class="nav-link" href="<?= url('profile') ?>"><i class="bi bi-person-circle me-1"></i>Profile</a></li>
                    <?php else: ?>
                        <li class="nav-item"><a class="nav-link" href="<?= url('students') ?>"><i class="bi bi-table me-1"></i>Grade Management</a></li>
                    <?php endif; ?>
                    <li class="nav-item"><a class="nav-link" href="<?= url('change-password') ?>"><i class="bi bi-shield-lock me-1"></i>Change Password</a></li>
                    <li class="nav-item">
                        <form method="post" action="<?= url('logout') ?>" class="m-0">
                            <?= csrf_field() ?>
                            <button class="btn btn-light btn-sm ms-lg-2" type="submit"><i class="bi bi-box-arrow-right me-1"></i>Logout</button>
                        </form>
                    </li>
                </ul>
            </div>
        <?php endif; ?>
    </div>
</nav>

<main class="container py-5">
    <?php if ($flash): ?>
        <div class="alert alert-<?= e($flash['type']) ?> alert-dismissible fade show" role="alert">
            <?= e($flash['message']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if ($formErrors): ?>
        <div class="alert alert-danger" role="alert">
            <strong>Please correct the following:</strong>
            <ul class="mb-0 mt-2">
                <?php foreach ($formErrors as $error): ?>
                    <li><?= e($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>
