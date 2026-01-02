<?php
// includes/header.php
require_once __DIR__ . '/../config.php';

// Fetch active urgent alert
$alert_sql = "SELECT * FROM urgent_alerts WHERE is_active = 1 ORDER BY created_at DESC LIMIT 1";
$alert_result = $conn->query($alert_sql);
$active_alert = ($alert_result && $alert_result->num_rows > 0) ? $alert_result->fetch_assoc() : null;

// Fetch ticker items
$ticker_sql = "SELECT * FROM ticker_items WHERE is_active = 1 ORDER BY created_at DESC LIMIT 10";
$ticker_result = $conn->query($ticker_sql);

// Fetch Site Settings (Logo, etc)
$settings = [];
$settings_result = $conn->query("SELECT * FROM site_settings");
if ($settings_result) {
    while($row = $settings_result->fetch_assoc()) {
        $settings[$row['setting_key']] = $row['setting_value'];
    }
}
$site_logo = isset($settings['site_logo']) && !empty($settings['site_logo']) ? $settings['site_logo'] : null;
$site_name = isset($settings['site_name']) ? $settings['site_name'] : SITE_NAME;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? $page_title . ' - ' . $site_name : $site_name; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }

        /* Ticker Styles (Right to Left) */
        .ticker-container { background: #002147; color: white; overflow: hidden; height: 40px; display: flex; align-items: center; }
        .ticker-title { background: #cf142b; color: white; padding: 0 20px; height: 100%; display: flex; align-items: center; font-weight: bold; z-index: 10; }
        .ticker-wrap { width: 100%; overflow: hidden; white-space: nowrap; }
        .ticker-move { display: inline-block; animation: ticker-h 30s linear infinite; padding-left: 100%; }
        .ticker-item { display: inline-block; padding: 0 2rem; color: #fff; }
        @keyframes ticker-h {
            0% { transform: translate3d(0, 0, 0); }
            100% { transform: translate3d(-100%, 0, 0); }
        }

        .navbar-brand img { height: 60px; width: auto; max-width: 200px; object-fit: contain; }
        .footer { background: #333; color: white; padding: 20px 0; }
        .alert-modal { display: block; background: rgba(0,0,0,0.5); }

        /* Dropdown Hover */
        @media all and (min-width: 992px) {
            .navbar .nav-item .dropdown-menu{ display: none; }
            .navbar .nav-item:hover .dropdown-menu{ display: block; margin-top: 0; }
            .navbar .nav-item .dropdown-menu{ margin-top:0; }
        }

        /* Submenu styles */
        .dropdown-submenu { position: relative; }
        .dropdown-submenu>.dropdown-menu { top: 0; left: 100%; margin-top: -6px; margin-left: -1px; -webkit-border-radius: 0 6px 6px 6px; -moz-border-radius: 0 6px 6px 6px; border-radius: 0 6px 6px 6px; }
        .dropdown-submenu:hover>.dropdown-menu { display: block; }
        .dropdown-submenu>a:after { display: block; content: " "; float: right; width: 0; height: 0; border-color: transparent; border-style: solid; border-width: 5px 0 5px 5px; border-left-color: #ccc; margin-top: 5px; margin-right: -10px; }
        .dropdown-submenu:hover>a:after { border-left-color: #fff; }
        .dropdown-submenu.pull-left { float: none; }
        .dropdown-submenu.pull-left>.dropdown-menu { left: -100%; margin-left: 10px; -webkit-border-radius: 6px 0 6px 6px; -moz-border-radius: 6px 0 6px 6px; border-radius: 6px 0 6px 6px; }

        /* Custom Styles */
        .slider-img { height: 500px; object-fit: cover; }
        .card-img-top-faculty { height: 300px; object-fit: cover; }
        .gallery-cover { height: 250px; object-fit: cover; }
    </style>
</head>
<body>

<!-- Urgent Alert Modal -->
<?php if ($active_alert): ?>
<div class="modal fade show" id="urgentAlertModal" tabindex="-1" aria-labelledby="urgentAlertLabel" aria-hidden="true" style="display: block; background: rgba(0,0,0,0.5);">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title" id="urgentAlertLabel"><i class="fas fa-exclamation-triangle"></i> <?php echo htmlspecialchars($active_alert['title']); ?></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onclick="document.getElementById('urgentAlertModal').style.display='none'"></button>
      </div>
      <div class="modal-body">
        <?php echo nl2br(htmlspecialchars($active_alert['message'])); ?>
      </div>
    </div>
  </div>
</div>
<?php endif; ?>

<!-- Top Bar / Ticker -->
<div class="ticker-container">
    <div class="ticker-title">LATEST NEWS</div>
    <div class="ticker-wrap">
        <div class="ticker-move">
            <?php if ($ticker_result && $ticker_result->num_rows > 0): ?>
                <?php while($item = $ticker_result->fetch_assoc()): ?>
                <div class="ticker-item"><i class="fas fa-star text-warning small"></i> <?php echo htmlspecialchars($item['content']); ?></div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="ticker-item">Welcome to <?php echo htmlspecialchars($site_name); ?></div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Navigation -->
<nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm sticky-top py-3">
  <div class="container">
    <a class="navbar-brand d-flex align-items-center" href="index.php">
        <?php if($site_logo): ?>
            <img src="<?php echo htmlspecialchars($site_logo); ?>" alt="Logo" class="me-2">
        <?php endif; ?>
        <span class="d-none d-md-block fw-bold text-primary fs-4"><?php echo htmlspecialchars($site_name); ?></span>
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto fw-semibold">
        <!-- Home -->
        <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>

        <!-- About us -->
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">About us</a>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="page.php?slug=vision-mission">Vision & Mission</a></li>
                <li><a class="dropdown-item" href="page.php?slug=history">History</a></li>
                <li><a class="dropdown-item" href="page.php?slug=principal-message">Principal's Message</a></li>
                <li><a class="dropdown-item" href="page.php?slug=core-team">Core Team</a></li>
            </ul>
        </li>

        <!-- Administration -->
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">Administration</a>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="page.php?slug=principal-office">Principal Office</a></li>
                <li><a class="dropdown-item" href="page.php?slug=vice-principal-office">Vice Principal Office</a></li>
                <li><a class="dropdown-item" href="page.php?slug=controller-office">Controller Office</a></li>
                <li><a class="dropdown-item" href="page.php?slug=student-affairs">Student Affairs Office</a></li>
            </ul>
        </li>

        <!-- Academics -->
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">Academics</a>
            <ul class="dropdown-menu">
                <li class="dropdown-submenu">
                    <a class="dropdown-item dropdown-toggle" href="#">Programs</a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="page.php?slug=program-intermediate">Intermediate</a></li>
                        <li><a class="dropdown-item" href="page.php?slug=program-bs-4ydp">BS-4YDP</a></li>
                    </ul>
                </li>
                <li><a class="dropdown-item" href="faculty.php">Faculty</a></li>
            </ul>
        </li>

        <!-- Admissions -->
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">Admissions</a>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="downloads.php">Intermediate (Prospectus)</a></li>
                <li><a class="dropdown-item" href="downloads.php">BS-4YDP (Prospectus)</a></li>
            </ul>
        </li>

        <!-- Life at Campus -->
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">Life at Campus</a>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="page.php?slug=facilities">Facilities</a></li>
                <li class="dropdown-submenu">
                    <a class="dropdown-item dropdown-toggle" href="#">Societies</a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="page.php?slug=college-societies">College Societies</a></li>
                        <li><a class="dropdown-item" href="page.php?slug=girls-guide">Girls Guide Association</a></li>
                    </ul>
                </li>
                <li><a class="dropdown-item" href="page.php?slug=co-curricular">Co-curricular Activities</a></li>
                <li><a class="dropdown-item" href="page.php?slug=hostel">Hostel</a></li>
                <li><a class="dropdown-item" href="page.php?slug=library">Library</a></li>
                <li><a class="dropdown-item" href="page.php?slug=career-counselling">Career Counselling</a></li>
            </ul>
        </li>

        <!-- Alumni -->
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">Alumni</a>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="alumni.php">Our Best Graduates</a></li>
                <li><a class="dropdown-item" href="page.php?slug=success-stories">Success Stories</a></li>
                <li><a class="dropdown-item" href="page.php?slug=donate">Donate</a></li>
            </ul>
        </li>

        <!-- Contact & Events -->
        <li class="nav-item"><a class="nav-link" href="contact.php">Contact us</a></li>
        <li class="nav-item"><a class="nav-link" href="events.php">Events</a></li>
      </ul>
    </div>
  </div>
</nav>

<main class="container my-4" style="min-height: 60vh;">
