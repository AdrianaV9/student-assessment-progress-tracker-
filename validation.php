<?php

/**
 * Shared validation helpers used by the main data-entry forms.
 *
 * Browser validation improves usability, but these PHP checks remain
 * authoritative because client-side validation can be bypassed.
 */

function appStringLength(string $value): int
{
    if (function_exists('mb_strlen')) {
        return mb_strlen($value, 'UTF-8');
    }

    return strlen($value);
}

function isValidDateYmd(string $value): bool
{
    $date = DateTimeImmutable::createFromFormat('!Y-m-d', $value);
    $errors = DateTimeImmutable::getLastErrors();

    if ($date === false) {
        return false;
    }

    if (
        is_array($errors)
        && (
            ($errors['warning_count'] ?? 0) > 0
            || ($errors['error_count'] ?? 0) > 0
        )
    ) {
        return false;
    }

    return $date->format('Y-m-d') === $value;
}

function validateStudentFields(
    string $studentNumber,
    string $firstName,
    string $lastName,
    string $email,
    string $courseProgramme
): array {
    $errors = [];

    if ($studentNumber === '') {
        $errors[] = 'Student ID is required.';
    }

    if ($firstName === '') {
        $errors[] = 'First name is required.';
    }

    if ($lastName === '') {
        $errors[] = 'Last name is required.';
    }

    if ($studentNumber !== '' && appStringLength($studentNumber) > 20) {
        $errors[] = 'Student ID must be 20 characters or fewer.';
    }

    if ($firstName !== '' && appStringLength($firstName) > 100) {
        $errors[] = 'First name must be 100 characters or fewer.';
    }

    if ($lastName !== '' && appStringLength($lastName) > 100) {
        $errors[] = 'Last name must be 100 characters or fewer.';
    }

    if ($email !== '') {
        if (appStringLength($email) > 255) {
            $errors[] = 'Email address must be 255 characters or fewer.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Enter a valid email address.';
        }
    }

    if (
        $courseProgramme !== ''
        && appStringLength($courseProgramme) > 150
    ) {
        $errors[] = 'Course / Programme must be 150 characters or fewer.';
    }

    return $errors;
}

function validateAssessmentFields(
    string $title,
    string $moduleCode,
    string $moduleName,
    string $dueDate,
    string $maximumMark
): array {
    $errors = [];

    if ($title === '') {
        $errors[] = 'Assessment title is required.';
    }

    if ($moduleCode === '') {
        $errors[] = 'Module code is required.';
    }

    if ($moduleName === '') {
        $errors[] = 'Module name is required.';
    }

    if ($dueDate === '') {
        $errors[] = 'Due date is required.';
    }

    if ($maximumMark === '') {
        $errors[] = 'Maximum mark is required.';
    }

    if ($title !== '' && appStringLength($title) > 200) {
        $errors[] = 'Assessment title must be 200 characters or fewer.';
    }

    if ($moduleCode !== '' && appStringLength($moduleCode) > 30) {
        $errors[] = 'Module code must be 30 characters or fewer.';
    }

    if ($moduleName !== '' && appStringLength($moduleName) > 150) {
        $errors[] = 'Module name must be 150 characters or fewer.';
    }

    if ($dueDate !== '' && !isValidDateYmd($dueDate)) {
        $errors[] = 'Enter a valid due date.';
    }

    if ($maximumMark !== '') {
        if (!is_numeric($maximumMark)) {
            $errors[] = 'Maximum mark must be a number.';
        } else {
            $maximumValue = (float) $maximumMark;

            if (!is_finite($maximumValue) || $maximumValue <= 0) {
                $errors[] = 'Maximum mark must be greater than zero.';
            } elseif ($maximumValue > 9999.99) {
                $errors[] =
                    'Maximum mark cannot exceed 9999.99.';
            }
        }
    }

    return $errors;
}

function validateMarkValue(
    string $markAchieved,
    ?float $maximumMark = null
): array {
    $errors = [];

    if ($markAchieved === '') {
        $errors[] = 'Mark is required.';
        return $errors;
    }

    if (!is_numeric($markAchieved)) {
        $errors[] = 'Mark must be a number.';
        return $errors;
    }

    $markValue = (float) $markAchieved;

    if (!is_finite($markValue)) {
        $errors[] = 'Mark must be a valid number.';
        return $errors;
    }

    if ($markValue < 0) {
        $errors[] = 'Mark cannot be below zero.';
    }

    if ($markValue > 9999.99) {
        $errors[] = 'Mark cannot exceed 9999.99.';
    }

    if (
        $maximumMark !== null
        && $markValue > $maximumMark
    ) {
        $errors[] =
            'Mark cannot be greater than the assessment maximum mark of '
            . number_format($maximumMark, 2)
            . '.';
    }

    return $errors;
}
