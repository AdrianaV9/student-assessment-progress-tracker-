# SCRUM-17 Test Evidence – Add Student Record

## Story

As a user, I want to add a student so that their assessment information and progress can be managed within the system.

## Acceptance criteria covered

A student name can be entered.

A unique student ID can be entered.

Required fields cannot be empty.

Invalid information is rejected.

Valid student information is stored in MySQL.

The new student appears in the student list.

## Suggested test cases

### Test 1 – Valid student

Student ID: `S1001`

First Name: `Alex`

Last Name: `Morgan`

Email: `alex.morgan@example.com`

Course / Programme: `FdSc Computing`

Expected result: the form submits successfully, redirects to `students.php`, shows a success message, and the new student appears in the table.

### Test 2 – Missing required information

Leave Student ID blank and submit.

Expected result: the record is not stored and an error message states that Student ID is required.

Repeat with First Name and Last Name.

### Test 3 – Invalid email

Enter:

`not-an-email`

Expected result: the record is not stored and a valid-email error message is displayed.

### Test 4 – Duplicate Student ID

After adding `S1001`, try adding another student using `S1001`.

Expected result: the duplicate record is rejected and the user sees:

`That student ID already exists.`

### Test 5 – Database persistence

After adding a valid student, refresh the student-list page or restart the browser.

Expected result: the student still appears because the record is stored in MySQL.

## Evidence to retain

Take screenshots of:

1. the completed Add Student form before submission;
2. the Students page showing the successful record;
3. one validation error;
4. phpMyAdmin showing the student row in the `students` table.

Do not use personal or real student data for coursework testing. Use fictional test records only.
