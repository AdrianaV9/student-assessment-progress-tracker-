# SCRUM-27 Test Evidence – Search and Filter Student Records

## Story

As a user, I want to search and filter student records so that relevant information can be found quickly.

## Acceptance criteria

Students can be searched by relevant identifiers such as name or student ID.

Search results update correctly.

Filters return only matching records.

A clear result is shown when no records match.

## Test 1 – Search by student ID

Use the exact or partial student number of an existing fictional student.

Expected result: only records whose student ID contains the search value are displayed.

## Test 2 – Search by first or last name

Search using part of an existing student's name.

Expected result: matching student records are displayed.

## Test 3 – Search by full name

Search using a full name stored in the database.

Expected result: the matching student is displayed.

## Test 4 – Search by email

Search using part of an existing student's email address.

Expected result: matching student records are displayed.

## Test 5 – Programme filter

Ensure at least two students have different Course / Programme values.

Choose one programme from the filter.

Expected result: only students assigned to that programme are displayed.

## Test 6 – Combined search and filter

Enter a name or student ID and choose a programme.

Expected result: only records matching both conditions are displayed.

## Test 7 – No matching records

Search for a value that does not exist, such as `ZZZ999999`.

Expected result: `No matching students` is shown with an option to reset the search/filter.

## Test 8 – Clear filters

Run a search or filter and then select **Clear**.

Expected result: the full student list returns.

## Test 9 – Existing actions remain available

After filtering, select View, Edit or Delete on a displayed student.

Expected result: the existing action opens the correct record.

## Implementation note

Each LIKE comparison uses its own named PDO placeholder because the project connection uses native prepared statements (`ATTR_EMULATE_PREPARES = false`). All search placeholders receive the same escaped search value.

## Evidence to retain

Capture screenshots of a student ID search, a programme filter, a combined search/filter, and the no-results state.
