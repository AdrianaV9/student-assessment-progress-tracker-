<?php
require_once __DIR__ . '/config/database.php';

$pageTitle = 'Create Assessment';

$errors = [];

$title = '';
$moduleCode = '';
$moduleName = '';
$dueDate = '';
$maximumMark = '100';

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
             * Reuse an existing module if the module code already exists.
             * Otherwise create a new module for this assessment.
             */
            $moduleStatement = $pdo->prepare(
                'SELECT id, module_name
                 FROM modules
                 WHERE module_code = :module_code'
            );

            $moduleStatement->execute([
                'module_code' => $moduleCode
            ]);

            $existingModule = $moduleStatement->fetch();

            if ($existingModule) {
                $moduleId = (int) $existingModule['id'];
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

            $insertAssessment = $pdo->prepare(
                'INSERT INTO assessments (
                    module_id,
                    title,
                    due_date,
                    maximum_mark
                ) VALUES (
                    :module_id,
                    :title,
                    :due_date,
                    :maximum_mark
                )'
            );

            $insertAssessment->execute([
                'module_id' => $moduleId,
                'title' => $title,
                'due_date' => $dueDate,
                'maximum_mark' => (float) $maximumMark
            ]);

            $pdo->commit();

            header('Location: assessments.php?added=1');
            exit;

        } catch (PDOException $exception) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }

            $errors[] = 'The assessment could not be saved. Please try again.';
        }
    }
}

require __DIR__ . '/includes/header.php';
?>

<section class="page-heading">
    <div>
        <p class="eyebrow">Assessments</p>
        <h2>Create Assessment</h2>
        <p>
            Enter the assessment and module information below.
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
        action="add_assessment.php"
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
                placeholder="e.g. PFD200"
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
                placeholder="e.g. Professional Development"
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
                Save Assessment
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
