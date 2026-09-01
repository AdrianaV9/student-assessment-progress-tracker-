<?php
require_once __DIR__ . '/config/database.php';

$pageTitle = 'Results';

$statement = $pdo->query(
    'SELECT
        r.id,
        r.mark_achieved,
        s.student_number,
        s.first_name,
        s.last_name,
        a.title AS assessment_title,
        a.maximum_mark,
        m.module_code
     FROM results r
     JOIN students s
        ON r.student_id = s.id
     JOIN assessments a
        ON r.assessment_id = a.id
     JOIN modules m
        ON a.module_id = m.id
     ORDER BY
        s.last_name,
        s.first_name,
        m.module_code,
        a.title'
);

$results = $statement->fetchAll();

require __DIR__ . '/includes/header.php';
?>

<section class="page-heading">
    <div>
        <p class="eyebrow">Results</p>
        <h2>Assessment Results</h2>
        <p>
            View marks currently recorded for students.
        </p>
    </div>

    <a
        href="add_result.php"
        class="button button-primary"
    >
        + Record Mark
    </a>
</section>

<?php if (isset($_GET['added'])): ?>
    <div class="alert alert-success" role="status">
        Assessment mark recorded successfully.
    </div>
<?php endif; ?>

<section class="panel">
    <?php if ($results): ?>
        <div class="table-wrapper">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Student</th>
                        <th>Module</th>
                        <th>Assessment</th>
                        <th>Mark Achieved</th>
                        <th>Maximum Mark</th>
                    </tr>
                </thead>

                <tbody>
                    <?php foreach ($results as $result): ?>
                        <tr>
                            <td>
                                <strong>
                                    <?= htmlspecialchars(
                                        $result['student_number']
                                    ) ?>
                                </strong>

                                <br>

                                <span class="table-secondary">
                                    <?= htmlspecialchars(
                                        $result['first_name']
                                        . ' '
                                        . $result['last_name']
                                    ) ?>
                                </span>
                            </td>

                            <td>
                                <?= htmlspecialchars(
                                    $result['module_code']
                                ) ?>
                            </td>

                            <td>
                                <?= htmlspecialchars(
                                    $result['assessment_title']
                                ) ?>
                            </td>

                            <td>
                                <?= htmlspecialchars(
                                    number_format(
                                        (float) $result['mark_achieved'],
                                        2
                                    )
                                ) ?>
                            </td>

                            <td>
                                <?= htmlspecialchars(
                                    number_format(
                                        (float) $result['maximum_mark'],
                                        2
                                    )
                                ) ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

    <?php else: ?>
        <p class="placeholder-note">
            No assessment marks have been recorded yet.
        </p>
    <?php endif; ?>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
