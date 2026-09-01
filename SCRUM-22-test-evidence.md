# SCRUM-22 Test Evidence – Record Student Assessment Marks

## Story

As a user, I want to enter assessment marks for individual students so that academic performance can be recorded.

## Acceptance criteria

A student and assessment can be selected.

A mark can be entered within the valid range.

Invalid marks are rejected.

The result is stored in the database.

The saved mark is displayed correctly.

## Test 1 – Valid mark

Use an existing fictional student and an existing assessment.

Example:

Student: `S1001 - Alex Morgan`

Assessment: `PFD200 - Web Development Project`

Maximum mark: `100`

Mark achieved: `72`

Expected result: the application redirects to `results.php`, displays `Assessment mark recorded successfully.`, and shows the saved mark as `72.00`.

## Test 2 – Mark above maximum

For an assessment with maximum mark `100`, enter:

`110`

Expected result: the record is rejected and the application explains that the mark cannot be greater than the maximum mark.

## Test 3 – Negative mark

Enter:

`-1`

Expected result: the record is rejected and `Mark cannot be below zero.` is displayed.

## Test 4 – Missing selection

Submit without selecting a student or assessment.

Expected result: the form is rejected and a valid student/assessment selection is required.

## Test 5 – Duplicate result

After recording a mark for one student and assessment, try recording another mark for the same student and assessment.

Expected result: the duplicate is rejected because each student-assessment combination may have only one result record.

## Test 6 – Database persistence

After saving a valid mark, refresh `results.php` and inspect the `results` table in phpMyAdmin.

Expected result: the result remains present and contains the correct `student_id`, `assessment_id` and `mark_achieved`.

## Important scope note

SCRUM-22 displays the raw mark and maximum mark only.

Percentage calculation is deliberately left for SCRUM-23.

## Evidence to retain

Capture screenshots of the Record Mark form, a successfully saved mark in the Results page, one invalid-mark error, and the corresponding `results` row in phpMyAdmin.

Use fictional/coursework data only.
