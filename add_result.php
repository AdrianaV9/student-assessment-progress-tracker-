<?php
require_once __DIR__ . '/config/database.php';

$pageTitle = 'Record Assessment Mark';

$errors = [];

$studentId = '';
$assessmentId = '';
$markAchieved = '';

$students = $pdo->query(
    'SELECT
        id,
        student_number,
        first_name,
        last_name
     FROM students
     ORDER BY last_name, first_name'
)->fetchAll();

$assessments = $pdo->query(
    'SELECT
        a.id,
        a.title,
        a.maximum_mark,
        m.module_code
     FROM assessments a
     JOIN modules m
        ON a.module_id = m.id
     ORDER BY m.module_code, a.title'
)->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $studentId = trim($_POST['student_id'] ?? '');
    $assessmentId = trim($_POST['assessment_id'] ?? '');
    $markAchieved = trim($_POST['mark_achieved'] ?? '');

    if (
        $studentId === ''
        || !ctype_digit($studentId)
    ) {
        $errors[] = 'Select a valid student.';
    }

    if (
        $assessmentId === ''
        || !ctype_digit($assessmentId)
    ) {
        $errors[] = 'Select a valid assessment.';
    }

    if ($markAchieved === '') {
        $errors[] = 'Mark is required.';
    } elseif (!is_numeric($markAchieved)) {
        $errors[] = 'Mark must be a number.';
    }

    $student = null;
    $assessment = null;

    if (!$errors) {
        $studentStatement = $pdo->prepare(
            'SELECT id
             FROM students
             WHERE id = :id'
        );

        $studentStatement->execute([
            'id' => (int) $studentId
        ]);

        $student = $studentStatement->fetch();

        if (!$student) {
            $errors[] = 'The selected student does not exist.';
        }

        $assessmentStatement = $pdo->prepare(
            'SELECT
                id,
                maximum_mark
             FROM assessments
             WHERE id = :id'
        );

        $assessmentStatement->execute([
            'id' => (int) $assessmentId
        ]);

        $assessment = $assessmentStatement->fetch();

        if (!$assessment) {
            $errors[] = 'The selected assessment does not exist.';
        }
    }

    if (
        !$errors
        && $assessment
        && is_numeric($markAchieved)
    ) {
        $markValue = (float) $markAchieved;
        $maximumMark = (float) $assessment['maximum_mark'];

        if ($markValue < 0) {
            $errors[] = 'Mark cannot be below zero.';
        }

        if ($markValue > $maximumMark) {
            $errors[] =
                'Mark cannot be greater than the assessment maximum mark of '
                . number_format($maximumMark, 2)
                . '.';
        }
    }

    if (!$errors) {
        $duplicateStatement = $pdo->prepare(
            'SELECT COUNT(*)
             FROM results
             WHERE student_id = :student_id
               AND assessment_id = :assessment_id'
        );

        $duplicateStatement->execute([
            'student_id' => (int) $studentId,
            'assessment_id' => (int) $assessmentId
        ]);

        if ((int) $duplicateStatement->fetchColumn() > 0) {
            $errors[] =
                'A mark has already been recorded for this student and assessment.';
        }
    }

    if (!$errors) {
        try {
            $insertStatement = $pdo->prepare(
                'INSERT INTO results (
                    student_id,
                    assessment_id,
                    mark_achieved
                ) VALUES (
                    :student_id,
                    :assessment_id,
                    :mark_achieved
                )'
            );

            $insertStatement->execute([
                'student_id' => (int) $studentId,
                'assessment_id' => (int) $assessmentId,
                'mark_achieved' => (float) $markAchieved
            ]);

            header('Location: results.php?added=1');
            exit;

        } catch (PDOException $exception) {
            $errors[] =
                'The assessment mark could not be saved. Please try again.';
        }
    }
}

require __DIR__ . '/includes/header.php';
?>

<section class="page-heading">
    <div>
        <p class="eyebrow">Results</p>
        <h2>Record Assessment Mark</h2>
        <p>
            Select a student and assessment, then enter the mark achieved.
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

<?php if (!$students): ?>
    <div class="alert alert-error">
        No students are available. Add a student before recording marks.
    </div>
<?php endif; ?>

<?php if (!$assessments): ?>
    <div class="alert alert-error">
        No assessments are available. Create an assessment before recording marks.
    </div>
<?php endif; ?>

<section class="panel">
    <form
        method="post"
        action="add_result.php"
        class="form-grid"
        novalidate
    >
        <div class="form-group">
            <label for="student_id">Student *</label>

            <select
                id="student_id"
                name="student_id"
                required
            >
                <option value="">Select student</option>

                <?php foreach ($students as $student): ?>
                    <option
                        value="<?= (int) $student['id'] ?>"
                        <?= (string) $studentId === (string) $student['id']
                            ? 'selected'
                            : '' ?>
                    >
                        <?= htmlspecialchars(
                            $student['student_number']
                            . ' - '
                            . $student['first_name']
                            . ' '
                            . $student['last_name']
                        ) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="assessment_id">Assessment *</label>

            <select
                id="assessment_id"
                name="assessment_id"
                required
            >
                <option value="">Select assessment</option>

                <?php foreach ($assessments as $assessment): ?>
                    <option
                        value="<?= (int) $assessment['id'] ?>"
                        <?= (string) $assessmentId === (string) $assessment['id']
                            ? 'selected'
                            : '' ?>
                    >
                        <?= htmlspecialchars(
                            $assessment['module_code']
                            . ' - '
                            . $assessment['title']
                            . ' (max '
                            . number_format(
                                (float) $assessment['maximum_mark'],
                                2
                            )
                            . ')'
                        ) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="mark_achieved">Mark Achieved *</label>

            <input
                type="number"
                id="mark_achieved"
                name="mark_achieved"
                min="0"
                step="0.01"
                required
                value="<?= htmlspecialchars($markAchieved) ?>"
            >
        </div>

        <div class="form-actions form-group-full">
            <button
                type="submit"
                class="button button-primary"
                <?= (!$students || !$assessments) ? 'disabled' : '' ?>
            >
                Save Mark
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
