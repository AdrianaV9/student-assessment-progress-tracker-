<?php
require_once __DIR__ . '/config/database.php';

$pageTitle = 'Assessment Feedback';

$resultId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$resultId) {
    http_response_code(400);
    die('Invalid result ID.');
}

$statement = $pdo->prepare(
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
        m.module_name
     FROM results r
     JOIN students s
        ON r.student_id = s.id
     JOIN assessments a
        ON r.assessment_id = a.id
     JOIN modules m
        ON a.module_id = m.id
     WHERE r.id = :id'
);

$statement->execute([
    'id' => $resultId
]);

$result = $statement->fetch();

if (!$result) {
    http_response_code(404);
    die('Assessment result not found.');
}

$errors = [];
$feedback = $result['feedback'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $feedback = trim($_POST['feedback'] ?? '');

    if (strlen($feedback) > 5000) {
        $errors[] = 'Feedback must be 5000 characters or fewer.';
    }

    if (!$errors) {
        try {
            $updateStatement = $pdo->prepare(
                'UPDATE results
                 SET feedback = :feedback
                 WHERE id = :id'
            );

            $updateStatement->execute([
                'feedback' => $feedback !== '' ? $feedback : null,
                'id' => $resultId
            ]);

            header('Location: results.php?feedback_updated=1');
            exit;

        } catch (PDOException $exception) {
            $errors[] =
                'The feedback could not be saved. Please try again.';
        }
    }
}

$percentage = null;

if ((float) $result['maximum_mark'] > 0) {
    $percentage =
        ((float) $result['mark_achieved']
        / (float) $result['maximum_mark'])
        * 100;
}

require __DIR__ . '/includes/header.php';
?>

<section class="page-heading">
    <div>
        <p class="eyebrow">Results</p>
        <h2>Assessment Feedback</h2>
        <p>
            Add or update feedback for this student's assessment result.
        </p>
    </div>
</section>

<?php if ($errors): ?>
    <div class="alert alert-error" role="alert">
        <strong>Please correct the following:</strong>

        <ul>
            <?php foreach ($errors as $error): ?>
                <li><?= htmlspecialchars($error) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<section class="panel result-summary">
    <div class="details-grid">
        <div class="details-item">
            <dt>Student</dt>
            <dd>
                <?= htmlspecialchars(
                    $result['student_number']
                    . ' - '
                    . $result['first_name']
                    . ' '
                    . $result['last_name']
                ) ?>
            </dd>
        </div>

        <div class="details-item">
            <dt>Assessment</dt>
            <dd>
                <?= htmlspecialchars(
                    $result['module_code']
                    . ' - '
                    . $result['assessment_title']
                ) ?>
            </dd>
        </div>

        <div class="details-item">
            <dt>Mark</dt>
            <dd>
                <?= htmlspecialchars(
                    number_format(
                        (float) $result['mark_achieved'],
                        2
                    )
                ) ?>
                /
                <?= htmlspecialchars(
                    number_format(
                        (float) $result['maximum_mark'],
                        2
                    )
                ) ?>
            </dd>
        </div>

        <div class="details-item">
            <dt>Percentage</dt>
            <dd>
                <?= $percentage !== null
                    ? htmlspecialchars(number_format($percentage, 2)) . '%'
                    : 'Not available' ?>
            </dd>
        </div>
    </div>
</section>

<section class="panel">
    <form
        method="post"
        action="edit_feedback.php?id=<?= (int) $resultId ?>"
    >
        <div class="form-group">
            <label for="feedback">Feedback</label>

            <textarea
                id="feedback"
                name="feedback"
                rows="8"
                maxlength="5000"
                placeholder="Enter assessment feedback..."
            ><?= htmlspecialchars($feedback) ?></textarea>

            <small class="form-help">
                Feedback is optional and can be updated later.
            </small>
        </div>

        <div class="form-actions feedback-actions">
            <button
                type="submit"
                class="button button-primary"
            >
                Save Feedback
            </button>

            <a
                href="results.php"
                class="button button-secondary"
            >
                Cancel
            </a>
        </div>
    </form>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
