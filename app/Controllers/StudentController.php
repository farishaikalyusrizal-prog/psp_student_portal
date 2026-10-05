<?php

namespace App\Controllers;

use App\Models\Program;
use App\Models\Student;

final class StudentController
{
    /*
    |--------------------------------------------------------------------------
    | STUDENT LIST
    |--------------------------------------------------------------------------
    */

    public function index(): void
    {
        require_role('admin');

        $students = Student::all();

        $pageTitle = 'Grade Management';

        require __DIR__ . '/../Views/students/index.php';
    }


    /*
    |--------------------------------------------------------------------------
    | CREATE FORM
    |--------------------------------------------------------------------------
    */

    public function create(): void
    {
        require_role('admin');

        $programs = Program::all();

        $pageTitle = 'Add Student';

        require __DIR__ . '/../Views/students/create.php';
    }


    /*
    |--------------------------------------------------------------------------
    | STORE NEW STUDENT
    |--------------------------------------------------------------------------
    */

    public function store(): void
    {
        require_role('admin');

        verify_csrf();

        [$data, $errors] =
            $this->validateStudentInput($_POST);


        /*
        | Check duplicate NRIC
        */

        if (
            empty($errors)
            && Student::nricExists($data['nric'])
        ) {
            $errors[] = 'The NRIC already exists.';
        }


        /*
        | Validation failed
        */

        if (!empty($errors)) {

            set_old([
                'name' => $data['name'],
                'nric' => $data['nric'],
                'program_id' => (string) $data['program_id'],
                'marks' => $data['marks'],
            ]);

            $_SESSION['form_errors'] = $errors;

            redirect('student-create');

            return;
        }


        /*
        | Temporary student password
        */

        $temporaryPassword = 'Student@123';


        /*
        | Hash password before database
        */

        $passwordHash = password_hash(
            $temporaryPassword,
            PASSWORD_DEFAULT
        );


        /*
        | Create student
        */

        Student::create(
            $data,
            $passwordHash
        );


        clear_old();


        set_flash(
            'success',
            'Student account created successfully. Initial password: Student@123.'
        );


        redirect('students');
    }


    /*
    |--------------------------------------------------------------------------
    | EDIT FORM
    |--------------------------------------------------------------------------
    */

    public function edit(): void
    {
        require_role('admin');


        $id = filter_input(
            INPUT_GET,
            'id',
            FILTER_VALIDATE_INT
        );


        $student = $id
            ? Student::find((int) $id)
            : null;


        if (!$student) {

            http_response_code(404);

            require __DIR__
                . '/../Views/errors/404.php';

            return;
        }


        $programs = Program::all();

        $pageTitle = 'Edit Student';


        require __DIR__
            . '/../Views/students/edit.php';
    }


    /*
    |--------------------------------------------------------------------------
    | UPDATE STUDENT
    |--------------------------------------------------------------------------
    */

    public function update(): void
    {
        require_role('admin');

        verify_csrf();


        $id = filter_input(
            INPUT_POST,
            'id',
            FILTER_VALIDATE_INT
        );


        $student = $id
            ? Student::find((int) $id)
            : null;


        if (!$student) {

            http_response_code(404);

            require __DIR__
                . '/../Views/errors/404.php';

            return;
        }


        [$data, $errors] =
            $this->validateStudentInput($_POST);


        /*
        | Check duplicate NRIC
        */

        if (
            empty($errors)
            && Student::nricExists(
                $data['nric'],
                (int) $student['user_id']
            )
        ) {

            $errors[] =
                'The NRIC already belongs to another account.';
        }


        /*
        | Validation failed
        */

        if (!empty($errors)) {

            set_old([
                'name' => $data['name'],
                'nric' => $data['nric'],
                'program_id' => (string) $data['program_id'],
                'marks' => $data['marks'],
            ]);


            $_SESSION['form_errors'] = $errors;


            redirect(
                'student-edit',
                [
                    'id' => (int) $id
                ]
            );

            return;
        }


        Student::update(
            (int) $id,
            $data
        );


        clear_old();


        set_flash(
            'success',
            'Student details updated successfully.'
        );


        redirect('students');
    }


    /*
    |--------------------------------------------------------------------------
    | DELETE STUDENT
    |--------------------------------------------------------------------------
    */

    public function destroy(): void
    {
        require_role('admin');

        verify_csrf();


        $id = filter_input(
            INPUT_POST,
            'id',
            FILTER_VALIDATE_INT
        );


        if (
            !$id
            || !Student::delete((int) $id)
        ) {

            set_flash(
                'danger',
                'Unable to delete the selected student.'
            );


            redirect('students');

            return;
        }


        set_flash(
            'success',
            'Student record deleted successfully.'
        );


        redirect('students');
    }


    /*
    |--------------------------------------------------------------------------
    | SERVER-SIDE VALIDATION
    |--------------------------------------------------------------------------
    */

    private function validateStudentInput(
        array $input
    ): array {

        /*
        | NAME
        */

        $name = trim(
            (string) ($input['name'] ?? '')
        );


        /*
        | NRIC
        |
        | Keep NRIC as STRING.
        | Do not convert to integer because
        | Malaysian NRIC may begin with zero.
        */

        $nric = trim(
            (string) ($input['nric'] ?? '')
        );


        /*
        | Remove spaces accidentally entered
        */

        $nric = preg_replace(
            '/\s+/',
            '',
            $nric
        );


        /*
        | PROGRAM
        */

        $programId = filter_var(
            $input['program_id'] ?? null,
            FILTER_VALIDATE_INT
        );


        /*
        | MARKS
        */

        $marks = filter_var(
            $input['marks'] ?? null,
            FILTER_VALIDATE_FLOAT
        );


        $errors = [];


        /*
        |--------------------------------------------------------------------------
        | NAME VALIDATION
        |--------------------------------------------------------------------------
        */

        if (
            mb_strlen($name) < 2
            || mb_strlen($name) > 100
        ) {

            $errors[] =
                'Name must be between 2 and 100 characters.';
        }


        /*
        |--------------------------------------------------------------------------
        | NRIC VALIDATION
        |--------------------------------------------------------------------------
        |
        | Exactly 12 digits.
        |
        | Example:
        | 060309020267
        |
        */

        if (
            !preg_match(
                '/^[0-9]{12}$/',
                $nric
            )
        ) {

            $errors[] =
                'IC/NRIC must contain exactly 12 digits.';
        }


        /*
        |--------------------------------------------------------------------------
        | PROGRAM VALIDATION
        |--------------------------------------------------------------------------
        */

        if (
            !$programId
            || !Program::exists(
                (int) $programId
            )
        ) {

            $errors[] =
                'Please select a valid program.';
        }


        /*
        |--------------------------------------------------------------------------
        | MARKS VALIDATION
        |--------------------------------------------------------------------------
        */

        if (
            $marks === false
            || $marks < 0
            || $marks > 100
        ) {

            $errors[] =
                'Marks must be a number between 0 and 100.';
        }


        /*
        |--------------------------------------------------------------------------
        | RETURN CLEAN DATA
        |--------------------------------------------------------------------------
        */

        return [

            [
                'name' => $name,

                'nric' => $nric,

                'program_id' =>
                    $programId
                        ? (int) $programId
                        : 0,

                'marks' =>
                    $marks === false
                        ? ''
                        : number_format(
                            (float) $marks,
                            2,
                            '.',
                            ''
                        ),
            ],

            $errors
        ];
    }
}