<?php require __DIR__ . '/../layouts/header.php'; ?>
<?php
$nameValue = $_SESSION['old']['name'] ?? $student['name'];
$nricValue = $_SESSION['old']['nric'] ?? $student['nric'];
$programValue = $_SESSION['old']['program_id'] ?? $student['program_id'];
$marksValue = $_SESSION['old']['marks'] ?? $student['marks'];
?>
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm portal-card">
            <div class="card-body p-4 p-md-5">
                <h1 class="h3 fw-bold mb-1">Update Student</h1>
                <p class="text-secondary mb-4">Update the student's profile and assessment marks.</p>
                <form method="post" action="<?= url('student-update') ?>" class="needs-validation" novalidate>
                    <?= csrf_field() ?>
                    <input type="hidden" name="id" value="<?= (int) $student['id'] ?>">
                    <div class="row g-3">
                        <div class="col-md-7">
                            <label class="form-label" for="name">Name</label>
                            <input class="form-control" id="name" name="name" minlength="2" maxlength="100" required value="<?= e($nameValue) ?>">
                            <div class="invalid-feedback">Enter the student's name.</div>
                        </div>
                        <div class="col-md-5">
                            <label class="form-label" for="nric">IC / NRIC</label>
                            <input class="form-control" id="nric" name="nric" pattern="[0-9]{12}" maxlength="12" inputmode="numeric" required value="<?= e($nricValue) ?>">
                            <div class="invalid-feedback">Enter exactly 12 digits.</div>
                        </div>
                        <div class="col-md-8">
                            <label class="form-label" for="program_id">Program</label>
                            <select class="form-select" id="program_id" name="program_id" required>
                                <?php foreach ($programs as $program): ?>
                                    <option value="<?= (int) $program['id'] ?>" <?= (string)$programValue === (string)$program['id'] ? 'selected' : '' ?>><?= e($program['code'] . ' - ' . $program['name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label" for="marks">Marks</label>
                            <input class="form-control" type="number" step="0.01" min="0" max="100" id="marks" name="marks" required value="<?= e((string)$marksValue) ?>">
                            <div class="invalid-feedback">Marks must be 0-100.</div>
                        </div>
                    </div>
                    <div class="d-flex gap-2 mt-4">
                        <button class="btn btn-primary" type="submit"><i class="bi bi-check2-circle me-2"></i>Save Changes</button>
                        <a class="btn btn-outline-secondary" href="<?= url('students') ?>">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php clear_old(); require __DIR__ . '/../layouts/footer.php'; ?>
