<?php
require_once __DIR__ . '/config/database.php';

$pageTitle = 'Results';

$statement = $pdo->query(
    'SELECT
        r.id,
        r.mark_achieved,
        r.feedback,
        s.student_number,
        s.first_name,
        s.last_name,
        a.title AS assessment_title,
        a.maximum_mark,
        m.module_code,
        ROUND(
            (r.mark_achieved / NULLIF(a.maximum_mark, 0)) * 100,
            2
        ) AS percentage
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
            View marks, percentages and assessment feedback.
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

<?php if (isset($_GET['feedback_updated'])): ?>
    <div class="alert alert-success" role="status">
        Assessment feedback saved successfully.
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
                        <th>Mark</th>
                        <th>Percentage</th>
                        <th>Feedback</th>
                        <th>Action</th>
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
                                    . ' / '
                                    . number_format(
                                        (float) $result['maximum_mark'],
                                        2
                                    )
                                ) ?>
                            </td>

                            <td>
                                <?php if ($result['percentage'] !== null): ?>
                                    <strong class="percentage-value">
                                        <?= htmlspecialchars(
                                            number_format(
                                                (float) $result['percentage'],
                                                2
                                            )
                                        ) ?>%
                                    </strong>
                                <?php else: ?>
                                    <span class="table-secondary">
                                        Not available
                                    </span>
                                <?php endif; ?>
                            </td>

                            <td class="feedback-cell">
                                <?php if (!empty($result['feedback'])): ?>
                                    <?= nl2br(
                                        htmlspecialchars(
                                            $result['feedback']
                                        )
                                    ) ?>
                                <?php else: ?>
                                    <span class="table-secondary">
                                        No feedback
                                    </span>
                                <?php endif; ?>
                            </td>

                            <td>
                                <a
                                    href="edit_feedback.php?id=<?= (int) $result['id'] ?>"
                                    class="button button-small button-secondary"
                                >
                                    <?= !empty($result['feedback'])
                                        ? 'Edit Feedback'
                                        : 'Add Feedback' ?>
                                </a>
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
