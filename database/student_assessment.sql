-- Student Assessment Management and Progress Tracking System
-- SCRUM-15: Database Structure and Relationships
-- Target DBMS: MySQL 8+

CREATE DATABASE IF NOT EXISTS student_assessment_tracker
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE student_assessment_tracker;

-- -----------------------------------------------------
-- Table: students
-- Stores the core student record.
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS students (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    student_number VARCHAR(20) NOT NULL UNIQUE,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    email VARCHAR(255) NULL UNIQUE,
    course_programme VARCHAR(150) NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP
);

-- -----------------------------------------------------
-- Table: modules
-- Stores module information separately to avoid
-- repeating module data for every assessment.
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS modules (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    module_code VARCHAR(30) NOT NULL UNIQUE,
    module_name VARCHAR(150) NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP
);

-- -----------------------------------------------------
-- Table: assessments
-- Each assessment belongs to one module.
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS assessments (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    module_id INT UNSIGNED NOT NULL,
    title VARCHAR(200) NOT NULL,
    due_date DATE NULL,
    maximum_mark DECIMAL(6,2) NOT NULL DEFAULT 100.00,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT chk_assessment_maximum_mark
        CHECK (maximum_mark > 0),

    CONSTRAINT fk_assessments_module
        FOREIGN KEY (module_id)
        REFERENCES modules(id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT,

    INDEX idx_assessments_module_id (module_id),
    INDEX idx_assessments_due_date (due_date)
);

-- -----------------------------------------------------
-- Table: results
-- Resolves the many-to-many relationship between
-- students and assessments.
--
-- One student can have many assessment results.
-- One assessment can have results for many students.
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS results (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    student_id INT UNSIGNED NOT NULL,
    assessment_id INT UNSIGNED NOT NULL,
    mark_achieved DECIMAL(6,2) NOT NULL,
    feedback TEXT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT chk_result_mark_non_negative
        CHECK (mark_achieved >= 0),

    CONSTRAINT uq_student_assessment
        UNIQUE (student_id, assessment_id),

    CONSTRAINT fk_results_student
        FOREIGN KEY (student_id)
        REFERENCES students(id)
        ON UPDATE CASCADE
        ON DELETE CASCADE,

    CONSTRAINT fk_results_assessment
        FOREIGN KEY (assessment_id)
        REFERENCES assessments(id)
        ON UPDATE CASCADE
        ON DELETE CASCADE,

    INDEX idx_results_student_id (student_id),
    INDEX idx_results_assessment_id (assessment_id)
);

-- -----------------------------------------------------
-- Example query: assessment result percentage
--
-- Percentage is calculated rather than stored because
-- it can be derived from mark_achieved / maximum_mark.
-- -----------------------------------------------------
SELECT
    r.id AS result_id,
    s.student_number,
    CONCAT(s.first_name, ' ', s.last_name) AS student_name,
    a.title AS assessment_title,
    r.mark_achieved,
    a.maximum_mark,
    ROUND((r.mark_achieved / a.maximum_mark) * 100, 2) AS percentage,
    r.feedback
FROM results r
JOIN students s
    ON r.student_id = s.id
JOIN assessments a
    ON r.assessment_id = a.id;

-- -----------------------------------------------------
-- Example query: overall student progress
--
-- The MVP uses the average of recorded assessment
-- percentages as an overall progress indicator.
-- -----------------------------------------------------
SELECT
    s.id,
    s.student_number,
    CONCAT(s.first_name, ' ', s.last_name) AS student_name,
    ROUND(
        AVG((r.mark_achieved / a.maximum_mark) * 100),
        2
    ) AS overall_progress_percentage
FROM students s
LEFT JOIN results r
    ON s.id = r.student_id
LEFT JOIN assessments a
    ON r.assessment_id = a.id
GROUP BY
    s.id,
    s.student_number,
    s.first_name,
    s.last_name
ORDER BY
    s.last_name,
    s.first_name;
