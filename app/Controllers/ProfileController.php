<?php

namespace App\Controllers;

use App\Models\Student;

final class ProfileController
{
    // =====================================================
    // SHOW STUDENT PROFILE
    // =====================================================
    public function show(): void
    {
        require_role('student');

        $student = Student::findByUserId(
            (int) $_SESSION['user_id']
        );

        if (!$student) {
            http_response_code(404);
            require __DIR__ . '/../Views/errors/404.php';
            return;
        }

        $pageTitle = 'My Profile';

        require __DIR__ . '/../Views/profile/show.php';
    }


    // =====================================================
    // UPLOAD PROFILE PICTURE
    // =====================================================
    public function upload(): void
    {
        require_role('student');

        // Protect form from invalid request
        verify_csrf();

        $userId = (int) $_SESSION['user_id'];

        $student = Student::findByUserId($userId);

        if (!$student) {
            http_response_code(404);
            require __DIR__ . '/../Views/errors/404.php';
            return;
        }


        // =================================================
        // CHECK FILE EXISTS
        // =================================================
        if (
            !isset($_FILES['profile_picture']) ||
            !is_array($_FILES['profile_picture'])
        ) {
            set_flash(
                'danger',
                'Please select a profile picture.'
            );

            redirect('profile');
        }


        $file = $_FILES['profile_picture'];


        // =================================================
        // CHECK UPLOAD ERROR
        // =================================================
        if ($file['error'] !== UPLOAD_ERR_OK) {

            set_flash(
                'danger',
                'An error occurred while uploading the file.'
            );

            redirect('profile');
        }


        // =================================================
        // CHECK FILE SIZE
        // MAXIMUM 2MB
        // =================================================
        $maxFileSize = 2 * 1024 * 1024;
        if (
            $file['size'] <= 0 ||
            $file['size'] > $maxFileSize
        ) {
            set_flash(
                'danger',
                'Profile picture must not exceed 2MB.'
            );

            redirect('profile');
        }
        $originalExtension = strtolower(
            pathinfo(
                $file['name'],
                PATHINFO_EXTENSION
            )
        );
        $allowedExtensions = [
            'jpg',
            'jpeg',
            'png'
        ];

        if (
            !in_array(
                $originalExtension,
                $allowedExtensions,
                true
            )
        ) {
            set_flash(
                'danger',
                'Only JPG, JPEG and PNG files are allowed.'
            );

            redirect('profile');
        }


        // =================================================
        // VERIFY REAL MIME TYPE
        // =================================================
        $finfo = new \finfo(FILEINFO_MIME_TYPE);

        $mimeType = $finfo->file(
            $file['tmp_name']
        );

        $allowedMimeTypes = [
            'image/jpeg' => 'jpg',
            'image/png'  => 'png'
        ];

        if (
            !isset(
                $allowedMimeTypes[$mimeType]
            )
        ) {
            set_flash(
                'danger',
                'Invalid image file. Only JPG, JPEG and PNG are allowed.'
            );

            redirect('profile');
        }


        // =================================================
        // MAKE SURE FILE IS A REAL UPLOADED FILE
        // =================================================
        if (
            !is_uploaded_file(
                $file['tmp_name']
            )
        ) {
            set_flash(
                'danger',
                'Invalid uploaded file.'
            );

            redirect('profile');
        }

        $safeExtension =
            $allowedMimeTypes[$mimeType];

        $newFilename =
            uniqid(
                'profile_',
                true
            )
            . '.'
            . $safeExtension;


        // =================================================
        // UPLOAD DIRECTORY
        // =================================================
        $uploadDirectory =
            __DIR__
            . '/../../public/uploads/profiles/';


        // Create folder if it does not exist
        if (!is_dir($uploadDirectory)) {

            mkdir(
                $uploadDirectory,
                0755,
                true
            );
        }


        $destination =
            $uploadDirectory
            . $newFilename;


        // =================================================
        // MOVE UPLOADED FILE
        // =================================================
        if (
            !move_uploaded_file(
                $file['tmp_name'],
                $destination
            )
        ) {
            set_flash(
                'danger',
                'Failed to save profile picture.'
            );

            redirect('profile');
        }


        // =================================================
        // SAVE OLD PROFILE PICTURE
        // =================================================
        $oldPicture =
            $student['profile_picture'] ?? null;


        // =================================================
        // UPDATE DATABASE
        // =================================================
        $updated =
            Student::updateProfilePicture(
                $userId,
                $newFilename
            );


        if (!$updated) {

            // Delete newly uploaded file
            if (is_file($destination)) {
                unlink($destination);
            }

            set_flash(
                'danger',
                'Failed to update profile picture.'
            );

            redirect('profile');
        }


        // =================================================
        // DELETE OLD PROFILE PICTURE
        // =================================================
        if (!empty($oldPicture)) {

            $oldFile =
                $uploadDirectory
                . basename($oldPicture);

            if (
                is_file($oldFile) &&
                $oldFile !== $destination
            ) {
                unlink($oldFile);
            }
        }


        // =================================================
        // SUCCESS
        // =================================================
        set_flash(
            'success',
            'Profile picture uploaded successfully.'
        );

        redirect('profile');
    }
}
