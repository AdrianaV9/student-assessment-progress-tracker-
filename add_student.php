<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/validation.php';

$pageTitle = 'Add Student';

$errors = [];
$successMessage = '';

$studentNumber = '';
$firstName = '';
$lastName = '';
$email = '';
$courseProgramme = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $studentNumber = trim($_POST['student_number'] ?? '');
    $firstName = trim($_POST['first_name'] ?? '');
    $lastName = trim($_POST['last_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $courseProgramme = trim($_POST['course_programme'] ?? '');

    $errors = validateStudentFields(
        $studentNumber,
        $firstName,
        $lastName,
        $email,
        $courseProgramme
    );

    if (!$errors) {
        $duplicateCheck = $pdo->prepare(
            'SELECT COUNT(*) 
             FROM students 
             WHERE student_number = :student_number'
        );

        $duplicateCheck->execute([
            'student_number' => $studentNumber
        ]);

        if ((int) $duplicateCheck->fetchColumn() > 0) {
            $errors[] = 'That student ID already exists.';
        }
    }

    if (!$errors && $email !== '') {
        $emailCheck = $pdo->prepare(
            'SELECT COUNT(*)
             FROM students
             WHERE email = :email'
        );

        $emailCheck->execute([
            'email' => $email
        ]);

        if ((int) $emailCheck->fetchColumn() > 0) {
            $errors[] = 'That email address is already used by another student.';
        }
    }

    if (!$errors) {
        try {
            $insert = $pdo->prepare(
                'INSERT INTO students (
                    student_number,
                    first_name,
                    last_name,
                    email,
                    course_programme
                ) VALUES (
                    :student_number,
                    :first_name,
                    :last_name,
                    :email,
                    :course_programme
                )'
            );

            $insert->execute([
                'student_number'   => $studentNumber,
                'first_name'       => $firstName,
                'last_name'        => $lastName,
                'email'            => $email !== '' ? $email : null,
                'course_programme' => $courseProgramme !== '' ? $courseProgramme : null
            ]);

            header('Location: students.php?added=1');
            exit;

        } catch (PDOException $exception) {
            $errors[] = 'The student could not be saved. Please try again.';
        }
    }
}

require __DIR__ . '/includes/header.php';
?>

<section class="page-heading">
    <div>
        <p class="eyebrow">Students</p>
        <h2>Add Student</h2>
        <p>
            Enter the student's details below. Fields marked with * are required.
        </p>
    </div>
</section>

<?php if ($errors): ?>
    <div class="alert alert-error" role="alert">
        <strong>Please correct the following:</strong>
        <ul>
            <?php foreach ($errors as $error): ?>
                <li><?= htmlspecialchars($error) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<section class="panel">
    <form method="post" action="add_student.php" class="form-grid" novalidate>

        <div class="form-group">
            <label for="student_number">Student ID *</label>
            <input
                type="text"
                id="student_number"
                name="student_number"
                maxlength="20"
                required
                value="<?= htmlspecialchars($studentNumber) ?>"
            >
        </div>

        <div class="form-group">
            <label for="first_name">First Name *</label>
            <input
                type="text"
                id="first_name"
                name="first_name"
                maxlength="100"
                required
                value="<?= htmlspecialchars($firstName) ?>"
            >
        </div>

        <div class="form-group">
            <label for="last_name">Last Name *</label>
            <input
                type="text"
                id="last_name"
                name="last_name"
                maxlength="100"
                required
                value="<?= htmlspecialchars($lastName) ?>"
            >
        </div>

        <div class="form-group">
            <label for="email">Email</label>
            <input
                type="email"
                id="email"
                name="email"
                maxlength="255"
                value="<?= htmlspecialchars($email) ?>"
            >
        </div>

        <div class="form-group form-group-full">
            <label for="course_programme">Course / Programme</label>
            <input
                type="text"
                id="course_programme"
                name="course_programme"
                maxlength="150"
                value="<?= htmlspecialchars($courseProgramme) ?>"
            >
        </div>

        <div class="form-actions form-group-full">
            <button type="submit" class="button button-primary">
                Save Student
            </button>

            <a href="students.php" class="button button-secondary">
                Cancel
            </a>
        </div>
    </form>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
