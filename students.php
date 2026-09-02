<?php
require_once __DIR__ . '/config/database.php';

$pageTitle = 'Students';

$search = trim($_GET['q'] ?? '');
$courseFilter = trim($_GET['course'] ?? '');

/*
 * Load available programme values for the filter.
 * Blank/null programme values are excluded.
 */
$courseStatement = $pdo->query(
    'SELECT DISTINCT course_programme
     FROM students
     WHERE course_programme IS NOT NULL
       AND TRIM(course_programme) <> \'\'
     ORDER BY course_programme'
);

$courses = $courseStatement->fetchAll(PDO::FETCH_COLUMN);

$sql = '
    SELECT
        id,
        student_number,
        first_name,
        last_name,
        email,
        course_programme
    FROM students
    WHERE 1 = 1
';

$params = [];

if ($search !== '') {
    /*
     * Native PDO prepared statements require a unique named
     * placeholder for each occurrence in the SQL statement.
     */
    $sql .= '
        AND (
            student_number LIKE :search_student_number
            OR first_name LIKE :search_first_name
            OR last_name LIKE :search_last_name
            OR CONCAT(first_name, \' \', last_name) LIKE :search_full_name
            OR email LIKE :search_email
        )
    ';

    $searchValue = '%' . $search . '%';

    $params['search_student_number'] = $searchValue;
    $params['search_first_name'] = $searchValue;
    $params['search_last_name'] = $searchValue;
    $params['search_full_name'] = $searchValue;
    $params['search_email'] = $searchValue;
}

if ($courseFilter !== '') {
    $sql .= '
        AND course_programme = :course
    ';

    $params['course'] = $courseFilter;
}

$sql .= '
    ORDER BY last_name, first_name, student_number
';

$statement = $pdo->prepare($sql);
$statement->execute($params);
$students = $statement->fetchAll();

$hasFilters = $search !== '' || $courseFilter !== '';

require __DIR__ . '/includes/header.php';
?>

<section class="page-heading">
    <div>
        <p class="eyebrow">Students</p>
        <h2>Student Management</h2>
        <p>
            Search, filter and manage student records.
        </p>
    </div>

    <a
        href="add_student.php"
        class="button button-primary"
    >
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

<section class="panel student-search-panel">
    <form
        method="get"
        action="students.php"
        class="student-search-form"
    >
        <div class="form-group">
            <label for="q">Search students</label>

            <input
                type="search"
                id="q"
                name="q"
                value="<?= htmlspecialchars($search) ?>"
                placeholder="Student ID, name or email"
            >
        </div>

        <div class="form-group">
            <label for="course">Course / Programme</label>

            <select
                id="course"
                name="course"
            >
                <option value="">All programmes</option>

                <?php foreach ($courses as $course): ?>
                    <option
                        value="<?= htmlspecialchars($course) ?>"
                        <?= $courseFilter === $course ? 'selected' : '' ?>
                    >
                        <?= htmlspecialchars($course) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="student-search-actions">
            <button
                type="submit"
                class="button button-primary"
            >
                Search / Filter
            </button>

            <?php if ($hasFilters): ?>
                <a
                    href="students.php"
                    class="button button-secondary"
                >
                    Clear
                </a>
            <?php endif; ?>
        </div>
    </form>

    <?php if ($hasFilters): ?>
        <p class="search-summary">
            <?= count($students) ?>
            matching
            <?= count($students) === 1 ? 'student' : 'students' ?>
            found.
        </p>
    <?php endif; ?>
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
                                <strong>
                                    <?= htmlspecialchars(
                                        $student['student_number']
                                    ) ?>
                                </strong>
                            </td>

                            <td>
                                <?= htmlspecialchars(
                                    $student['first_name']
                                    . ' '
                                    . $student['last_name']
                                ) ?>
                            </td>

                            <td>
                                <?php if (!empty($student['email'])): ?>
                                    <?= htmlspecialchars($student['email']) ?>
                                <?php else: ?>
                                    <span class="table-secondary">
                                        Not provided
                                    </span>
                                <?php endif; ?>
                            </td>

                            <td>
                                <?php if (!empty($student['course_programme'])): ?>
                                    <?= htmlspecialchars(
                                        $student['course_programme']
                                    ) ?>
                                <?php else: ?>
                                    <span class="table-secondary">
                                        Not provided
                                    </span>
                                <?php endif; ?>
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

    <?php elseif ($hasFilters): ?>
        <div class="no-results-state">
            <h3>No matching students</h3>

            <p>
                No student records match the current search or filter.
            </p>

            <a
                href="students.php"
                class="button button-secondary"
            >
                Clear Search and Filters
            </a>
        </div>

    <?php else: ?>
        <p class="placeholder-note">
            No students have been added yet.
        </p>
    <?php endif; ?>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
