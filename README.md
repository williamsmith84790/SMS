# EduPortal (PHP Version)

This is a complete rebuild of the EduPortal project using **PHP** and **MySQL**, designed to run on **XAMPP**.

## Prerequisites

1.  **XAMPP**: Ensure you have XAMPP installed (includes Apache, PHP, and MySQL).
2.  **Web Browser**: To view the site.

## Installation

1.  **Project Location**:
    - Copy the entire project folder to your XAMPP `htdocs` directory (e.g., `C:\xampp\htdocs\edu_portal`).

2.  **Database Setup**:
    - Open **phpMyAdmin** (`http://localhost/phpmyadmin`).
    - Create a new database named `edu_portal`.
    - Click on the database name, then go to the **Import** tab.
    - Choose the `database.sql` file provided in this project and click **Go**.
    - This will create all the necessary tables (slider_images, notices, students_results, etc.).

3.  **Configuration**:
    - The project is configured to work with default XAMPP settings (User: `root`, Password: empty, Host: `localhost`).
    - If your MySQL configuration is different, edit `config.php` to match your credentials.

## Usage

1.  Start **Apache** and **MySQL** in the XAMPP Control Panel.
2.  Open your browser and navigate to:
    `http://localhost/edu_portal/index.php`

## Features

- **Home**: Dynamic slider, ticker news, and pinned notices.
- **Faculty**: List of faculty members with photos and bios.
- **Gallery**: Photo albums and video gallery.
- **Alumni**: Distinguished alumni records.
- **Downloads**: Categorized document downloads.
- **Results**: Student result search by Roll Number and Session.
- **Dynamic Pages**: About us, History, etc. (managed via database slugs).

## Admin / Data Entry

To add content (Faculty, Notices, Results, etc.), you will need to insert data into the MySQL database tables using phpMyAdmin or a separate admin tool.

*Note: The previous Django Admin panel is not available in this PHP version. Database management is handled directly via SQL/phpMyAdmin.*
