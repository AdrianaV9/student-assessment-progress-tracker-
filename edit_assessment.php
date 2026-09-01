<?php
require_once __DIR__ . '/config/database.php';

$pageTitle = 'Edit Assessment';

$assessmentId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$assessmentId) {
    http_response_code(400);
    die('Invalid assessment ID.');
}

$loadStatement = $pdo->prepare(
    'SELECT
        a.id,
        a.title,
        a.due_date,
        a.maximum_mark,
        a.module_id,
        m.module_code,
        m.module_name
     FROM assessments a
     JOIN modules m
        ON a.module_id = m.id
     WHERE a.id = :id'
);

$loadStatement->execute([
    'id' => $assessmentId
]);

$assessment = $loadStatement->fetch();

if (!$assessment) {
    http_response_code(404);
    die('Assessment record not found.');
}

$errors = [];

$title = $assessment['title'];
$moduleCode = $assessment['module_code'];
$moduleName = $assessment['module_name'];
$dueDate = $assessment['due_date'];
$maximumMark = $assessment['maximum_mark'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $moduleCode = strtoupper(trim($_POST['module_code'] ?? ''));
    $moduleName = trim($_POST['module_name'] ?? '');
    $dueDate = trim($_POST['due_date'] ?? '');
    $maximumMark = trim($_POST['maximum_mark'] ?? '');

    if ($title === '') {
        $errors[] = 'Assessment title is required.';
    }

    if ($moduleCode === '') {
        $errors[] = 'Module code is required.';
    }

    if ($moduleName === '') {
        $errors[] = 'Module name is required.';
    }

    if ($dueDate === '') {
        $errors[] = 'Due date is required.';
    }

    if ($maximumMark === '') {
        $errors[] = 'Maximum mark is required.';
    }

    if ($title !== '' && strlen($title) > 200) {
        $errors[] = 'Assessment title must be 200 characters or fewer.';
    }

    if ($moduleCode !== '' && strlen($moduleCode) > 30) {
        $errors[] = 'Module code must be 30 characters or fewer.';
    }

    if ($moduleName !== '' && strlen($moduleName) > 150) {
        $errors[] = 'Module name must be 150 characters or fewer.';
    }

    if ($dueDate !== '') {
        $dateObject = DateTime::createFromFormat('Y-m-d', $dueDate);

        if (
            !$dateObject
            || $dateObject->format('Y-m-d') !== $dueDate
        ) {
            $errors[] = 'Enter a valid due date.';
        }
    }

    if (
        $maximumMark !== ''
        && (
            !is_numeric($maximumMark)
            || (float) $maximumMark <= 0
        )
    ) {
        $errors[] = 'Maximum mark must be greater than zero.';
    }

    if (!$errors) {
        try {
            $pdo->beginTransaction();

            /*
             * Module codes are unique.
             * If the code exists, reuse that module.
             * If it does not exist, create it.
             */
            $moduleStatement = $pdo->prepare(
                'SELECT id
                 FROM modules
                 WHERE module_code = :module_code'
            );

            $moduleStatement->execute([
                'module_code' => $moduleCode
            ]);

            $existingModule = $moduleStatement->fetch();

            if ($existingModule) {
                $moduleId = (int) $existingModule['id'];

                /*
                 * Keep the module name aligned with the code.
                 * Because modules are shared records, changing the name
                 * updates it everywhere that module is used.
                 */
                $updateModule = $pdo->prepare(
                    'UPDATE modules
                     SET module_name = :module_name
                     WHERE id = :id'
                );

                $updateModule->execute([
                    'module_name' => $moduleName,
                    'id' => $moduleId
                ]);

            } else {
                $insertModule = $pdo->prepare(
                    'INSERT INTO modules (
                        module_code,
                        module_name
                    ) VALUES (
                        :module_code,
                        :module_name
                    )'
                );

                $insertModule->execute([
                    'module_code' => $moduleCode,
                    'module_name' => $moduleName
                ]);

                $moduleId = (int) $pdo->lastInsertId();
            }

            $updateAssessment = $pdo->prepare(
                'UPDATE assessments
                 SET
                    module_id = :module_id,
                    title = :title,
                    due_date = :due_date,
                    maximum_mark = :maximum_mark
                 WHERE id = :id'
            );

            $updateAssessment->execute([
                'module_id' => $moduleId,
                'title' => $title,
                'due_date' => $dueDate,
                'maximum_mark' => (float) $maximumMark,
                'id' => $assessmentId
            ]);

            $pdo->commit();

            header('Location: assessments.php?updated=1');
            exit;

        } catch (PDOException $exception) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }

            $errors[] = 'The assessment could not be updated. Please try again.';
        }
    }
}

require __DIR__ . '/includes/header.php';
?>

<section class="page-heading">
    <div>
        <p class="eyebrow">Assessments</p>
        <h2>Edit Assessment</h2>
        <p>
            Update the stored assessment details.
            Fields marked with * are required.
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

<section class="panel">
    <form
        method="post"
        action="edit_assessment.php?id=<?= (int) $assessmentId ?>"
        class="form-grid"
        novalidate
    >
        <div class="form-group form-group-full">
            <label for="title">Assessment Title *</label>

            <input
                type="text"
                id="title"
                name="title"
                maxlength="200"
                required
                value="<?= htmlspecialchars($title) ?>"
            >
        </div>

        <div class="form-group">
            <label for="module_code">Module Code *</label>

            <input
                type="text"
                id="module_code"
                name="module_code"
                maxlength="30"
                required
                value="<?= htmlspecialchars($moduleCode) ?>"
            >
        </div>

        <div class="form-group">
            <label for="module_name">Module Name *</label>

            <input
                type="text"
                id="module_name"
                name="module_name"
                maxlength="150"
                required
                value="<?= htmlspecialchars($moduleName) ?>"
            >
        </div>

        <div class="form-group">
            <label for="due_date">Due Date *</label>

            <input
                type="date"
                id="due_date"
                name="due_date"
                required
                value="<?= htmlspecialchars($dueDate) ?>"
            >
        </div>

        <div class="form-group">
            <label for="maximum_mark">Maximum Mark *</label>

            <input
                type="number"
                id="maximum_mark"
                name="maximum_mark"
                min="0.01"
                step="0.01"
                required
                value="<?= htmlspecialchars($maximumMark) ?>"
            >
        </div>

        <div class="form-actions form-group-full">
            <button
                type="submit"
                class="button button-primary"
            >
                Save Changes
            </button>

            <a
                href="assessments.php"
                class="button button-secondary"
            >
                Cancel
            </a>
        </div>
    </form>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
