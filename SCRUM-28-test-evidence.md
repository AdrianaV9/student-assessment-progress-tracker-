# SCRUM-28 Test Evidence – Implement Input Validation

## Task

Implement validation for user input across student, assessment and result forms. Prevent empty required fields, invalid marks and unsuitable data from being stored.

## Validation approach

The application uses layered validation.

HTML input attributes such as `required`, `maxlength`, `min`, `max`, `type="email"` and `type="number"` provide immediate browser-side feedback.

PHP server-side validation remains authoritative because browser-side validation can be bypassed.

PDO prepared statements continue to be used for database operations.

A shared file, `includes/validation.php`, centralises the main reusable validation rules.

## Student validation

Test Add Student and Edit Student.

Required values: Student ID, First Name and Last Name.

Student ID maximum length: 20 characters.

First and last name maximum length: 100 characters each.

Email is optional but, when supplied, must be a valid email address and no longer than 255 characters.

Course / Programme is optional and no longer than 150 characters.

Duplicate Student IDs are rejected.

Duplicate non-empty email addresses are rejected.

Expected result: invalid input is not written to MySQL and a clear validation message is shown.

## Assessment validation

Test Create Assessment and Edit Assessment.

Required values: Assessment Title, Module Code, Module Name, Due Date and Maximum Mark.

Assessment Title maximum length: 200 characters.

Module Code maximum length: 30 characters.

Module Name maximum length: 150 characters.

Due Date must be a valid `YYYY-MM-DD` date.

Maximum Mark must be numeric, greater than zero and no greater than 9999.99, matching the database `DECIMAL(6,2)` capacity.

Expected result: unsuitable assessment values are rejected before storage.

## Result validation

Test Record Assessment Mark.

A valid existing student must be selected.

A valid existing assessment must be selected.

Mark is required and must be numeric.

Mark cannot be below zero.

Mark cannot exceed 9999.99.

Mark cannot exceed the selected assessment's maximum mark.

A duplicate student-assessment result is rejected.

Expected result: invalid marks or invalid record references are not stored.

## Suggested functional tests

### Test 1 – Blank student required field

Attempt to create a student with the Student ID blank.

Expected result: submission is blocked or `Student ID is required.` is shown.

### Test 2 – Invalid email

Enter `not-an-email` in the Email field.

Expected result: invalid email is rejected.

### Test 3 – Duplicate email

Use the email address of another existing student.

Expected result: `That email address is already used by another student.`

### Test 4 – Excessively long course value

Enter more than 150 characters in Course / Programme.

Expected result: the browser maxlength prevents further typing; server-side validation also rejects an oversized value if browser validation is bypassed.

### Test 5 – Invalid assessment maximum

Try `0` as Maximum Mark.

Expected result: rejected.

Then try a value greater than `9999.99`.

Expected result: rejected.

### Test 6 – Invalid result mark

For an assessment with maximum mark 100, try `-1` and then `101`.

Expected result: both are rejected.

### Test 7 – Duplicate result

Attempt to record another mark for a student/assessment pair that already has a result.

Expected result: duplicate result is rejected.

### Test 8 – Valid data regression test

Create a valid temporary student, assessment and result.

Expected result: all valid records save normally after the validation changes.

Delete temporary test records afterwards if they are not required.

## Evidence to retain

Capture screenshots of at least one invalid student input, one invalid assessment input, one invalid result mark, and one successful valid submission.

Retain the Jira test comment and GitHub commit as additional QA evidence.
