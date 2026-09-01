<?php
$pageTitle = $pageTitle ?? 'Student Assessment Tracker';
$currentPage = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle) ?></title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<header class="site-header">
    <div class="container">
        <h1>Student Assessment Management and Progress Tracking System</h1>
    </div>
</header>

<nav class="main-nav">
    <div class="container nav-links">
        <a class="<?= $currentPage === 'index.php' ? 'active' : '' ?>" href="index.php">Dashboard</a>
        <a class="<?= $currentPage === 'students.php' ? 'active' : '' ?>" href="students.php">Students</a>
        <a class="<?= $currentPage === 'assessments.php' ? 'active' : '' ?>" href="assessments.php">Assessments</a>
        <a class="<?= $currentPage === 'results.php' ? 'active' : '' ?>" href="results.php">Results</a>
        <a class="<?= $currentPage === 'progress.php' ? 'active' : '' ?>" href="progress.php">Progress</a>
    </div>
</nav>

<main class="container main-content">
