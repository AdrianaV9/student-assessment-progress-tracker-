# Technical Documentation

## 1. System Overview

The Student Assessment Management and Progress Tracking System is a PHP/MySQL web application designed to manage student assessment information and provide a simple overview of academic progress.

The application uses a conventional server-rendered architecture. PHP processes requests, validates user input, communicates with MySQL through PDO and renders HTML. CSS provides layout and responsive behaviour, while JavaScript is used for responsive navigation and limited interface behaviour.

The MVP was deliberately scoped to demonstrate complete CRUD operations, relational database design, validation, calculated data, testing and project documentation within the development period.

## 2. Application Architecture

The presentation layer uses HTML, CSS and limited JavaScript. The application layer uses PHP for form processing, validation, CRUD operations, calculations, feedback, search/filtering and error handling. Reusable validation functions are stored in `includes/validation.php`.

The data layer uses MySQL through PDO configured in `config/database.php`. PDO exception mode and native prepared statements are used.

## 3. Database Design

The database is named `student_assessment_tracker`.

The `students` table stores student number, first name, last name, email and course/programme. Student number is unique, and non-empty email values are treated as unique.

The `modules` table stores module code and module name. Module code is unique.

The `assessments` table stores module reference, title, due date and maximum mark. Each assessment belongs to one module.

The `results` table stores student reference, assessment reference, mark achieved and feedback. The student/assessment combination is unique, preventing duplicate results for the same assessment.

Deleting a student removes linked results. Deleting an assessment removes linked results.

## 4. Database Relationships

```text
modules
   |
   | 1:M
   v
assessments
   |
   | 1:M
   v
results
   ^
   | M:1
   |
students
```

The `results` table resolves the conceptual many-to-many relationship between students and assessments.

## 5. Database Access and PDO

The application uses PDO prepared statements for queries involving user-supplied data. This supports clear parameter binding and reduces SQL injection risk when correctly implemented.

During SCRUM-27, the search query initially reused the same named placeholder several times. With native prepared statements, this produced `SQLSTATE[HY093]: Invalid parameter number`. The query was corrected to use separate placeholders for student number, first name, last name, full name and email, with each placeholder receiving the same search value.

## 6. Student Management

Student management provides create, read, update, delete, search and filter operations. Required values are validated before database changes. Duplicate student IDs and duplicate non-empty email addresses are rejected.

Search supports student ID, name and email. Course/programme filtering can be used independently or together with the text search.

## 7. Assessment Management

Assessment records contain title, module code, module name, due date and maximum mark. When an assessment is created or edited, the application can reuse an existing module or create the required module record as part of the workflow.

## 8. Result Management

Each result links one student to one assessment and contains a mark plus optional feedback. The selected student and assessment must exist. Marks must be numeric, non-negative and no greater than the assessment maximum. Duplicate student-assessment results are rejected.

## 9. Percentage Calculation

Assessment percentages are derived rather than stored independently:

```text
percentage = (mark_achieved / maximum_mark) × 100
```

This avoids a redundant stored value becoming inconsistent with its source data. `NULLIF` is used where appropriate to prevent division by zero.

## 10. Overall Progress Calculation

Overall progress is the average of a student's recorded assessment percentages. Only recorded results contribute. A student with no recorded results displays a no-results state.

## 11. Dashboard

The dashboard displays student count, assessment count, recorded result count, average recorded performance, student progress and assessment due-date status.

Due-date status can display `Upcoming`, `Due today` or `Overdue`. The system does not maintain a separate submission-status field, so this should not be interpreted as submission status.

## 12. Validation Strategy

Browser-level HTML constraints provide immediate feedback, but PHP server-side validation is authoritative. Shared validation logic covers student fields, assessment fields and result marks. Database uniqueness and relational checks provide additional integrity controls.

## 13. Security Considerations

The academic MVP uses PDO prepared statements, server-side input validation, escaped HTML output where applicable, database uniqueness constraints and foreign keys.

The project does not currently implement authentication, role permissions, CSRF protection or production deployment hardening. It should therefore be treated as a local academic development application rather than production-ready software.

## 14. Responsive Design

SCRUM-31 added a mobile navigation toggle, active navigation states, stronger keyboard focus indicators, a skip-to-content link, responsive dashboard cards, stacked form/search actions on narrow screens and horizontal scrolling for wide tables.

## 15. Testing Strategy

Testing was performed incrementally during development and consolidated in SCRUM-29. The final report contains FT01–FT30 covering dashboard behaviour, student CRUD, search/filtering, assessment CRUD, validation, result recording, duplicate prevention, percentage calculation, feedback, overall progress, due-date status and navigation.

All planned final functional tests were recorded as PASS. Supporting evidence is stored under `docs/testing/`.

## 16. Defect Handling

The most significant documented development defect was the SCRUM-27 PDO search-parameter issue. The failure was reproduced, diagnosed, corrected and retested. It provides evidence of practical debugging and regression testing.

## 17. Project Management

Development used an Agile/Scrum-inspired approach rather than a full organisational Scrum implementation. Jira was used to manage epics, tasks/stories, priorities, story points, acceptance criteria and workflow statuses.

The planned sprints were:

```text
Sprint 1 – Planning and Foundation
Sprint 2 – Student / Assessment Management
Sprint 3 – Results and Progress Tracking
Sprint 4 – Testing and Completion
```

GitHub was used as the repository and as project evidence.

## 18. Key Design Decisions

Percentages are derived from source marks rather than duplicated in the database. Results reference students and assessments through foreign keys. Shared validation keeps add/edit workflows consistent. A server-rendered PHP architecture was selected to control complexity while demonstrating front-end, back-end and database skills.

Authentication, notifications, VLE integration and similar larger features were deliberately excluded from the MVP so the core assessment workflow could be fully implemented and tested.

## 19. Future Development

Possible future extensions include authentication and role-based permissions, student login, assessment submission tracking, result-editing workflows, reporting/charts, notifications, CSV/PDF export, automated testing, CSRF protection, production deployment configuration and external VLE/API integration.

## 20. Conclusion

The completed system demonstrates a working full-stack PHP/MySQL application supported by structured requirements, relational database design, CRUD implementation, validation, calculated progress data, responsive design, functional testing, documented defect resolution and Jira/GitHub evidence.
