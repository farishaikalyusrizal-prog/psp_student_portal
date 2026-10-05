<?php $pageTitle = 'Access Denied'; require __DIR__ . '/../layouts/header.php'; ?>
<div class="text-center py-5">
    <div class="display-3 fw-bold text-danger">403</div>
    <h1 class="h3">Access denied</h1>
    <p class="text-secondary">You do not have permission to view this page.</p>
    <a class="btn btn-primary" href="<?= url($_SESSION['role'] === 'admin' ? 'students' : 'profile') ?>">Go Back</a>
</div>
<?php require __DIR__ . '/../layouts/footer.php'; ?>
