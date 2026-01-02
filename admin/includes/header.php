<?php
require_once '../config.php';
require_once 'auth_check.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - <?php echo SITE_NAME; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- Summernote CSS (Rich Text Editor) -->
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Merriweather:wght@700&family=Open+Sans:wght@400;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #002147;
            --secondary-color: #b30000;
        }
        body { font-family: 'Open Sans', sans-serif; background-color: #f4f6f9; }

        .sidebar { min-height: 100vh; background: var(--primary-color); color: white; box-shadow: 2px 0 10px rgba(0,0,0,0.1); }
        .sidebar .brand { font-family: 'Merriweather', serif; font-size: 1.2rem; padding: 20px; border-bottom: 1px solid rgba(255,255,255,0.1); display: block; color: #fff; text-decoration: none; }
        .sidebar a.nav-link { color: rgba(255,255,255,.8); text-decoration: none; padding: 12px 20px; display: block; border-bottom: 1px solid rgba(255,255,255,0.05); transition: 0.2s; font-size: 0.9rem; }
        .sidebar a.nav-link:hover, .sidebar a.nav-link.active { background: rgba(255,255,255,0.1); color: #fff; border-left: 4px solid var(--secondary-color); padding-left: 16px; }
        .sidebar i { width: 25px; text-align: center; margin-right: 10px; opacity: 0.8; }

        .content { padding: 30px; }

        .card { border: none; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); }
        .card-header { background-color: #fff; border-bottom: 1px solid #eee; padding: 15px 20px; font-weight: bold; color: var(--primary-color); border-radius: 8px 8px 0 0 !important; }

        .btn-primary { background-color: var(--primary-color); border-color: var(--primary-color); }
        .btn-primary:hover { background-color: #00152e; border-color: #00152e; }

        .table thead th { background-color: #f8f9fa; border-bottom: 2px solid #eee; color: #555; font-weight: 600; text-transform: uppercase; font-size: 0.8rem; }
    </style>
</head>
<body>

<div class="d-flex">
    <!-- Sidebar -->
    <div class="sidebar d-flex flex-column flex-shrink-0 text-white" style="width: 260px;">
        <a href="dashboard.php" class="brand">
            <i class="fas fa-graduation-cap text-danger"></i> <?php echo SITE_NAME; ?> <small style="font-size: 0.7rem; display: block; opacity: 0.6; margin-top: 5px;">ADMIN PANEL</small>
        </a>
        <ul class="nav nav-pills flex-column mb-auto">
            <li class="nav-item"><a href="dashboard.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active' : ''; ?>"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
            <li><a href="slider_list.php" class="nav-link <?php echo strpos($_SERVER['PHP_SELF'], 'slider') !== false ? 'active' : ''; ?>"><i class="fas fa-images"></i> Sliders</a></li>
            <li><a href="menus.php" class="nav-link <?php echo strpos($_SERVER['PHP_SELF'], 'menu') !== false ? 'active' : ''; ?>"><i class="fas fa-bars"></i> Menus</a></li>
            <li><a href="ticker_list.php" class="nav-link <?php echo strpos($_SERVER['PHP_SELF'], 'ticker') !== false ? 'active' : ''; ?>"><i class="fas fa-scroll"></i> News Ticker</a></li>
            <li><a href="alerts_list.php" class="nav-link <?php echo strpos($_SERVER['PHP_SELF'], 'alerts') !== false ? 'active' : ''; ?>"><i class="fas fa-exclamation-triangle"></i> Urgent Alerts</a></li>
            <li><a href="notices_list.php" class="nav-link <?php echo strpos($_SERVER['PHP_SELF'], 'notices') !== false ? 'active' : ''; ?>"><i class="fas fa-bullhorn"></i> Notices</a></li>
            <li><a href="pages_list.php" class="nav-link <?php echo strpos($_SERVER['PHP_SELF'], 'pages') !== false ? 'active' : ''; ?>"><i class="fas fa-file-alt"></i> Pages</a></li>
            <li><a href="faculty_list.php" class="nav-link <?php echo strpos($_SERVER['PHP_SELF'], 'faculty') !== false ? 'active' : ''; ?>"><i class="fas fa-chalkboard-teacher"></i> Faculty</a></li>
            <li><a href="albums_list.php" class="nav-link <?php echo strpos($_SERVER['PHP_SELF'], 'album') !== false || strpos($_SERVER['PHP_SELF'], 'gallery') !== false ? 'active' : ''; ?>"><i class="fas fa-camera"></i> Gallery</a></li>
            <li><a href="alumni_list.php" class="nav-link <?php echo strpos($_SERVER['PHP_SELF'], 'alumni') !== false ? 'active' : ''; ?>"><i class="fas fa-user-graduate"></i> Alumni</a></li>
            <li><a href="downloads_list.php" class="nav-link <?php echo strpos($_SERVER['PHP_SELF'], 'downloads') !== false ? 'active' : ''; ?>"><i class="fas fa-download"></i> Downloads</a></li>
            <li><a href="results_list.php" class="nav-link <?php echo strpos($_SERVER['PHP_SELF'], 'results') !== false ? 'active' : ''; ?>"><i class="fas fa-poll"></i> Results</a></li>
            <li><a href="settings.php" class="nav-link <?php echo strpos($_SERVER['PHP_SELF'], 'settings') !== false ? 'active' : ''; ?>"><i class="fas fa-cogs"></i> Settings</a></li>
            <li class="mt-4"><a href="../index.php" target="_blank" class="nav-link text-warning"><i class="fas fa-external-link-alt"></i> View Site</a></li>
            <li><a href="logout.php" class="nav-link text-danger"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="flex-grow-1" style="max-height: 100vh; overflow-y: auto;">
        <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom px-4 shadow-sm sticky-top">
            <div class="container-fluid">
                <span class="navbar-brand mb-0 h1" style="font-family: 'Merriweather', serif; font-size: 1.1rem; color: #333;"><?php echo isset($page_title) ? $page_title : 'Dashboard'; ?></span>
                <div class="ms-auto">
                    <span class="text-muted small me-2">Logged in as <strong><?php echo $_SESSION['admin_username'] ?? 'Admin'; ?></strong></span>
                    <i class="fas fa-user-circle fa-lg text-secondary"></i>
                </div>
            </div>
        </nav>
        <div class="content">
            <?php if(isset($_SESSION['msg_success'])): ?>
                <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                    <i class="fas fa-check-circle me-2"></i> <?php echo $_SESSION['msg_success']; unset($_SESSION['msg_success']); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>
            <?php if(isset($_SESSION['msg_error'])): ?>
                <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i> <?php echo $_SESSION['msg_error']; unset($_SESSION['msg_error']); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>
