# SCRUM-21 Test Evidence – Edit and Delete Assessment

## Story

As a user, I want to edit or delete assessments so that assessment information can be maintained accurately.

## Acceptance criteria

Existing assessment details can be opened and edited.

Required fields remain validated.

Saved changes persist in the database.

An assessment can be deleted after confirmation.

Deleted assessments no longer appear in the assessment list.

## Test 1 – Open assessment for editing

From `assessments.php`, select **Edit**.

Expected result: the edit form opens with the existing assessment title, module, due date and maximum mark already populated.

## Test 2 – Valid update

Change the assessment title or due date and press **Save Changes**.

Expected result: the application returns to the assessment list, displays `Assessment updated successfully.`, and shows the new value.

## Test 3 – Required-field validation

Remove the assessment title and save.

Expected result: the update is rejected and `Assessment title is required.` is shown.

Repeat with module code, module name, due date or maximum mark if required.

## Test 4 – Invalid maximum mark

Enter `0` for Maximum Mark.

Expected result: the update is rejected and `Maximum mark must be greater than zero.` is shown.

## Test 5 – Database persistence

Save a valid change, refresh the assessment list and inspect the assessment row in phpMyAdmin.

Expected result: the updated value remains stored.

## Test 6 – Cancel deletion

Select **Delete** for a temporary assessment and then select **Cancel**.

Expected result: the assessment remains in the database and assessment list.

## Test 7 – Confirm deletion

Create or use a temporary fictional assessment. Select **Delete** and then **Yes, Delete Assessment**.

Expected result: the application returns to the assessment list, displays `Assessment deleted successfully.`, and the assessment no longer appears.

## Test 8 – Invalid assessment ID

Open:

`edit_assessment.php?id=999999`

and:

`delete_assessment.php?id=999999`

Expected result: `Assessment record not found.` is shown.

## Evidence to retain

Capture screenshots of the pre-populated Edit Assessment form, a successful update, one validation error, the deletion confirmation page, and the assessment list after deletion.

Use fictional/coursework test data for destructive testing.
