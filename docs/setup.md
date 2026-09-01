# Local Setup

## SCRUM-16 – PHP/MySQL foundation

The application requires PHP and MySQL. XAMPP can be used for local development on Windows.

### 1. Place the repository in the web-server directory

For XAMPP, this will normally be:

`C:\xampp\htdocs\student-assessment-progress-tracker`

### 2. Start Apache and MySQL

Open the XAMPP Control Panel and start both Apache and MySQL.

### 3. Create/import the database

Open phpMyAdmin:

`http://localhost/phpmyadmin`

Use the **Import** function and import:

`database/student_assessment.sql`

The SQL file creates the database:

`student_assessment_tracker`

and its required tables.

### 4. Database connection

The application connection is defined in:

`config/database.php`

The default local values are:

Host: `localhost`  
Port: `3306`  
Database: `student_assessment_tracker`  
Username: `root`  
Password: empty

These are typical XAMPP development defaults. If your MySQL configuration is different, change the appropriate environment variables or the local fallback values.

### 5. Open the application

Visit:

`http://localhost/student-assessment-progress-tracker/`

The Dashboard should load and show:

**Database connected**

It should also display counts for students, assessments and results.

### SCRUM-16 acceptance check

SCRUM-16 can move to Testing/Review once:

1. the project folders and base pages exist;
2. Apache/PHP can load the application;
3. MySQL is running;
4. the SCRUM-15 SQL schema has been imported;
5. `index.php` loads without a database error;
6. the Dashboard successfully retrieves record counts from MySQL.
