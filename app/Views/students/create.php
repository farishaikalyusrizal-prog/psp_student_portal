<?php require __DIR__ . '/../layouts/header.php'; ?>

<div class="row justify-content-center">
    <div class="col-lg-8">

        <div class="card border-0 shadow-sm portal-card">
            <div class="card-body p-4 p-md-5">

                <h1 class="h3 fw-bold mb-1">Add New Student</h1>

                <p class="text-secondary mb-4">
                    Enter the student details below to create a new account.
                </p>

                <form method="POST"
                      action="<?= url('student-store') ?>">

                    <?= csrf_field() ?>

                    <div class="row g-3">

                        <!-- NAME -->
                        <div class="col-md-7">

                            <label class="form-label" for="name">
                                Name
                            </label>

                            <input
                                type="text"
                                class="form-control"
                                id="name"
                                name="name"
                                minlength="2"
                                maxlength="100"
                                required
                                value="<?= e(old('name')) ?>"
                            >

                        </div>


                        <!-- NRIC -->
                        <div class="col-md-5">

                            <label class="form-label" for="nric">
                                IC / NRIC
                            </label>

                            <input
                                type="text"
                                class="form-control"
                                id="nric"
                                name="nric"
                                pattern="[0-9]{12}"
                                minlength="12"
                                maxlength="12"
                                inputmode="numeric"
                                autocomplete="off"
                                required
                                value="<?= e(old('nric')) ?>"
                            >

                            <div class="form-text">
                                Enter exactly 12 digits without "-".
                            </div>

                        </div>


                        <!-- PROGRAM -->
                        <div class="col-md-8">

                            <label class="form-label" for="program_id">
                                Program
                            </label>

                            <select
                                class="form-select"
                                id="program_id"
                                name="program_id"
                                required
                            >

                                <option value="">
                                    Choose program...
                                </option>

                                <?php foreach ($programs as $program): ?>

                                    <option
                                        value="<?= (int) $program['id'] ?>"
                                        <?= old('program_id') === (string) $program['id']
                                            ? 'selected'
                                            : '' ?>
                                    >

                                        <?= e(
                                            $program['code']
                                            . ' - '
                                            . $program['name']
                                        ) ?>

                                    </option>

                                <?php endforeach; ?>

                            </select>

                        </div>


                        <!-- MARKS -->
                        <div class="col-md-4">

                            <label class="form-label" for="marks">
                                Marks
                            </label>

                            <input
                                type="number"
                                class="form-control"
                                id="marks"
                                name="marks"
                                min="0"
                                max="100"
                                step="0.01"
                                required
                                value="<?= e(old('marks')) ?>"
                            >

                        </div>

                    </div>


                    <!-- TEMP PASSWORD -->
                    <div class="alert alert-info mt-4 small">

                        <i class="bi bi-info-circle me-2"></i>

                        Initial password:

                        <strong>
                            Student@123
                        </strong>

                        . The student will be asked to change it after
                        the first sign-in.

                    </div>


                    <!-- BUTTON -->
                    <div class="d-flex gap-2 mt-4">

                        <button
                            class="btn btn-primary"
                            type="submit"
                        >

                            <i class="bi bi-save me-2"></i>

                            Create Student

                        </button>


                        <a
                            class="btn btn-outline-secondary"
                            href="<?= url('students') ?>"
                        >
                            Cancel
                        </a>

                    </div>

                </form>

            </div>
        </div>

    </div>
</div>

<?php
clear_old();
require __DIR__ . '/../layouts/footer.php';
?>