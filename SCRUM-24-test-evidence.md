# SCRUM-24 Test Evidence – Record Assessment Feedback

## Story

As a user, I want to record feedback alongside an assessment result so that comments can be reviewed later.

## Acceptance criteria

Feedback can be entered for a student's assessment result.

Feedback is stored with the correct student and assessment.

Saved feedback can be viewed later.

Feedback can be updated when required.

## Test 1 – Add feedback

From `results.php`, select **Add Feedback** for an existing result.

Enter:

`Good understanding of the main concepts. Review validation techniques before the next assessment.`

Select **Save Feedback**.

Expected result: the application returns to the Results page, shows `Assessment feedback saved successfully.`, and displays the feedback beside the correct result.

## Test 2 – Database persistence

Refresh the Results page and inspect the corresponding row in the `results` table in phpMyAdmin.

Expected result: the feedback remains stored in the `feedback` column.

## Test 3 – Update feedback

Select **Edit Feedback** on a result that already contains feedback.

Change the wording and save.

Expected result: the new feedback replaces the previous text and is displayed correctly.

## Test 4 – Correct record association

Use at least two results for different students or assessments.

Add different feedback to each result.

Expected result: each feedback comment remains attached to the correct result record.

## Test 5 – Remove feedback

Open Edit Feedback, delete all text and save.

Expected result: the database stores `NULL` and the Results page displays `No feedback`.

## Test 6 – Invalid result ID

Open:

`edit_feedback.php?id=999999`

Expected result:

`Assessment result not found.`

## Evidence to retain

Capture screenshots of the feedback form, feedback displayed in the Results table, updated feedback, and optionally the `feedback` value in phpMyAdmin.

Use fictional/coursework feedback only.
