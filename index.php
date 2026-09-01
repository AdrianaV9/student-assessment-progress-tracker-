<?php
require_once __DIR__ . '/config/database.php';

$pageTitle = 'Dashboard';

/*
 * These simple queries confirm that PHP can communicate with the
 * database created in SCRUM-15.
 */
$studentCount = (int) $pdo->query(
    'SELECT COUNT(*) FROM students'
)->fetchColumn();

$assessmentCount = (int) $pdo->query(
    'SELECT COUNT(*) FROM assessments'
)->fetchColumn();

$resultCount = (int) $pdo->query(
    'SELECT COUNT(*) FROM results'
)->fetchColumn();

require __DIR__ . '/includes/header.php';
?>

<section class="page-heading">
    <div>
        <p class="eyebrow">Dashboard</p>
        <h2>Project foundation</h2>
        <p>
            The PHP application is connected to the MySQL database.
            Functional features will be implemented in later Jira work items.
        </p>
    </div>
    <span class="status-badge">Database connected</span>
</section>

<section class="summary-grid" aria-label="Database summary">
    <article class="summary-card">
        <span>Total Students</span>
        <strong><?= $studentCount ?></strong>
    </article>

    <article class="summary-card">
        <span>Total Assessments</span>
        <strong><?= $assessmentCount ?></strong>
    </article>

    <article class="summary-card">
        <span>Results Recorded</span>
        <strong><?= $resultCount ?></strong>
    </article>

    <article class="summary-card">
        <span>Current Stage</span>
        <strong>Foundation</strong>
    </article>
</section>

<section class="panel">
    <h3>SCRUM-16 foundation check</h3>
    <p>
        If this page loads and displays the database counts above,
        the PHP-to-MySQL connection is working correctly.
    </p>

    <p>
        Student CRUD, assessment CRUD, results, calculations and
        progress tracking will be developed incrementally in the
        subsequent Jira stories.
    </p>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
