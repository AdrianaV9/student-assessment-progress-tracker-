# SCRUM-29 Functional Test Report

## Task

Test the completed Student Assessment Management and Progress Tracking System against the defined functional requirements and acceptance criteria.

## Test environment

Application: Student Assessment Management and Progress Tracking System

Local URL: `http://localhost/student-assessment-progress-tracker/`

Environment: XAMPP / Apache / PHP / MySQL

Browser: Google Chrome

Test date: 02/09/2026

Tester: Adriana V

## Functional test cases

| ID | Area | Test case | Expected outcome | Actual outcome | Result |
|---|---|---|---|---|---|
| FT01 | Dashboard | Open the application home page. | Dashboard loads without PHP/MySQL errors and displays summary information. | Dashboard loaded correctly with no errors. | PASS |
| FT02 | Dashboard | Compare Students, Assessments and Recorded Results totals with the relevant pages. | Dashboard totals match the stored application records. | Dashboard totals matched the stored records. | PASS |
| FT03 | Students | Add a student using valid details. | Student is stored and appears in the student list. | Student was saved and displayed correctly. | PASS |
| FT04 | Students | Attempt to add a student with a blank required field. | Submission is blocked and invalid data is not stored. | Blank required field was rejected and no record was saved. | PASS |
| FT05 | Students | Attempt to add a student using an invalid email address. | Invalid email is rejected. | Invalid email address was rejected. | PASS |
| FT06 | Students | Attempt to use a duplicate student ID or duplicate non-empty email. | Duplicate value is rejected and no duplicate record is created. | Duplicate student ID/email was rejected. | PASS |
| FT07 | Students | Open an existing student's View page. | Correct student details are displayed. | Correct student details were displayed. | PASS |
| FT08 | Students | Edit an existing student using valid values. | Updated information is saved and remains correct after refresh. | Student details were updated and saved correctly. | PASS |
| FT09 | Students | Search by student ID or student name. | Only matching student records are displayed. | Search returned only matching student records. | PASS |
| FT10 | Students | Apply a Course / Programme filter. | Only students assigned to the selected programme are displayed. | Programme filter returned only matching students. | PASS |
| FT11 | Students | Search for `ZZZ999999`. | A clear `No matching students` state is displayed. | No matching students message displayed correctly. | PASS |
| FT12 | Students | Delete a temporary student and confirm deletion. | Student is removed from the list and database. | Student was deleted successfully. | PASS |
| FT13 | Assessments | Create an assessment using valid details. | Assessment is stored and appears in the assessment list. | Assessment was created and displayed correctly. | PASS |
| FT14 | Assessments | Attempt to create an assessment with a blank required field. | Submission is blocked and invalid data is not stored. | Blank required assessment field was rejected. | PASS |
| FT15 | Assessments | Enter Maximum Mark `0` or a value over `9999.99`. | Invalid maximum mark is rejected. | Invalid maximum mark values were rejected. | PASS |
| FT16 | Assessments | Edit an existing assessment using valid values. | Updated assessment details persist after refresh. | Assessment details were updated and saved correctly. | PASS |
| FT17 | Assessments | Delete a temporary assessment and confirm deletion. | Assessment is removed from the list and database. | Assessment was deleted successfully. | PASS |
| FT18 | Results | Record a valid mark for a student and assessment. | Result is stored and displayed against the correct student and assessment. | Assessment mark was recorded and displayed correctly. | PASS |
| FT19 | Results | Attempt to record a negative mark. | Negative mark is rejected. | Negative mark was rejected. | PASS |
| FT20 | Results | Attempt to record a mark above the assessment maximum. | Mark is rejected and is not stored. | Mark above the assessment maximum was rejected. | PASS |
| FT21 | Results | Attempt to record a second result for the same student and assessment. | Duplicate student-assessment result is rejected. | Duplicate student-assessment result was rejected. | PASS |
| FT22 | Percentage | Check a result such as `60 / 80`. | Percentage displays as `75.00%`. | Percentage was calculated and displayed correctly. | PASS |
| FT23 | Feedback | Add feedback to an existing result. | Feedback is stored with the correct result and displayed later. | Feedback was saved with the correct result. | PASS |
| FT24 | Feedback | Edit existing feedback. | Updated feedback replaces the previous text and persists. | Existing feedback was updated successfully. | PASS |
| FT25 | Progress | Use a student with two known assessment percentages, such as `80%` and `60%`. | Overall progress displays as `70.00%`. | Overall student progress was calculated correctly. | PASS |
| FT26 | Progress | View a student with no results. | Page displays `No results recorded`, not an artificial `0%`. | Student with no results displayed `No results recorded`. | PASS |
| FT27 | Dashboard | Compare a student's dashboard progress with `progress.php`. | Both pages display the same overall progress value. | Dashboard progress matched the Progress page. | PASS |
| FT28 | Dashboard | Check an assessment with a future due date. | Due status displays `Upcoming`. | Future assessment displayed as Upcoming. | PASS |
| FT29 | Dashboard | Check an assessment with a past due date. | Due status displays `Overdue`. | Past assessment displayed as Overdue. | PASS |
| FT30 | Navigation | Open Dashboard, Students, Assessments, Results and Progress from the main navigation. | Every navigation link opens the correct page without errors. | All main navigation links opened the correct pages. | PASS |

## Regression and data-integrity check

After completing the tests, refresh the main pages and confirm that valid test data remains available and invalid test attempts did not create unwanted database records.

If temporary students, assessments or results were created only for testing, remove them after capturing the necessary evidence.

## Defect recording

Any failed test should not be hidden or changed to PASS.

For each failure, record the test ID, observed behaviour, expected behaviour, steps to reproduce, error message if any, and screenshot reference.

Any defect identified can then be addressed under SCRUM-30 – Fix identified defects.

## Overall result

All planned functional test cases are recorded as PASS.

## Completion note

The completed test report provides evidence that the implemented Student Assessment Management and Progress Tracking System has been tested against the defined functional requirements and acceptance criteria across dashboard, student management, assessment management, result entry, validation, percentage calculation, feedback, progress tracking, filtering and navigation.
