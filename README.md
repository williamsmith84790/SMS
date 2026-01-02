# Education Portal Setup Guide for XAMPP

This project is configured to work with the MySQL database provided by XAMPP.

## Prerequisites

1.  **Python 3.10+**: Ensure Python is installed on your system.
2.  **XAMPP**: Ensure XAMPP is installed and running (specifically the MySQL/MariaDB service).
3.  **Git**: To clone the repository (if you haven't already).

## Database Setup

1.  Open **phpMyAdmin** (usually at `http://localhost/phpmyadmin`).
2.  Create a new database named `edu_portal`.
    - Collation: `utf8mb4_general_ci` (recommended).

## Project Setup

1.  **Install Dependencies:**
    Open a terminal/command prompt in the project root directory and run:
    ```bash
    pip install -r requirements.txt
    ```
    *Note: Installing `mysqlclient` on Windows might require Visual C++ Build Tools or using a pre-compiled wheel. If you encounter issues, look for `mysqlclient` installation guides for your OS.*

2.  **Database Configuration:**
    The project uses the following default settings for MySQL (typical for XAMPP):
    - **Host**: `localhost`
    - **Port**: `3306`
    - **User**: `root`
    - **Password**: (empty)
    - **Database Name**: `edu_portal`

    If your XAMPP configuration is different (e.g., you have a password for root), you can override these using environment variables:
    - `DB_NAME`
    - `DB_USER`
    - `DB_PASSWORD`
    - `DB_HOST`
    - `DB_PORT`

    Example (PowerShell):
    ```powershell
    $env:DB_PASSWORD="yourpassword"
    python manage.py runserver
    ```

    Example (Bash/Git Bash):
    ```bash
    export DB_PASSWORD="yourpassword"
    python manage.py runserver
    ```

3.  **Run Migrations:**
    Create the database tables by running:
    ```bash
    python manage.py migrate
    ```

4.  **Create Superuser (Admin):**
    ```bash
    python manage.py createsuperuser
    ```

5.  **Run the Server:**
    ```bash
    python manage.py runserver
    ```
    Access the site at `http://127.0.0.1:8000`.

## Notes on PHP

While you are running XAMPP which includes PHP 8, this project is built with **Python/Django**. You do not need PHP to run this application, but you are using the MySQL server provided by your XAMPP installation.
