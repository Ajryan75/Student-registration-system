# University Student Registration System

A beginner-friendly student registration web application built with PHP, MySQL, HTML, and CSS.

## Features

- Register a student with name, email, course, and year of study
- Validate required fields and email format
- Prevent duplicate email addresses
- Save student records in MySQL
- Display registered students in a table
- Use prepared statements and HTML escaping

## Requirements

- XAMPP (Apache and MySQL/MariaDB)
- A web browser
- VS Code (recommended)

## Run locally

1. Start Apache and MySQL from the XAMPP Control Panel.
2. Copy this project folder into XAMPP's `htdocs` directory.
   - Windows: `C:\\xampp\\htdocs\\student-registration-system`
   - macOS: `/Applications/XAMPP/htdocs/student-registration-system`
3. Open phpMyAdmin at `http://localhost/phpmyadmin`.
4. Select **Import**, choose `database.sql`, and run the import.
5. If your database has a password, update `db.php` with your local database credentials.
6. Visit `http://localhost/student-registration-system/`.

## Project files

- `index.php` — form, validation, insert, and student list
- `db.php` — connection to the database
- `database.sql` — creates the database and students table
- `style.css` — page styling
- `.gitignore` — excludes local and editor-specific files

## Notes

This is a learning project for local development. Before using it for real student data, add authentication and authorization, CSRF protection, stronger operational security, backups, and a privacy-compliant deployment. Do not commit real student records or passwords to GitHub.
