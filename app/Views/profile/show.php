<?php require __DIR__ . '/../layouts/header.php'; ?>

<?php
$profilePicture = $student['profile_picture'] ?? null;
?>

<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
    <div>
        <span class="badge text-bg-primary mb-2">Student Account</span>

        <h1 class="h2 fw-bold mb-1">
            My Profile
        </h1>

        <p class="text-secondary mb-0">
            View your personal and academic information.
        </p>
    </div>

    <a
        class="btn btn-outline-primary"
        href="<?= url('change-password') ?>"
    >
        <i class="bi bi-shield-lock me-2"></i>
        Change Password
    </a>
</div>


<div class="row g-4">

    <!-- ========================================= -->
    <!-- PROFILE PICTURE -->
    <!-- ========================================= -->
    <div class="col-lg-4">

        <div class="card border-0 shadow-sm portal-card h-100">

            <div class="card-body p-4 text-center">

                <h5 class="fw-bold mb-4">
                    Profile Picture
                </h5>


                <!-- CURRENT PROFILE PICTURE -->
                <div class="mb-4">

                    <?php if (!empty($profilePicture)): ?>

                        <img
                            src="public/uploads/profiles/<?= e($profilePicture) ?>"
                            alt="Profile Picture"
                            class="rounded-circle border shadow-sm"
                            width="160"
                            height="160"
                            style="object-fit: cover;"
                        >

                    <?php else: ?>

                        <div
                            class="rounded-circle bg-light border d-flex align-items-center justify-content-center mx-auto"
                            style="
                                width: 160px;
                                height: 160px;
                            "
                        >
                            <i
                                class="bi bi-person-circle text-secondary"
                                style="font-size: 100px;"
                            ></i>
                        </div>

                    <?php endif; ?>

                </div>


                <!-- ========================================= -->
                <!-- UPLOAD FORM -->
                <!-- ========================================= -->

                <form
                    method="post"
                    action="<?= url('profile-upload') ?>"
                    enctype="multipart/form-data"
                >

                    <?= csrf_field() ?>


                    <div class="mb-3 text-start">

                        <label
                            for="profile_picture"
                            class="form-label fw-semibold"
                        >
                            Choose Profile Picture
                        </label>


                        <input
                            type="file"
                            class="form-control"
                            id="profile_picture"
                            name="profile_picture"
                            accept=".jpg,.jpeg,.png"
                            required
                        >


                        <div class="form-text">
                            JPG, JPEG or PNG only. Maximum file size: 2MB.
                        </div>

                    </div>


                    <button
                        type="submit"
                        class="btn btn-primary w-100"
                    >
                        <i class="bi bi-cloud-arrow-up me-2"></i>
                        Upload Picture
                    </button>

                </form>

            </div>

        </div>

    </div>


    <!-- ========================================= -->
    <!-- STUDENT INFORMATION -->
    <!-- ========================================= -->

    <div class="col-lg-8">

        <div class="card border-0 shadow-sm portal-card h-100">

            <div class="card-body p-4">

                <h5 class="fw-bold mb-4">
                    Student Information
                </h5>


                <div class="row g-4">

                    <div class="col-md-6">

                        <div class="info-label">
                            Name
                        </div>

                        <div class="info-value">
                            <?= e($student['name']) ?>
                        </div>

                    </div>


                    <div class="col-md-6">

                        <div class="info-label">
                            NRIC
                        </div>

                        <div class="info-value">
                            <?= e($student['nric']) ?>
                        </div>

                    </div>


                    <div class="col-md-6">

                        <div class="info-label">
                            Program
                        </div>

                        <div class="info-value">

                            <?= e($student['program_code']) ?>
                            -
                            <?= e($student['program_name']) ?>

                        </div>

                    </div>


                    <div class="col-md-6">

                        <div class="info-label">
                            Marks
                        </div>

                        <div class="info-value">

                            <?= e(
                                number_format(
                                    (float) $student['marks'],
                                    2
                                )
                            ) ?>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>


<?php require __DIR__ . '/../layouts/footer.php'; ?>