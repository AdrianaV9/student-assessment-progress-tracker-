<?php
$pageTitle = $pageTitle ?? 'Student Assessment Tracker';
$currentPage = basename($_SERVER['PHP_SELF']);

$studentPages = [
    'students.php',
    'add_student.php',
    'view_student.php',
    'edit_student.php',
    'delete_student.php'
];

$assessmentPages = [
    'assessments.php',
    'add_assessment.php',
    'edit_assessment.php',
    'delete_assessment.php'
];

$resultPages = [
    'results.php',
    'add_result.php',
    'edit_feedback.php'
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >
    <title><?= htmlspecialchars($pageTitle) ?></title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<a class="skip-link" href="#main-content">
    Skip to main content
</a>

<header class="site-header">
    <div class="container header-content">
        <div>
            <p class="site-kicker">Student Progress Tracker</p>
            <h1>
                Student Assessment Management and Progress Tracking System
            </h1>
        </div>

        <button
            type="button"
            class="nav-toggle"
            aria-expanded="false"
            aria-controls="primary-navigation"
        >
            <span class="nav-toggle-label">Menu</span>
            <span class="nav-toggle-icon" aria-hidden="true">☰</span>
        </button>
    </div>
</header>

<nav
    class="main-nav"
    id="primary-navigation"
    aria-label="Primary navigation"
>
    <div class="container nav-links">
        <a
            class="<?= $currentPage === 'index.php' ? 'active' : '' ?>"
            href="index.php"
            <?= $currentPage === 'index.php' ? 'aria-current="page"' : '' ?>
        >
            Dashboard
        </a>

        <a
            class="<?= in_array($currentPage, $studentPages, true) ? 'active' : '' ?>"
            href="students.php"
            <?= in_array($currentPage, $studentPages, true)
                ? 'aria-current="page"'
                : '' ?>
        >
            Students
        </a>

        <a
            class="<?= in_array($currentPage, $assessmentPages, true) ? 'active' : '' ?>"
            href="assessments.php"
            <?= in_array($currentPage, $assessmentPages, true)
                ? 'aria-current="page"'
                : '' ?>
        >
            Assessments
        </a>

        <a
            class="<?= in_array($currentPage, $resultPages, true) ? 'active' : '' ?>"
            href="results.php"
            <?= in_array($currentPage, $resultPages, true)
                ? 'aria-current="page"'
                : '' ?>
        >
            Results
        </a>

        <a
            class="<?= $currentPage === 'progress.php' ? 'active' : '' ?>"
            href="progress.php"
            <?= $currentPage === 'progress.php'
                ? 'aria-current="page"'
                : '' ?>
        >
            Progress
        </a>
    </div>
</nav>

<main
    class="container main-content"
    id="main-content"
>
