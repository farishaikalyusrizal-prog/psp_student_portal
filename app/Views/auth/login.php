<?php require __DIR__ . '/../layouts/header.php'; ?>
<div class="row justify-content-center">
    <div class="col-md-7 col-lg-5">
        <div class="card border-0 shadow-lg portal-card">
            <div class="card-body p-4 p-md-5">
                <div class="text-center mb-4">
                    <div class="icon-circle mx-auto mb-3"><i class="bi bi-mortarboard-fill"></i></div>
                    <h1 class="h3 fw-bold mb-1">Student Portal Login</h1>
                    <p class="text-secondary mb-0">Sign in using your NRIC and password.</p>
                </div>
                <form method="post" action="<?= url('login-submit') ?>" class="needs-validation" novalidate>
                    <?= csrf_field() ?>
                    <div class="mb-3">
                        <label class="form-label" for="nric">NRIC</label>
                        <input class="form-control" id="nric" name="nric" inputmode="numeric" pattern="[0-9]{12}" maxlength="12" required value="<?= old('nric') ?>" placeholder="e.g. 010101070001">
                        <div class="invalid-feedback">Enter exactly 12 digits.</div>
                    </div>
                    <div class="mb-4">
                        <label class="form-label" for="password">Password</label>
                        <div class="input-group">
                            <input class="form-control" type="password" id="password" name="password" required>
                            <button class="btn btn-outline-secondary password-toggle" type="button" data-target="password" aria-label="Show password"><i class="bi bi-eye"></i></button>
                            <div class="invalid-feedback">Password is required.</div>
                        </div>
                    </div>
                    <button class="btn btn-primary w-100 py-2 fw-semibold" type="submit"><i class="bi bi-box-arrow-in-right me-2"></i>Login</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php clear_old(); require __DIR__ . '/../layouts/footer.php'; ?>
