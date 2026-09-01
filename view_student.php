<?php
require_once __DIR__ . '/config/database.php';

$pageTitle = 'View Student';

$studentId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$studentId) {
    http_response_code(400);
    die('Invalid student ID.');
}

$statement = $pdo->prepare(
    'SELECT id, student_number, first_name, last_name, email,
            course_programme, created_at, updated_at
     FROM students
     WHERE id = :id'
);

$statement->execute(['id' => $studentId]);
$student = $statement->fetch();

if (!$student) {
    http_response_code(404);
    die('Student record not found.');
}

require __DIR__ . '/includes/header.php';
?>

<section class="page-heading">
    <div>
        <p class="eyebrow">Students</p>
        <h2><?= htmlspecialchars($student['first_name'] . ' ' . $student['last_name']) ?></h2>
        <p>View the selected student's current details.</p>
    </div>

    <div class="page-actions">
        <a href="edit_student.php?id=<?= (int) $student['id'] ?>" class="button button-primary">
            Edit Student
        </a>
        <a href="students.php" class="button button-secondary">Back to Students</a>
    </div>
</section>

<?php if (isset($_GET['updated'])): ?>
    <div class="alert alert-success" role="status">
        Student details updated successfully.
    </div>
<?php endif; ?>

<section class="panel">
    <dl class="details-grid">
        <div class="details-item">
            <dt>Student ID</dt>
            <dd><?= htmlspecialchars($student['student_number']) ?></dd>
        </div>

        <div class="details-item">
            <dt>First Name</dt>
            <dd><?= htmlspecialchars($student['first_name']) ?></dd>
        </div>

        <div class="details-item">
            <dt>Last Name</dt>
            <dd><?= htmlspecialchars($student['last_name']) ?></dd>
        </div>

        <div class="details-item">
            <dt>Email</dt>
            <dd><?= htmlspecialchars($student['email'] ?: 'Not provided') ?></dd>
        </div>

        <div class="details-item">
            <dt>Course / Programme</dt>
            <dd><?= htmlspecialchars($student['course_programme'] ?: 'Not provided') ?></dd>
        </div>

        <div class="details-item">
            <dt>Last Updated</dt>
            <dd><?= htmlspecialchars($student['updated_at']) ?></dd>
        </div>
    </dl>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
