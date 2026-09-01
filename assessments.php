<?php
require_once __DIR__ . '/config/database.php';

$pageTitle = 'Assessments';

$statement = $pdo->query(
    'SELECT
        a.id,
        a.title,
        a.due_date,
        a.maximum_mark,
        m.module_code,
        m.module_name
     FROM assessments a
     JOIN modules m
        ON a.module_id = m.id
     ORDER BY a.due_date, a.title'
);

$assessments = $statement->fetchAll();

require __DIR__ . '/includes/header.php';
?>

<section class="page-heading">
    <div>
        <p class="eyebrow">Assessments</p>
        <h2>Assessment Management</h2>
        <p>
            View and manage assessments currently stored in the system.
        </p>
    </div>

    <a
        href="add_assessment.php"
        class="button button-primary"
    >
        + Create Assessment
    </a>
</section>

<?php if (isset($_GET['added'])): ?>
    <div class="alert alert-success" role="status">
        Assessment created successfully.
    </div>
<?php endif; ?>

<?php if (isset($_GET['updated'])): ?>
    <div class="alert alert-success" role="status">
        Assessment updated successfully.
    </div>
<?php endif; ?>

<?php if (isset($_GET['deleted'])): ?>
    <div class="alert alert-success" role="status">
        Assessment deleted successfully.
    </div>
<?php endif; ?>

<section class="panel">
    <?php if ($assessments): ?>
        <div class="table-wrapper">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Assessment</th>
                        <th>Module</th>
                        <th>Due Date</th>
                        <th>Maximum Mark</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>
                    <?php foreach ($assessments as $assessment): ?>
                        <tr>
                            <td>
                                <?= htmlspecialchars(
                                    $assessment['title']
                                ) ?>
                            </td>

                            <td>
                                <strong>
                                    <?= htmlspecialchars(
                                        $assessment['module_code']
                                    ) ?>
                                </strong>

                                <br>

                                <span class="table-secondary">
                                    <?= htmlspecialchars(
                                        $assessment['module_name']
                                    ) ?>
                                </span>
                            </td>

                            <td>
                                <?= htmlspecialchars(
                                    $assessment['due_date']
                                ) ?>
                            </td>

                            <td>
                                <?= htmlspecialchars(
                                    number_format(
                                        (float) $assessment['maximum_mark'],
                                        2
                                    )
                                ) ?>
                            </td>

                            <td>
                                <div class="table-actions">
                                    <a
                                        href="edit_assessment.php?id=<?= (int) $assessment['id'] ?>"
                                        class="button button-small button-primary"
                                    >
                                        Edit
                                    </a>

                                    <a
                                        href="delete_assessment.php?id=<?= (int) $assessment['id'] ?>"
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
            No assessments have been created yet.
        </p>
    <?php endif; ?>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
