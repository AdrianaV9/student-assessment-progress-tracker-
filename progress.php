<?php
require_once __DIR__ . '/config/database.php';

$pageTitle = 'Student Progress';

/*
 * Overall progress is the arithmetic mean of the student's
 * recorded assessment percentages.
 *
 * Example:
 * Assessment 1 = 80%
 * Assessment 2 = 60%
 * Overall progress = (80 + 60) / 2 = 70%
 *
 * Students with no recorded results return NULL rather than 0%.
 */
$statement = $pdo->query(
    'SELECT
        s.id,
        s.student_number,
        s.first_name,
        s.last_name,
        s.course_programme,
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
     LEFT JOIN results r
        ON r.student_id = s.id
     LEFT JOIN assessments a
        ON r.assessment_id = a.id
     GROUP BY
        s.id,
        s.student_number,
        s.first_name,
        s.last_name,
        s.course_programme
     ORDER BY
        s.last_name,
        s.first_name'
);

$students = $statement->fetchAll();

require __DIR__ . '/includes/header.php';
?>

<section class="page-heading">
    <div>
        <p class="eyebrow">Progress</p>
        <h2>Overall Student Progress</h2>
        <p>
            Overall progress is calculated from each student's
            recorded assessment percentages.
        </p>
    </div>
</section>

<section class="panel progress-explanation">
    <h3>How overall progress is calculated</h3>

    <p>
        The system calculates the percentage for every recorded
        assessment result and then calculates the average of those
        percentages for each student.
    </p>

    <p class="calculation-example">
        Example: 80% + 60% = 140 ÷ 2 assessments = <strong>70%</strong>
    </p>
</section>

<section class="panel">
    <?php if ($students): ?>
        <div class="table-wrapper">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Student</th>
                        <th>Course / Programme</th>
                        <th>Recorded Results</th>
                        <th>Overall Progress</th>
                    </tr>
                </thead>

                <tbody>
                    <?php foreach ($students as $student): ?>
                        <tr>
                            <td>
                                <strong>
                                    <?= htmlspecialchars(
                                        $student['student_number']
                                    ) ?>
                                </strong>

                                <br>

                                <span class="table-secondary">
                                    <?= htmlspecialchars(
                                        $student['first_name']
                                        . ' '
                                        . $student['last_name']
                                    ) ?>
                                </span>
                            </td>

                            <td>
                                <?php if (!empty($student['course_programme'])): ?>
                                    <?= htmlspecialchars(
                                        $student['course_programme']
                                    ) ?>
                                <?php else: ?>
                                    <span class="table-secondary">
                                        Not provided
                                    </span>
                                <?php endif; ?>
                            </td>

                            <td>
                                <?= (int) $student['result_count'] ?>
                            </td>

                            <td>
                                <?php if ($student['overall_progress'] !== null): ?>
                                    <?php
                                    $progressValue =
                                        max(
                                            0,
                                            min(
                                                100,
                                                (float) $student['overall_progress']
                                            )
                                        );
                                    ?>

                                    <div class="progress-result">
                                        <strong>
                                            <?= htmlspecialchars(
                                                number_format(
                                                    (float) $student['overall_progress'],
                                                    2
                                                )
                                            ) ?>%
                                        </strong>

                                        <progress
                                            value="<?= htmlspecialchars(
                                                number_format(
                                                    $progressValue,
                                                    2,
                                                    '.',
                                                    ''
                                                )
                                            ) ?>"
                                            max="100"
                                            aria-label="Overall progress"
                                        >
                                            <?= htmlspecialchars(
                                                number_format(
                                                    $progressValue,
                                                    2
                                                )
                                            ) ?>%
                                        </progress>
                                    </div>

                                <?php else: ?>
                                    <span class="progress-empty">
                                        No results recorded
                                    </span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

    <?php else: ?>
        <p class="placeholder-note">
            No students have been added yet.
        </p>
    <?php endif; ?>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
