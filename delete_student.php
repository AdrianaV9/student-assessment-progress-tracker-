<?php
require_once __DIR__ . '/config/database.php';

$pageTitle = 'Delete Student';

$studentId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$studentId) {
    http_response_code(400);
    die('Invalid student ID.');
}

$statement = $pdo->prepare(
    'SELECT id, student_number, first_name, last_name, email, course_programme
     FROM students
     WHERE id = :id'
);

$statement->execute(['id' => $studentId]);
$student = $statement->fetch();

if (!$student) {
    http_response_code(404);
    die('Student record not found.');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $postedId = filter_input(INPUT_POST, 'student_id', FILTER_VALIDATE_INT);

    if (!$postedId || $postedId !== $studentId) {
        http_response_code(400);
        die('Invalid deletion request.');
    }

    try {
        $deleteStatement = $pdo->prepare(
            'DELETE FROM students
             WHERE id = :id'
        );

        $deleteStatement->execute([
            'id' => $studentId
        ]);

        header('Location: students.php?deleted=1');
        exit;

    } catch (PDOException $exception) {
        $errorMessage = 'The student could not be deleted. Please try again.';
    }
}

require __DIR__ . '/includes/header.php';
?>

<section class="page-heading">
    <div>
        <p class="eyebrow">Students</p>
        <h2>Delete Student</h2>
        <p>
            Confirm that you want to permanently delete this student record.
        </p>
    </div>
</section>

<?php if (!empty($errorMessage)): ?>
    <div class="alert alert-error" role="alert">
        <?= htmlspecialchars($errorMessage) ?>
    </div>
<?php endif; ?>

<section class="panel delete-confirmation">
    <div class="warning-box">
        <h3>Confirm deletion</h3>

        <p>
            You are about to delete:
        </p>

        <p class="student-delete-name">
            <?= htmlspecialchars(
                $student['student_number']
                . ' - '
                . $student['first_name']
                . ' '
                . $student['last_name']
            ) ?>
        </p>

        <p>
            This action cannot be undone. Any result records linked to this
            student will also be removed because of the database relationship.
        </p>
    </div>

    <form
        method="post"
        action="delete_student.php?id=<?= (int) $studentId ?>"
        class="delete-actions"
    >
        <input
            type="hidden"
            name="student_id"
            value="<?= (int) $studentId ?>"
        >

        <button
            type="submit"
            class="button button-danger"
        >
            Yes, Delete Student
        </button>

        <a
            href="students.php"
            class="button button-secondary"
        >
            Cancel
        </a>
    </form>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
