# SCRUM-26 Test Evidence – Create Progress Dashboard

## Story

As a user, I want a dashboard showing assessment progress and status so that student performance can be understood quickly.

## Acceptance criteria

The dashboard displays key student progress information.

Assessment status is visible.

Overall progress is presented clearly.

Displayed data matches stored assessment results.

## Test 1 – Summary counts

Compare the Students, Assessments and Recorded Results dashboard cards with the corresponding application pages or phpMyAdmin.

Expected result: the dashboard totals match the stored records.

## Test 2 – Student progress

Use a student with known percentages, for example 80% and 60%.

Expected dashboard overall progress: 70.00%.

Expected result: the dashboard value matches `progress.php`.

## Test 3 – Student with no results

Expected result: Recorded Results is 0 and Overall Progress displays `No results recorded`.

## Test 4 – Upcoming assessment

Use an assessment with a future due date.

Expected status: `Upcoming`.

## Test 5 – Overdue assessment

Create a temporary assessment with a due date earlier than today.

Expected status: `Overdue`.

## Test 6 – Due today

Create or edit a temporary assessment so the due date is today.

Expected status: `Due today`.

## Test 7 – Dynamic update

Record another mark and return to `index.php`.

Expected result: Recorded Results, average recorded performance and relevant student progress update from the current database data.

## Test 8 – Navigation

Test the links to Students, Assessments, Results and Progress.

Expected result: each link opens the correct page.

## Scope note

Assessment status means due-date status because the current database does not store submission status.

## Evidence to retain

Capture the complete dashboard, a student progress row, at least one assessment due-status row, and optionally a before/after dashboard update after adding a new result.
