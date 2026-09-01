<?php
/**
 * Database connection
 * SCRUM-16: Create project structure and connect PHP to MySQL
 *
 * PDO is used because it supports prepared statements and provides
 * a clear, maintainable interface for database access.
 */

$host = getenv('DB_HOST') ?: 'localhost';
$port = getenv('DB_PORT') ?: '3306';
$dbname = getenv('DB_NAME') ?: 'student_assessment_tracker';
$username = getenv('DB_USER') ?: 'root';
$password = getenv('DB_PASSWORD') ?: '';

$dsn = "mysql:host={$host};port={$port};dbname={$dbname};charset=utf8mb4";

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $username, $password, $options);
} catch (PDOException $exception) {
    /*
     * Do not expose full database errors to end users in a production
     * application. For this coursework development build, a clear
     * message is shown so connection problems can be diagnosed.
     */
    die(
        'Database connection failed. Check MySQL is running, import the '
        . 'database/student_assessment.sql file, and confirm the settings '
        . 'in config/database.php.'
    );
}
