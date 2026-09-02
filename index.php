<?php
require_once __DIR__ . '/config/database.php';

$pageTitle = 'Dashboard';

$totalStudents = (int) $pdo->query('SELECT COUNT(*) FROM students')->fetchColumn();
$totalAssessments = (int) $pdo->query('SELECT COUNT(*) FROM assessments')->fetchColumn();
$totalResults = (int) $pdo->query('SELECT COUNT(*) FROM results')->fetchColumn();

$averageResultPercentage = $pdo->query(
    'SELECT ROUND(AVG(CASE WHEN a.maximum_mark > 0
        THEN (r.mark_achieved / a.maximum_mark) * 100
        ELSE NULL END), 2)
     FROM results r
     JOIN assessments a ON r.assessment_id = a.id'
)->fetchColumn();

$studentStatement = $pdo->query(
    'SELECT
        s.id,
        s.student_number,
        s.first_name,
        s.last_name,
        COUNT(r.id) AS result_count,
        ROUND(
            AVG(
                CASE
                    WHEN a.maximum_mark > 0
                    THEN (r.mark_achieved / a.maximum_mark) * 100
                    ELSE NULL
                END
            ),
            2
        ) AS overall_progress
     FROM students s
     LEFT JOIN results r ON r.student_id = s.id
     LEFT JOIN assessments a ON r.assessment_id = a.id
     GROUP BY
        s.id,
        s.student_number,
        s.first_name,
        s.last_name
     ORDER BY s.last_name, s.first_name'
);

$studentProgress = $studentStatement->fetchAll();

$assessmentStatement = $pdo->query(
    'SELECT
        a.id,
        a.title,
        a.due_date,
        m.module_code,
        m.module_name,
        COUNT(r.id) AS recorded_results
     FROM assessments a
     JOIN modules m ON a.module_id = m.id
     LEFT JOIN results r ON r.assessment_id = a.id
     GROUP BY
        a.id,
        a.title,
        a.due_date,
        m.module_code,
        m.module_name
     ORDER BY
        CASE WHEN a.due_date IS NULL THEN 1 ELSE 0 END,
        a.due_date,
        m.module_code,
        a.title'
);

$assessmentStatus = $assessmentStatement->fetchAll();
$today = new DateTimeImmutable('today');

function getDueStatus(?string $dueDate, DateTimeImmutable $today): array
{
    if (!$dueDate) {
        return ['label' => 'No due date', 'class' => 'status-neutral'];
    }

    $due = DateTimeImmutable::createFromFormat('Y-m-d', $dueDate);

    if (!$due) {
        return ['label' => 'Invalid date', 'class' => 'status-neutral'];
    }

    if ($due < $today) {
        return ['label' => 'Overdue', 'class' => 'status-overdue'];
    }

    if ($due == $today) {
        return ['label' => 'Due today', 'class' => 'status-today'];
    }

    return ['label' => 'Upcoming', 'class' => 'status-upcoming'];
}

require __DIR__ . '/includes/header.php';
?>

<section class="page-heading">
    <div>
        <p class="eyebrow">Dashboard</p>
        <h2>Student Assessment Progress Dashboard</h2>
        <p>Review current student progress, recorded results and assessment due status.</p>
    </div>
</section>

<section class="dashboard-cards" aria-label="System summary">
    <article class="dashboard-card">
        <span class="dashboard-card-label">Students</span>
        <strong class="dashboard-card-value"><?= $totalStudents ?></strong>
        <a href="students.php">View students</a>
    </article>

    <article class="dashboard-card">
        <span class="dashboard-card-label">Assessments</span>
        <strong class="dashboard-card-value"><?= $totalAssessments ?></strong>
        <a href="assessments.php">View assessments</a>
    </article>

    <article class="dashboard-card">
        <span class="dashboard-card-label">Recorded Results</span>
        <strong class="dashboard-card-value"><?= $totalResults ?></strong>
        <a href="results.php">View results</a>
    </article>

    <article class="dashboard-card">
        <span class="dashboard-card-label">Average Recorded Performance</span>
        <strong class="dashboard-card-value">
            <?php if ($averageResultPercentage !== null): ?>
                <?= htmlspecialchars(number_format((float) $averageResultPercentage, 2)) ?>%
            <?php else: ?>
                —
            <?php endif; ?>
        </strong>
        <a href="progress.php">View progress</a>
    </article>
</section>

<section class="panel dashboard-section">
    <div class="section-heading">
        <div>
            <h3>Student Progress Overview</h3>
            <p>Overall progress is the average of each student's recorded assessment percentages.</p>
        </div>
        <a href="progress.php" class="button button-secondary button-small">Full Progress View</a>
    </div>

    <?php if ($studentProgress): ?>
        <div class="table-wrapper">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Student</th>
                        <th>Recorded Results</th>
                        <th>Overall Progress</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($studentProgress as $student): ?>
                        <tr>
                            <td>
                                <strong><?= htmlspecialchars($student['student_number']) ?></strong><br>
                                <span class="table-secondary">
                                    <?= htmlspecialchars($student['first_name'] . ' ' . $student['last_name']) ?>
                                </span>
                            </td>
                            <td><?= (int) $student['result_count'] ?></td>
                            <td>
                                <?php if ($student['overall_progress'] !== null): ?>
                                    <?php
                                    $progressValue = max(
                                        0,
                                        min(100, (float) $student['overall_progress'])
                                    );
                                    ?>
                                    <div class="dashboard-progress">
                                        <strong>
                                            <?= htmlspecialchars(number_format((float) $student['overall_progress'], 2)) ?>%
                                        </strong>
                                        <progress
                                            value="<?= htmlspecialchars(number_format($progressValue, 2, '.', '')) ?>"
                                            max="100"
                                        >
                                            <?= htmlspecialchars(number_format($progressValue, 2)) ?>%
                                        </progress>
                                    </div>
                                <?php else: ?>
                                    <span class="table-secondary">No results recorded</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <p class="placeholder-note">No students have been added yet.</p>
    <?php endif; ?>
</section>

<section class="panel dashboard-section">
    <div class="section-heading">
        <div>
            <h3>Assessment Status</h3>
            <p>Status below refers specifically to the assessment due date.</p>
        </div>
        <a href="assessments.php" class="button button-secondary button-small">Manage Assessments</a>
    </div>

    <?php if ($assessmentStatus): ?>
        <div class="table-wrapper">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Module</th>
                        <th>Assessment</th>
                        <th>Due Date</th>
                        <th>Due Status</th>
                        <th>Recorded Results</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($assessmentStatus as $assessment): ?>
                        <?php $status = getDueStatus($assessment['due_date'], $today); ?>
                        <tr>
                            <td>
                                <strong><?= htmlspecialchars($assessment['module_code']) ?></strong><br>
                                <span class="table-secondary">
                                    <?= htmlspecialchars($assessment['module_name']) ?>
                                </span>
                            </td>
                            <td><?= htmlspecialchars($assessment['title']) ?></td>
                            <td>
                                <?php if ($assessment['due_date']): ?>
                                    <?= htmlspecialchars($assessment['due_date']) ?>
                                <?php else: ?>
                                    <span class="table-secondary">Not provided</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="status-badge <?= htmlspecialchars($status['class']) ?>">
                                    <?= htmlspecialchars($status['label']) ?>
                                </span>
                            </td>
                            <td><?= (int) $assessment['recorded_results'] ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <p class="placeholder-note">No assessments have been created yet.</p>
    <?php endif; ?>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
