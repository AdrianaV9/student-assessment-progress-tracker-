# SCRUM-18 Test Evidence – View and Edit Student Details

## Acceptance criteria

Existing student records can be opened and viewed.

Editable fields can be updated.

Required fields remain validated.

Saved changes persist in MySQL.

Updated information is displayed correctly.

## Test 1 – View existing student

Open `students.php` and select **View**.

Expected: the correct Student ID, name, email and course/programme are displayed.

## Test 2 – Edit page pre-population

Select **Edit**.

Expected: the form opens with the existing student details already populated.

## Test 3 – Valid update

Change the fictional student's email or course/programme and select **Save Changes**.

Expected: the application redirects to the View Student page, shows a success message and displays the updated information.

## Test 4 – Required fields

Remove Student ID, First Name or Last Name and submit.

Expected: the update is rejected and the relevant required-field error is displayed.

## Test 5 – Invalid email

Enter `invalid-email`.

Expected: the update is rejected.

## Test 6 – Duplicate Student ID

Edit one fictional student so its Student ID matches another existing student.

Expected: the update is rejected with `That student ID already exists.`

## Test 7 – Invalid record

Open `view_student.php?id=999999`.

Expected: `Student record not found.`

## Test 8 – Persistence

Save a valid update, refresh the page and check phpMyAdmin.

Expected: the new values remain stored in MySQL.

Use fictional data only.
