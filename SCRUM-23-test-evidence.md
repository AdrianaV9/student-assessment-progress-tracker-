# SCRUM-23 Test Evidence – Calculate Assessment Percentage Automatically

## Story

As a user, I want assessment percentages to be calculated automatically so that student performance can be measured consistently.

## Acceptance criteria

The calculation uses the achieved mark and maximum available mark.

The percentage is mathematically correct.

Invalid values are rejected.

The calculated percentage is displayed with the assessment result.

## Calculation

The application uses:

`percentage = (mark achieved / maximum mark) × 100`

The percentage is calculated when results are retrieved. It is not stored as a separate database value.

## Test 1 – Standard percentage

Assessment maximum mark: `100`

Mark achieved: `72`

Expected result:

`72.00%`

## Test 2 – Different maximum mark

Create or use an assessment with:

Maximum mark: `80`

Record:

Mark achieved: `60`

Expected calculation:

`(60 / 80) × 100 = 75`

Expected display:

`75.00%`

## Test 3 – Decimal percentage

Assessment maximum mark: `60`

Mark achieved: `41`

Expected calculation:

`(41 / 60) × 100 = 68.333...`

Expected display:

`68.33%`

## Test 4 – Full marks

Assessment maximum mark: `50`

Mark achieved: `50`

Expected display:

`100.00%`

## Test 5 – Zero mark

Assessment maximum mark: `100`

Mark achieved: `0`

Expected display:

`0.00%`

## Test 6 – Invalid values

Use the Record Mark form and attempt to enter a mark greater than the assessment maximum or below zero.

Expected result: the invalid mark is rejected before a result is stored, so an invalid percentage cannot be produced.

## Database design note

The percentage is intentionally derived rather than stored in the `results` table. This avoids redundant data and prevents a stored percentage becoming inconsistent if the mark or maximum available mark changes.

## Evidence to retain

Capture screenshots showing at least two results with different maximum marks and the automatically calculated percentage column.

For stronger evidence, include one manually checked calculation such as:

`60 / 80 × 100 = 75%`
