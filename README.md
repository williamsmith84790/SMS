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

## Admin Panel

The project includes a comprehensive Admin Panel to manage all website content.

- **URL**: `http://localhost/edu_portal/admin/login.php`
- **Default Username**: `admin`
- **Default Password**: `password`

### Features
- **Dashboard**: Quick stats overview.
- **Sliders & Ticker**: Manage homepage visuals and scrolling news.
- **Content Management**: Create and edit pages, notices (with Rich Text Editor), and urgent alerts.
- **Faculty & Alumni**: Manage staff and student profiles.
- **Gallery**: Create albums and bulk upload photos.
- **Downloads**: Organize files into categories.
- **Results**: Upload and manage student exam results.

### Note on File Uploads
Ensure the `media/` directory and its subdirectories are writable by the web server. The application will attempt to create them if they don't exist.
