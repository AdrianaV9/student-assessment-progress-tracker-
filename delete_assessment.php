<?php
require_once __DIR__ . '/config/database.php';

$pageTitle = 'Delete Assessment';

$assessmentId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$assessmentId) {
    http_response_code(400);
    die('Invalid assessment ID.');
}

$statement = $pdo->prepare(
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
     WHERE a.id = :id'
);

$statement->execute([
    'id' => $assessmentId
]);

$assessment = $statement->fetch();

if (!$assessment) {
    http_response_code(404);
    die('Assessment record not found.');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $postedId = filter_input(
        INPUT_POST,
        'assessment_id',
        FILTER_VALIDATE_INT
    );

    if (!$postedId || $postedId !== $assessmentId) {
        http_response_code(400);
        die('Invalid deletion request.');
    }

    try {
        $deleteStatement = $pdo->prepare(
            'DELETE FROM assessments
             WHERE id = :id'
        );

        $deleteStatement->execute([
            'id' => $assessmentId
        ]);

        header('Location: assessments.php?deleted=1');
        exit;

    } catch (PDOException $exception) {
        $errorMessage =
            'The assessment could not be deleted. Please try again.';
    }
}

require __DIR__ . '/includes/header.php';
?>

<section class="page-heading">
    <div>
        <p class="eyebrow">Assessments</p>
        <h2>Delete Assessment</h2>
        <p>
            Confirm that you want to permanently delete this assessment.
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

        <p>You are about to delete:</p>

        <p class="record-delete-name">
            <?= htmlspecialchars($assessment['title']) ?>
        </p>

        <p>
            Module:
            <strong>
                <?= htmlspecialchars($assessment['module_code']) ?>
            </strong>
            —
            <?= htmlspecialchars($assessment['module_name']) ?>
        </p>

        <p>
            This action cannot be undone.
            If results are later recorded against this assessment,
            those linked result records will also be removed because
            of the database relationship.
        </p>
    </div>

    <form
        method="post"
        action="delete_assessment.php?id=<?= (int) $assessmentId ?>"
        class="delete-actions"
    >
        <input
            type="hidden"
            name="assessment_id"
            value="<?= (int) $assessmentId ?>"
        >

        <button
            type="submit"
            class="button button-danger"
        >
            Yes, Delete Assessment
        </button>

        <a
            href="assessments.php"
            class="button button-secondary"
        >
            Cancel
        </a>
    </form>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
