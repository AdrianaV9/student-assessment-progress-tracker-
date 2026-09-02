# SCRUM-31 Test Evidence – Improve Interface and Responsive Design

## Task

Refine the application's visual design, consistency, navigation and responsive behaviour so that the interface is clear and usable on common screen sizes.

## Changes implemented

The header and navigation have been refined for clearer visual hierarchy.

The active navigation section remains highlighted on related add, edit, view and delete pages.

A mobile navigation button is displayed on smaller screens.

The button exposes its open/closed state using `aria-expanded`.

The navigation can be closed using the Escape key.

A keyboard-accessible Skip to main content link has been added.

Focus states have been strengthened for navigation links, buttons and form controls.

Tables remain usable on narrow screens through horizontal scrolling.

Form actions and table actions adapt to narrower layouts.

Dashboard cards, search controls and page headings collapse cleanly at smaller widths.

No database or business-logic changes are introduced by SCRUM-31.

## Test 1 – Desktop navigation

Open the application at a normal desktop browser width.

Expected result: the full navigation is visible and the mobile Menu button is hidden.

## Test 2 – Active navigation state

Open `add_student.php`, `edit_student.php`, an assessment edit page and `edit_feedback.php`.

Expected result: the relevant Students, Assessments or Results navigation item remains highlighted.

## Test 3 – Mobile navigation

Open Chrome DevTools and switch to a narrow mobile viewport, or reduce the browser width below approximately 700px.

Expected result: the normal navigation is hidden and a Menu button appears.

Select Menu.

Expected result: navigation links become visible.

Select a navigation link.

Expected result: the selected page opens and the mobile menu closes.

## Test 4 – Escape key

Open the mobile navigation and press Escape.

Expected result: the navigation closes and keyboard focus returns to the Menu button.

## Test 5 – Dashboard responsiveness

At desktop, tablet and mobile widths, open the dashboard.

Expected result: summary cards reorganise from multiple columns to fewer columns and finally one column without overlapping content.

## Test 6 – Students page responsiveness

Open the Students page at a narrow width.

Expected result: search/filter controls stack appropriately and remain usable.

The View/Edit/Delete actions remain accessible.

## Test 7 – Tables

Open Students, Assessments, Results and Progress at a narrow width.

Expected result: wide tables can be horizontally scrolled inside their table area instead of breaking the page layout.

## Test 8 – Forms

Open Add Student, Edit Student, Add Assessment and Record Mark on a narrow screen.

Expected result: fields fit the viewport and action buttons remain usable.

## Test 9 – Keyboard focus

Use the Tab key through the main navigation and a form.

Expected result: focused interactive elements have a clear visible outline.

## Test 10 – Functional regression

Navigate through Dashboard, Students, Assessments, Results and Progress after the interface changes.

Expected result: previously tested application functionality continues to work.

## Evidence to retain

Capture one desktop screenshot and at least two responsive screenshots, for example the dashboard and student-management page at a mobile viewport.

A screenshot showing the mobile navigation open is particularly useful portfolio evidence.
