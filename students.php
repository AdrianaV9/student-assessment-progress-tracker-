<?php
require_once __DIR__ . '/config/database.php';

$pageTitle = 'Students';
$search = trim($_GET['search'] ?? '');

if ($search !== '') {
    $statement = $pdo->prepare(
        'SELECT id, student_number, first_name, last_name, email, course_programme
         FROM students
         WHERE student_number LIKE :search
            OR first_name LIKE :search
            OR last_name LIKE :search
         ORDER BY last_name, first_name'
    );

    $statement->execute([
        'search' => '%' . $search . '%'
    ]);

    $students = $statement->fetchAll();

} else {
    $students = $pdo->query(
        'SELECT id, student_number, first_name, last_name, email, course_programme
         FROM students
         ORDER BY last_name, first_name'
    )->fetchAll();
}

require __DIR__ . '/includes/header.php';
?>

<section class="page-heading">
    <div>
        <p class="eyebrow">Students</p>
        <h2>Student Management</h2>
        <p>
            View and manage students currently stored in the system.
        </p>
    </div>

    <a href="add_student.php" class="button button-primary">
        + Add Student
    </a>
</section>

<?php if (isset($_GET['added'])): ?>
    <div class="alert alert-success" role="status">
        Student added successfully.
    </div>
<?php endif; ?>

<?php if (isset($_GET['deleted'])): ?>
    <div class="alert alert-success" role="status">
        Student deleted successfully.
    </div>
<?php endif; ?>

<section class="panel">
    <form method="get" action="students.php" class="search-form">
        <div class="form-group">
            <label for="search">Search students</label>

            <input
                type="search"
                id="search"
                name="search"
                placeholder="Student ID or name"
                value="<?= htmlspecialchars($search) ?>"
            >
        </div>

        <button
            type="submit"
            class="button button-secondary"
        >
            Search
        </button>

        <?php if ($search !== ''): ?>
            <a
                href="students.php"
                class="button button-secondary"
            >
                Clear
            </a>
        <?php endif; ?>
    </form>
</section>

<section class="panel">
    <?php if ($students): ?>
        <div class="table-wrapper">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Student ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Course / Programme</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>
                    <?php foreach ($students as $student): ?>
                        <tr>
                            <td>
                                <?= htmlspecialchars($student['student_number']) ?>
                            </td>

                            <td>
                                <?= htmlspecialchars(
                                    $student['first_name']
                                    . ' '
                                    . $student['last_name']
                                ) ?>
                            </td>

                            <td>
                                <?= htmlspecialchars(
                                    $student['email'] ?? ''
                                ) ?>
                            </td>

                            <td>
                                <?= htmlspecialchars(
                                    $student['course_programme'] ?? ''
                                ) ?>
                            </td>

                            <td>
                                <div class="table-actions">
                                    <a
                                        href="view_student.php?id=<?= (int) $student['id'] ?>"
                                        class="button button-small button-secondary"
                                    >
                                        View
                                    </a>

                                    <a
                                        href="edit_student.php?id=<?= (int) $student['id'] ?>"
                                        class="button button-small button-primary"
                                    >
                                        Edit
                                    </a>

                                    <a
                                        href="delete_student.php?id=<?= (int) $student['id'] ?>"
                                        class="button button-small button-danger"
                                    >
                                        Delete
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

    <?php else: ?>
        <p class="placeholder-note">
            No student records were found.
        </p>
    <?php endif; ?>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
