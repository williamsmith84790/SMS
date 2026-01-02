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

        .sidebar { min-height: 100vh; background: var(--primary-color); color: white; box-shadow: 2px 0 10px rgba(0,0,0,0.1); width: 260px; transition: 0.3s; }
        .sidebar .brand { font-family: 'Merriweather', serif; font-size: 1.2rem; padding: 20px; border-bottom: 1px solid rgba(255,255,255,0.1); display: block; color: #fff; text-decoration: none; }
        .sidebar a.nav-link { color: rgba(255,255,255,.8); text-decoration: none; padding: 12px 20px; display: block; border-bottom: 1px solid rgba(255,255,255,0.05); transition: 0.2s; font-size: 0.9rem; }
        .sidebar a.nav-link:hover, .sidebar a.nav-link.active { background: rgba(255,255,255,0.1); color: #fff; border-left: 4px solid var(--secondary-color); padding-left: 16px; }

        @media (max-width: 768px) {
            .sidebar { position: fixed; left: -260px; z-index: 1050; height: 100%; }
            .sidebar.active { left: 0; }
            .overlay { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1040; }
            .overlay.active { display: block; }
        }
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

<div class="overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>

<div class="d-flex">
    <!-- Sidebar -->
    <div class="sidebar d-flex flex-column flex-shrink-0 text-white" id="adminSidebar">
        <div class="d-flex justify-content-between align-items-center p-3 border-bottom border-secondary d-md-none">
            <span class="brand mb-0 p-0 border-0">Menu</span>
            <button class="btn btn-sm btn-dark" onclick="toggleSidebar()"><i class="fas fa-times"></i></button>
        </div>
        <a href="dashboard.php" class="brand d-none d-md-block">
            <i class="fas fa-graduation-cap text-danger"></i> <?php echo SITE_NAME; ?> <small style="font-size: 0.7rem; display: block; opacity: 0.6; margin-top: 5px;">ADMIN PANEL</small>
        </a>
        <ul class="nav nav-pills flex-column mb-auto mt-3 mt-md-0">
            <li class="nav-item"><a href="dashboard.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active' : ''; ?>"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>

            <?php if(has_permission('sliders')): ?>
            <li><a href="slider_list.php" class="nav-link <?php echo strpos($_SERVER['PHP_SELF'], 'slider') !== false ? 'active' : ''; ?>"><i class="fas fa-images"></i> Sliders</a></li>
            <?php endif; ?>

            <?php if(has_permission('menus')): ?>
            <li><a href="menus.php" class="nav-link <?php echo strpos($_SERVER['PHP_SELF'], 'menu') !== false ? 'active' : ''; ?>"><i class="fas fa-bars"></i> Menus</a></li>
            <?php endif; ?>

            <?php if(has_permission('news_ticker')): ?>
            <li><a href="ticker_list.php" class="nav-link <?php echo strpos($_SERVER['PHP_SELF'], 'ticker') !== false ? 'active' : ''; ?>"><i class="fas fa-scroll"></i> News Ticker</a></li>
            <?php endif; ?>

            <?php if(has_permission('events')): ?>
            <li><a href="events_list.php" class="nav-link <?php echo strpos($_SERVER['PHP_SELF'], 'events') !== false ? 'active' : ''; ?>"><i class="fas fa-calendar-alt"></i> Events</a></li>
            <?php endif; ?>

            <?php if(has_permission('urgent_alerts')): ?>
            <li><a href="alerts_list.php" class="nav-link <?php echo strpos($_SERVER['PHP_SELF'], 'alerts') !== false ? 'active' : ''; ?>"><i class="fas fa-exclamation-triangle"></i> Urgent Alerts</a></li>
            <?php endif; ?>

            <?php if(has_permission('notices')): ?>
            <li><a href="notices_list.php" class="nav-link <?php echo strpos($_SERVER['PHP_SELF'], 'notices') !== false ? 'active' : ''; ?>"><i class="fas fa-bullhorn"></i> Notices</a></li>
            <?php endif; ?>

            <?php if(has_permission('pages')): ?>
            <li><a href="pages_list.php" class="nav-link <?php echo strpos($_SERVER['PHP_SELF'], 'pages') !== false ? 'active' : ''; ?>"><i class="fas fa-file-alt"></i> Pages</a></li>
            <?php endif; ?>

            <?php if(has_permission('faculty')): ?>
            <li><a href="faculty_list.php" class="nav-link <?php echo strpos($_SERVER['PHP_SELF'], 'faculty') !== false ? 'active' : ''; ?>"><i class="fas fa-chalkboard-teacher"></i> Faculty</a></li>
            <?php endif; ?>

            <?php if(has_permission('gallery')): ?>
            <li><a href="albums_list.php" class="nav-link <?php echo strpos($_SERVER['PHP_SELF'], 'album') !== false || strpos($_SERVER['PHP_SELF'], 'gallery') !== false ? 'active' : ''; ?>"><i class="fas fa-camera"></i> Gallery</a></li>
            <?php endif; ?>

            <?php if(has_permission('alumni')): ?>
            <li><a href="alumni_list.php" class="nav-link <?php echo strpos($_SERVER['PHP_SELF'], 'alumni') !== false ? 'active' : ''; ?>"><i class="fas fa-user-graduate"></i> Alumni</a></li>
            <?php endif; ?>

            <?php if(has_permission('downloads')): ?>
            <li><a href="downloads_list.php" class="nav-link <?php echo strpos($_SERVER['PHP_SELF'], 'downloads') !== false ? 'active' : ''; ?>"><i class="fas fa-download"></i> Downloads</a></li>
            <?php endif; ?>

            <?php if(has_permission('results')): ?>
            <li><a href="results_list.php" class="nav-link <?php echo strpos($_SERVER['PHP_SELF'], 'results') !== false ? 'active' : ''; ?>"><i class="fas fa-poll"></i> Results</a></li>
            <?php endif; ?>

            <?php if(has_permission('settings')): ?>
            <li><a href="settings.php" class="nav-link <?php echo strpos($_SERVER['PHP_SELF'], 'settings') !== false ? 'active' : ''; ?>"><i class="fas fa-cogs"></i> Settings</a></li>
            <li><a href="stats_list.php" class="nav-link <?php echo strpos($_SERVER['PHP_SELF'], 'stats') !== false ? 'active' : ''; ?>"><i class="fas fa-chart-bar"></i> Stats Badges</a></li>
            <?php endif; ?>

            <?php if(has_permission('manage_users')): ?>
            <li><a href="users_list.php" class="nav-link <?php echo strpos($_SERVER['PHP_SELF'], 'users') !== false ? 'active' : ''; ?>"><i class="fas fa-users-cog"></i> Users</a></li>
            <?php endif; ?>

            <li class="mt-4"><a href="../index.php" target="_blank" class="nav-link text-warning"><i class="fas fa-external-link-alt"></i> View Site</a></li>
            <li><a href="logout.php" class="nav-link text-danger"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="flex-grow-1" style="max-height: 100vh; overflow-y: auto;">
        <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom px-4 shadow-sm sticky-top">
            <div class="container-fluid">
                <div class="d-flex align-items-center">
                    <button class="btn btn-outline-primary me-3 d-md-none" onclick="toggleSidebar()">
                        <i class="fas fa-bars"></i>
                    </button>
                    <span class="navbar-brand mb-0 h1" style="font-family: 'Merriweather', serif; font-size: 1.1rem; color: #333;"><?php echo isset($page_title) ? $page_title : 'Dashboard'; ?></span>
                </div>
                <div class="ms-auto dropdown">
                    <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle" id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">
                        <span class="text-muted small me-2">Logged in as <strong><?php echo $_SESSION['admin_username'] ?? 'Admin'; ?></strong></span>
                        <i class="fas fa-user-circle fa-lg text-secondary"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end text-small shadow" aria-labelledby="dropdownUser1">
                        <li><a class="dropdown-item" href="profile.php">Profile</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="logout.php">Sign out</a></li>
                    </ul>
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
            <!-- Scripts needed for the dropdown -->
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
            <script>
                function toggleSidebar() {
                    const sidebar = document.getElementById('adminSidebar');
                    const overlay = document.getElementById('sidebarOverlay');
                    sidebar.classList.toggle('active');
                    overlay.classList.toggle('active');
                }
            </script>
