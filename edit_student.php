<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/validation.php';

$pageTitle = 'Edit Student';

$studentId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$studentId) {
    http_response_code(400);
    die('Invalid student ID.');
}

$loadStatement = $pdo->prepare(
    'SELECT id, student_number, first_name, last_name, email, course_programme
     FROM students
     WHERE id = :id'
);

$loadStatement->execute(['id' => $studentId]);
$student = $loadStatement->fetch();

if (!$student) {
    http_response_code(404);
    die('Student record not found.');
}

$errors = [];

$studentNumber = $student['student_number'];
$firstName = $student['first_name'];
$lastName = $student['last_name'];
$email = $student['email'] ?? '';
$courseProgramme = $student['course_programme'] ?? '';

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
        $duplicateStatement = $pdo->prepare(
            'SELECT COUNT(*)
             FROM students
             WHERE student_number = :student_number
               AND id <> :id'
        );

        $duplicateStatement->execute([
            'student_number' => $studentNumber,
            'id' => $studentId
        ]);

        if ((int) $duplicateStatement->fetchColumn() > 0) {
            $errors[] = 'That student ID already exists.';
        }
    }

    if (!$errors && $email !== '') {
        $emailStatement = $pdo->prepare(
            'SELECT COUNT(*)
             FROM students
             WHERE email = :email
               AND id <> :id'
        );

        $emailStatement->execute([
            'email' => $email,
            'id' => $studentId
        ]);

        if ((int) $emailStatement->fetchColumn() > 0) {
            $errors[] = 'That email address is already used by another student.';
        }
    }

    if (!$errors) {
        try {
            $updateStatement = $pdo->prepare(
                'UPDATE students
                 SET student_number = :student_number,
                     first_name = :first_name,
                     last_name = :last_name,
                     email = :email,
                     course_programme = :course_programme
                 WHERE id = :id'
            );

            $updateStatement->execute([
                'student_number' => $studentNumber,
                'first_name' => $firstName,
                'last_name' => $lastName,
                'email' => $email !== '' ? $email : null,
                'course_programme' => $courseProgramme !== '' ? $courseProgramme : null,
                'id' => $studentId
            ]);

            header('Location: view_student.php?id=' . $studentId . '&updated=1');
            exit;
        } catch (PDOException $exception) {
            $errors[] = 'The student could not be updated. Please try again.';
        }
    }
}

require __DIR__ . '/includes/header.php';
?>

<section class="page-heading">
    <div>
        <p class="eyebrow">Students</p>
        <h2>Edit Student</h2>
        <p>Update the student's details below. Fields marked with * are required.</p>
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
    <form method="post"
          action="edit_student.php?id=<?= (int) $studentId ?>"
          class="form-grid">

        <div class="form-group">
            <label for="student_number">Student ID *</label>
            <input type="text" id="student_number" name="student_number"
                   maxlength="20" required
                   value="<?= htmlspecialchars($studentNumber) ?>">
        </div>

        <div class="form-group">
            <label for="first_name">First Name *</label>
            <input type="text" id="first_name" name="first_name"
                   maxlength="100" required
                   value="<?= htmlspecialchars($firstName) ?>">
        </div>

        <div class="form-group">
            <label for="last_name">Last Name *</label>
            <input type="text" id="last_name" name="last_name"
                   maxlength="100" required
                   value="<?= htmlspecialchars($lastName) ?>">
        </div>

        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email"
                   maxlength="255"
                   value="<?= htmlspecialchars($email) ?>">
        </div>

        <div class="form-group form-group-full">
            <label for="course_programme">Course / Programme</label>
            <input type="text" id="course_programme" name="course_programme"
                   maxlength="150"
                   value="<?= htmlspecialchars($courseProgramme) ?>">
        </div>

        <div class="form-actions form-group-full">
            <button type="submit" class="button button-primary">Save Changes</button>
            <a href="view_student.php?id=<?= (int) $studentId ?>"
               class="button button-secondary">Cancel</a>
        </div>
    </form>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
