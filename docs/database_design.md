# Database Design

## SCRUM-15 – Design database structure and relationships

The database uses four main tables: `students`, `modules`, `assessments`, and `results`.

### Students

The `students` table stores each student's identifying and contact information. `student_number` is unique so that duplicate student records can be prevented.

### Modules

The `modules` table stores module codes and module names. Module information is kept separately from assessments to reduce repeated data.

### Assessments

The `assessments` table stores assessment title, due date, maximum mark and the module to which the assessment belongs.

Each assessment belongs to one module, while one module can contain many assessments.

### Results

The `results` table links students to assessments and stores the achieved mark and feedback.

This table resolves the many-to-many relationship between students and assessments:

- one student can complete many assessments;
- one assessment can have results for many students.

A unique constraint on `student_id` and `assessment_id` prevents the same student from receiving duplicate result records for the same assessment.

## Relationships

`modules 1 ---- many assessments`

`students 1 ---- many results`

`assessments 1 ---- many results`

This means students and assessments have an indirect many-to-many relationship through the `results` table.

## Calculated values

Assessment percentage is not stored as a separate database column. It is calculated using:

`(mark_achieved / maximum_mark) × 100`

This avoids storing duplicated information that could become inconsistent if a mark or maximum mark changes.

Overall student progress is also calculated from stored results rather than being permanently stored.

For the MVP, overall progress is the average percentage across the student's recorded assessments.

## Referential integrity

Deleting a student deletes their related result records using `ON DELETE CASCADE`.

Deleting an assessment deletes its related result records using `ON DELETE CASCADE`.

A module cannot be deleted while assessments still refer to it because the relationship uses `ON DELETE RESTRICT`.

## Validation

The database prevents negative marks and requires the assessment maximum mark to be greater than zero.

A further application-level validation rule will ensure that `mark_achieved` cannot exceed the assessment's `maximum_mark`, because this rule depends on a value held in another table and is best enforced in the PHP application logic for this project.
