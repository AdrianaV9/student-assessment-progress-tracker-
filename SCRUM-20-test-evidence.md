# SCRUM-20 Test Evidence – Create Assessment

## Story

As a user, I want to create an assessment so that assessment activity can be recorded.

## Acceptance criteria

An assessment title can be entered.

Module, due date and maximum mark can be recorded.

Required fields are validated.

Valid assessment data is stored in the database.

The assessment appears in the assessment list.

## Test 1 – Valid assessment

Use fictional/test data:

Assessment Title: `Web Development Project`

Module Code: `PFD200`

Module Name: `Professional Development`

Due Date: choose a suitable future test date.

Maximum Mark: `100`

Expected result: the form saves successfully, redirects to `assessments.php`, shows `Assessment created successfully.`, and displays the new assessment.

## Test 2 – Required fields

Leave Assessment Title blank and submit.

Expected result: the record is not created and `Assessment title is required.` is displayed.

Repeat for Module Code, Module Name, Due Date and Maximum Mark.

## Test 3 – Maximum mark validation

Enter:

`0`

Expected result: the record is rejected and `Maximum mark must be greater than zero.` is displayed.

Also test a negative value if required.

## Test 4 – Module creation

Create an assessment using a new module code such as `TEST100`.

Expected result: the assessment is stored and a corresponding module row is created in the `modules` table.

## Test 5 – Existing module reuse

Create another assessment using the same module code.

Expected result: the application reuses the existing module instead of creating a duplicate module-code record.

## Test 6 – Database persistence

Refresh `assessments.php` and inspect the `assessments` and `modules` tables in phpMyAdmin.

Expected result: the saved assessment remains present and is linked to the correct module through `module_id`.

## Evidence to retain

Capture screenshots of the Create Assessment form, the assessment appearing in the list, one validation error, and phpMyAdmin showing the saved assessment/module relationship.

Use fictional or coursework test data only.
