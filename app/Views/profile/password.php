<?php require __DIR__ . '/../layouts/header.php'; ?>
<div class="row justify-content-center">
    <div class="col-lg-7">
        <div class="card border-0 shadow-sm portal-card">
            <div class="card-body p-4 p-md-5">
                <h1 class="h3 fw-bold mb-2">Change Password</h1>
                <p class="text-secondary">Choose a strong password to keep your account secure.</p>
                <form method="post" action="<?= url('password-update') ?>" class="needs-validation" novalidate>
                    <?= csrf_field() ?>
                    <div class="mb-3">
                        <label class="form-label" for="old_password">Current Password</label>
                        <input class="form-control" type="password" id="old_password" name="old_password" required>
                        <div class="invalid-feedback">Current password is required.</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="new_password">New Password</label>
                        <input class="form-control" type="password" id="new_password" name="new_password" minlength="8" required>
                        <div class="form-text">Use at least 8 characters with uppercase, lowercase and a number.</div>
                        <div class="invalid-feedback">Enter a valid new password.</div>
                    </div>
                    <div class="mb-4">
                        <label class="form-label" for="confirm_password">Confirm New Password</label>
                        <input class="form-control" type="password" id="confirm_password" name="confirm_password" minlength="8" required>
                        <div class="invalid-feedback">Please confirm your new password.</div>
                    </div>
                    <button class="btn btn-primary" type="submit"><i class="bi bi-check2-circle me-2"></i>Save Password</button>
                    <a class="btn btn-outline-secondary" href="<?= url($_SESSION['role'] === 'admin' ? 'students' : 'profile') ?>">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</div>
<?php require __DIR__ . '/../layouts/footer.php'; ?>
