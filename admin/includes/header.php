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
    <style>
        .sidebar { min-height: 100vh; background: #343a40; color: white; }
        .sidebar a { color: rgba(255,255,255,.8); text-decoration: none; padding: 10px 15px; display: block; border-bottom: 1px solid rgba(255,255,255,0.1); }
        .sidebar a:hover, .sidebar a.active { background: #495057; color: white; }
        .sidebar i { width: 25px; text-align: center; margin-right: 5px; }
        .content { padding: 20px; }
    </style>
</head>
<body>

<div class="d-flex">
    <!-- Sidebar -->
    <div class="sidebar d-flex flex-column flex-shrink-0 p-3 text-white bg-dark" style="width: 250px;">
        <a href="dashboard.php" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-white text-decoration-none">
            <span class="fs-4">Admin Panel</span>
        </a>
        <hr>
        <ul class="nav nav-pills flex-column mb-auto">
            <li class="nav-item"><a href="dashboard.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active' : ''; ?>"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
            <li><a href="slider_list.php" class="nav-link <?php echo strpos($_SERVER['PHP_SELF'], 'slider') !== false ? 'active' : ''; ?>"><i class="fas fa-images"></i> Sliders</a></li>
            <li><a href="ticker_list.php" class="nav-link <?php echo strpos($_SERVER['PHP_SELF'], 'ticker') !== false ? 'active' : ''; ?>"><i class="fas fa-scroll"></i> News Ticker</a></li>
            <li><a href="alerts_list.php" class="nav-link <?php echo strpos($_SERVER['PHP_SELF'], 'alerts') !== false ? 'active' : ''; ?>"><i class="fas fa-exclamation-triangle"></i> Urgent Alerts</a></li>
            <li><a href="notices_list.php" class="nav-link <?php echo strpos($_SERVER['PHP_SELF'], 'notices') !== false ? 'active' : ''; ?>"><i class="fas fa-bullhorn"></i> Notices</a></li>
            <li><a href="pages_list.php" class="nav-link <?php echo strpos($_SERVER['PHP_SELF'], 'pages') !== false ? 'active' : ''; ?>"><i class="fas fa-file-alt"></i> Pages</a></li>
            <li><a href="faculty_list.php" class="nav-link <?php echo strpos($_SERVER['PHP_SELF'], 'faculty') !== false ? 'active' : ''; ?>"><i class="fas fa-chalkboard-teacher"></i> Faculty</a></li>
            <li><a href="albums_list.php" class="nav-link <?php echo strpos($_SERVER['PHP_SELF'], 'album') !== false || strpos($_SERVER['PHP_SELF'], 'gallery') !== false ? 'active' : ''; ?>"><i class="fas fa-camera"></i> Gallery</a></li>
            <li><a href="alumni_list.php" class="nav-link <?php echo strpos($_SERVER['PHP_SELF'], 'alumni') !== false ? 'active' : ''; ?>"><i class="fas fa-user-graduate"></i> Alumni</a></li>
            <li><a href="downloads_list.php" class="nav-link <?php echo strpos($_SERVER['PHP_SELF'], 'downloads') !== false ? 'active' : ''; ?>"><i class="fas fa-download"></i> Downloads</a></li>
            <li><a href="results_list.php" class="nav-link <?php echo strpos($_SERVER['PHP_SELF'], 'results') !== false ? 'active' : ''; ?>"><i class="fas fa-poll"></i> Results</a></li>
            <li class="mt-4"><a href="../index.php" target="_blank" class="nav-link text-warning"><i class="fas fa-external-link-alt"></i> View Site</a></li>
            <li><a href="logout.php" class="nav-link text-danger"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="flex-grow-1" style="max-height: 100vh; overflow-y: auto;">
        <nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom px-4">
            <div class="container-fluid">
                <span class="navbar-brand mb-0 h1"><?php echo isset($page_title) ? $page_title : 'Dashboard'; ?></span>
            </div>
        </nav>
        <div class="content">
            <?php if(isset($_SESSION['msg_success'])): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?php echo $_SESSION['msg_success']; unset($_SESSION['msg_success']); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>
            <?php if(isset($_SESSION['msg_error'])): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?php echo $_SESSION['msg_error']; unset($_SESSION['msg_error']); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>
