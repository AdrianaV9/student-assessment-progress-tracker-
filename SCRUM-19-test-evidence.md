# SCRUM-19 Test Evidence – Delete Student Record

## Acceptance criteria

A student record can be selected for deletion.

The user is asked to confirm the deletion.

The selected record is removed from the database.

The deleted student no longer appears in the student list.

## Test 1 – Open deletion confirmation

Use a fictional student record and select **Delete** from `students.php`.

Expected result: a confirmation page appears showing the correct Student ID and student name.

## Test 2 – Cancel deletion

On the confirmation page, select **Cancel**.

Expected result: the application returns to the Students page and the student remains in the list.

## Test 3 – Confirm deletion

Create a temporary fictional student, for example:

Student ID: `TEST-DELETE`

First Name: `Delete`

Last Name: `Example`

Open the Delete page and select **Yes, Delete Student**.

Expected result: the application returns to `students.php`, shows `Student deleted successfully.`, and the record no longer appears in the student list.

## Test 4 – Database confirmation

Open phpMyAdmin and inspect the `students` table.

Expected result: the deleted student's row no longer exists.

## Test 5 – Invalid student

Open:

`delete_student.php?id=999999`

Expected result: `Student record not found.`

## Test 6 – Direct GET safety

Opening the Delete link must only display the confirmation page.

Expected result: the record must not be deleted until the confirmation form is submitted using POST.

## Evidence to retain

Capture screenshots of the deletion confirmation page, the Students page after successful deletion, and optionally phpMyAdmin showing that the record has been removed.

Use only fictional test records. Do not delete a record that you need to retain for later testing.
