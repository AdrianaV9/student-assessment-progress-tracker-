# Student Assessment Management and Progress Tracking System

A web-based student assessment management application developed as a negotiated mini-project for the PFD200 Professional Development module.

The system provides a central place to manage student records, assessments, marks, feedback and overall academic progress. It was developed to demonstrate practical full-stack development skills using PHP and MySQL alongside front-end technologies and an Agile/Scrum-inspired project workflow.

## Main Features

The application supports student CRUD, assessment CRUD, module-linked assessments, mark recording, duplicate-result prevention, automatic percentage calculation, feedback, overall progress calculation, dashboard summaries, student search/filtering, input validation and responsive layouts.

## Technologies Used

| Technology | Purpose |
|---|---|
| HTML5 | Page structure and forms |
| CSS3 | Interface styling and responsive design |
| JavaScript | Responsive navigation and interface behaviour |
| PHP | Server-side application logic |
| MySQL | Persistent relational data storage |
| PDO | Database access using prepared statements |
| XAMPP | Local Apache/PHP/MySQL development environment |
| Jira | Agile/Scrum-inspired planning and task tracking |
| GitHub | Repository and development evidence |

## Project Structure

```text
student-assessment-progress-tracker/
├── config/
│   └── database.php
├── css/
│   └── style.css
├── docs/
│   ├── setup.md
│   ├── technical-documentation.md
│   └── testing/
├── includes/
│   ├── footer.php
│   ├── header.php
│   └── validation.php
├── js/
│   └── script.js
├── add_assessment.php
├── add_result.php
├── add_student.php
├── assessments.php
├── edit_assessment.php
├── edit_feedback.php
├── edit_student.php
├── index.php
├── progress.php
├── results.php
├── students.php
└── README.md
```

## Database

The application uses the MySQL database `student_assessment_tracker` with four core tables: `students`, `modules`, `assessments` and `results`.

```text
modules       1 ---- many assessments
students      1 ---- many results
assessments   1 ---- many results
```

The `results` table links students and assessments. A student can have only one result for a particular assessment.

## Local Setup

Install XAMPP, place the project inside the XAMPP `htdocs` directory, then start Apache and MySQL.

Example project location:

```text
C:\xampp\htdocs\student-assessment-progress-tracker
```

Open phpMyAdmin at:

```text
http://localhost/phpmyadmin/
```

Create or import the project database using the SQL schema supplied with the repository. The default expected database name is:

```text
student_assessment_tracker
```

Check `config/database.php` and adjust the host, port, database name, username or password if your local environment differs.

Open the application at:

```text
http://localhost/student-assessment-progress-tracker/
```

## Progress Calculations

Assessment percentage is calculated dynamically:

```text
(mark achieved / maximum mark) × 100
```

For example, `60 / 80` produces `75%`.

Overall student progress is calculated as the average of the student's recorded assessment percentages. Students without recorded results display `No results recorded` rather than an artificial zero percentage.

## Validation

Server-side PHP validation is authoritative, with browser constraints providing additional usability feedback. Validation includes required fields, email format, duplicate student ID/email checks, positive maximum marks, mark range checks and duplicate student-assessment prevention.

PDO prepared statements are used for database queries involving user-supplied values.

## Responsive Interface

At narrower widths, the navigation changes to a mobile Menu control, dashboard cards reorganise, search/form actions stack, tables remain horizontally scrollable and keyboard focus states remain visible.

## Testing

Functional testing evidence is stored under:

```text
docs/testing/
```

The final functional test cycle covers FT01–FT30 and all planned final tests passed.

A PDO search defect discovered during SCRUM-27 was corrected by replacing a reused named placeholder with unique named placeholders. The corrected search implementation passed subsequent testing.

## Project Management

The mini-project used an Agile/Scrum-inspired iterative approach with Jira and four planned development sprints:

```text
Planning and Foundation
Student / Assessment Management
Results and Progress Tracking
Testing and Completion
```

GitHub was used to retain implementation and development evidence.

## Current Scope and Limitations

This is an academic/local-development application rather than a production university information system. The current scope excludes multi-user authentication, student self-service login, notifications, external VLE integration, predictive analytics, AI grading and production cloud deployment.

## Repository

```text
https://github.com/AdrianaV9/student-assessment-progress-tracker
```

## Supporting Documentation

See `docs/technical-documentation.md` and `docs/testing/` for more detailed implementation and testing evidence.
