# SCRUM-30 Defect Resolution Report

## Task

Resolve defects found during functional testing, retest affected features and document the fixes completed.

## Final functional testing outcome

The completed SCRUM-29 functional test cycle covered FT01–FT30.

All 30 planned functional tests were recorded as PASS.

No outstanding functional defects were identified during the final end-to-end test cycle.

Therefore, no new application code changes were required specifically as a result of SCRUM-29.

## Defect identified earlier in development

A defect was identified during the implementation and local testing of SCRUM-27 – Search and Filter Student Records.

### Defect reference

SCRUM-27 PDO search parameter defect

### Observed behaviour

When a student search was submitted, the Students page produced:

`SQLSTATE[HY093]: Invalid parameter number`

The error occurred in `students.php` when executing the prepared PDO statement.

### Cause

The SQL query reused the same named PDO placeholder, `:search`, several times across multiple `LIKE` conditions.

The project database connection uses native PDO prepared statements with emulated prepares disabled.

The search query therefore required a unique placeholder for each occurrence.

### Fix implemented

The query was changed to use separate named parameters:

`search_student_number`

`search_first_name`

`search_last_name`

`search_full_name`

`search_email`

Each parameter receives the same search value.

### Retest outcome

After the correction, search by student ID, first name, last name, full name and email worked correctly.

Programme filtering and combined search/filtering also worked correctly.

The no-match state displayed correctly.

The corrected implementation was subsequently included in the final SCRUM-29 functional test cycle.

Relevant final test cases, including student search and filtering, passed.

## Current defect status

Outstanding functional defects: **0**

Resolved development defects documented: **1**

Final functional test status: **FT01–FT30 PASS**

## Conclusion

The final functional testing cycle did not identify any unresolved defects requiring additional code changes. The previously identified PDO search defect had already been corrected and retested before the final test cycle. The application therefore proceeds from SCRUM-30 with no known outstanding functional defects based on the completed test evidence.
