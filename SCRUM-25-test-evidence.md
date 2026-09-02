# SCRUM-25 Test Evidence – Calculate Overall Student Progress

## Story

As a user, I want the system to calculate overall student progress so that performance across recorded assessments can be monitored.

## Acceptance criteria

Overall progress is calculated from available assessment results.

The calculation updates when new marks are added or changed.

The displayed result is accurate.

Students without sufficient results are handled appropriately.

## Calculation used

For the MVP, overall progress is defined as:

`average of all recorded assessment percentages for the student`

For example:

Assessment 1 = `80%`

Assessment 2 = `60%`

Overall progress:

`(80 + 60) / 2 = 70%`

The value is calculated dynamically from the `results` and `assessments` tables. It is not stored as a separate database value.

## Test 1 – One recorded result

Use a student with one result:

Mark achieved: `72`

Maximum mark: `100`

Expected overall progress:

`72.00%`

## Test 2 – Two assessment results

For the same student, use:

Assessment 1: `80 / 100 = 80%`

Assessment 2: `30 / 50 = 60%`

Expected overall progress:

`(80 + 60) / 2 = 70.00%`

## Test 3 – Different maximum marks

Use:

Assessment 1: `60 / 80 = 75%`

Assessment 2: `40 / 50 = 80%`

Expected overall progress:

`(75 + 80) / 2 = 77.50%`

This confirms that the calculation averages percentages rather than raw marks.

## Test 4 – New result updates progress

Record another valid assessment mark for the same student using the existing Record Mark page.

Return to `progress.php`.

Expected result: the number of recorded results increases and the overall percentage is recalculated automatically.

## Test 5 – Changed source value

If an existing mark is changed during development/testing, reload `progress.php`.

Expected result: the overall progress recalculates from the current database values without requiring a separate progress update.

## Test 6 – Student with no results

Create or use a student with no assessment results.

Expected result:

Recorded Results: `0`

Overall Progress: `No results recorded`

The system must not incorrectly display `0%`, because no performance data exists yet.

## Test 7 – Persistence

Refresh the Progress page or restart the browser.

Expected result: progress is recreated correctly from the persistent MySQL result data.

## Evidence to retain

Capture screenshots showing:

A student with multiple recorded results and a calculated overall percentage.

A manually verified calculation.

A student with no results showing `No results recorded`.

Optionally, record a new mark and capture the progress value before and after to demonstrate dynamic recalculation.
