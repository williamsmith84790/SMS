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

// Fetch Site Settings
$settings = [];
$settings_result = $conn->query("SELECT * FROM site_settings");
if ($settings_result) {
    while($row = $settings_result->fetch_assoc()) {
        $settings[$row['setting_key']] = $row['setting_value'];
    }
}
$site_logo = isset($settings['site_logo']) && !empty($settings['site_logo']) ? $settings['site_logo'] : null;
$site_name = isset($settings['site_name']) ? $settings['site_name'] : SITE_NAME;
$contact_phone = isset($settings['contact_phone']) ? $settings['contact_phone'] : '1800 000 0000';
$contact_email = isset($settings['contact_email']) ? $settings['contact_email'] : 'info@eduportal.org';
$apply_link = isset($settings['header_apply_link']) && !empty($settings['header_apply_link']) ? $settings['header_apply_link'] : 'downloads.php';

// Social Links
$social_links = [
    'facebook' => isset($settings['social_facebook']) ? $settings['social_facebook'] : '#',
    'twitter' => isset($settings['social_twitter']) ? $settings['social_twitter'] : '#',
    'instagram' => isset($settings['social_instagram']) ? $settings['social_instagram'] : '#',
    'linkedin' => isset($settings['social_linkedin']) ? $settings['social_linkedin'] : '#',
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? $page_title . ' - ' . $site_name : $site_name; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&family=Roboto:wght@500;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Open Sans', sans-serif; background-color: #f9f9f9; }

        /* Typography override for a more academic look */
        h1, h2, h3, h4, h5, h6 { font-family: 'Roboto', sans-serif; font-weight: 700; color: #333; }

        /* --- Ticker (Notice Bar) --- */
        .notice-bar { background-color: #cf142b; color: white; font-size: 0.9rem; height: 35px; line-height: 35px; overflow: hidden; position: relative; }
        .notice-label { background: #a30e1f; padding: 0 15px; position: absolute; z-index: 10; height: 100%; font-weight: bold; text-transform: uppercase; font-size: 0.8rem; display: flex; align-items: center; }
        .marquee-container { overflow: hidden; white-space: nowrap; position: absolute; left: 100px; right: 0; top: 0; bottom: 0; }
        .marquee-content { display: inline-block; padding-left: 100%; animation: marquee 30s linear infinite; }
        .marquee-content span { display: inline-block; margin-right: 40px; }
        @keyframes marquee {
            0% { transform: translate(0, 0); }
            100% { transform: translate(-100%, 0); }
        }

        /* --- Top Bar --- */
        .top-bar { background-color: #002147; color: #ccc; font-size: 0.85rem; padding: 8px 0; }
        .top-bar a { color: #ccc; text-decoration: none; transition: color 0.3s; }
        .top-bar a:hover { color: #fff; }
        .top-bar i { color: #fdbb00; margin-right: 5px; }
        .top-bar .social-icons a { margin-left: 10px; }
        .top-bar .divider { margin: 0 10px; color: #444; }

        /* --- Main Navbar --- */
        .navbar-main { background-color: #fff; padding: 15px 0; box-shadow: 0 2px 10px rgba(0,0,0,0.05); }
        .navbar-brand img { height: 50px; width: auto; }
        .navbar-brand span { color: #002147; font-size: 1.5rem; font-weight: 800; text-transform: uppercase; margin-left: 10px; vertical-align: middle; }

        .nav-link { color: #333 !important; font-weight: 600; text-transform: uppercase; font-size: 0.9rem; padding: 10px 15px !important; transition: all 0.3s; }
        .nav-link:hover, .nav-item.show .nav-link { color: #002147 !important; }
        .nav-item.active .nav-link { color: #002147 !important; }

        /* Dropdown Styling */
        .dropdown-menu { border: none; border-top: 3px solid #fdbb00; border-radius: 0; box-shadow: 0 5px 15px rgba(0,0,0,0.1); padding: 0; margin-top: 0; }
        .dropdown-item { padding: 10px 20px; font-size: 0.9rem; color: #555; border-bottom: 1px solid #eee; transition: all 0.2s; }
        .dropdown-item:last-child { border-bottom: none; }
        .dropdown-item:hover { background-color: #f8f9fa; color: #002147; padding-left: 25px; }

        /* Multilevel Dropdown */
        @media (min-width: 992px) {
            .navbar-expand-lg .navbar-nav .dropdown-menu { min-width: 220px; }
            .dropdown-submenu { position: relative; }
            .dropdown-submenu > .dropdown-menu { top: 0; left: 100%; margin-top: -3px; display: none; }
            .dropdown-submenu:hover > .dropdown-menu { display: block; }
            .dropdown-submenu > a::after { content: "\f0da"; font-family: "Font Awesome 5 Free"; font-weight: 900; float: right; margin-top: 4px; }
        }

        /* --- Global Styles --- */
        main { min-height: 60vh; padding: 40px 0; }
        .section-title { position: relative; margin-bottom: 30px; }
        .section-title::after { content: ''; display: block; width: 50px; height: 3px; background: #fdbb00; margin-top: 10px; }

        /* Mobile Adjustments */
        @media (max-width: 991px) {
            .top-bar { text-align: center; }
            .top-bar .d-flex { justify-content: center !important; flex-wrap: wrap; }
            .top-bar .contact-info, .top-bar .social-icons { margin-bottom: 5px; width: 100%; }
        }
    </style>
</head>
<body>

<!-- Urgent Alert Modal -->
<?php if ($active_alert): ?>
<div class="modal fade show" id="urgentAlertModal" tabindex="-1" style="display: block; background: rgba(0,0,0,0.5);">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title"><i class="fas fa-exclamation-triangle"></i> <?php echo htmlspecialchars($active_alert['title']); ?></h5>
        <button type="button" class="btn-close" onclick="document.getElementById('urgentAlertModal').style.display='none'"></button>
      </div>
      <div class="modal-body">
        <?php echo nl2br(htmlspecialchars($active_alert['message'])); ?>
      </div>
    </div>
  </div>
</div>
<?php endif; ?>

<!-- Notice Bar (Ticker) -->
<div class="notice-bar">
    <div class="notice-label">NEWS UPDATES</div>
    <div class="marquee-container">
        <div class="marquee-content">
            <?php if ($ticker_result && $ticker_result->num_rows > 0): ?>
                <?php while($item = $ticker_result->fetch_assoc()): ?>
                    <span><i class="fas fa-dot-circle small"></i> <?php echo htmlspecialchars($item['content']); ?></span>
                <?php endwhile; ?>
            <?php else: ?>
                <span>Welcome to <?php echo htmlspecialchars($site_name); ?>. Admissions are open for Fall 2023!</span>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Top Bar -->
<div class="top-bar">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center">
            <div class="contact-info">
                <a href="mailto:<?php echo htmlspecialchars($contact_email); ?>"><i class="fas fa-envelope"></i> <?php echo htmlspecialchars($contact_email); ?></a>
                <span class="divider">|</span>
                <a href="tel:<?php echo htmlspecialchars($contact_phone); ?>"><i class="fas fa-phone-alt"></i> <?php echo htmlspecialchars($contact_phone); ?></a>
            </div>
            <div class="social-icons">
                <a href="admin/login.php"><i class="fas fa-user-lock"></i> Staff Login</a>
                <span class="divider">|</span>
                <?php if($social_links['facebook'] != '#'): ?><a href="<?php echo htmlspecialchars($social_links['facebook']); ?>" target="_blank"><i class="fab fa-facebook-f"></i></a><?php endif; ?>
                <?php if($social_links['twitter'] != '#'): ?><a href="<?php echo htmlspecialchars($social_links['twitter']); ?>" target="_blank"><i class="fab fa-twitter"></i></a><?php endif; ?>
                <?php if($social_links['linkedin'] != '#'): ?><a href="<?php echo htmlspecialchars($social_links['linkedin']); ?>" target="_blank"><i class="fab fa-linkedin-in"></i></a><?php endif; ?>
                <?php if($social_links['instagram'] != '#'): ?><a href="<?php echo htmlspecialchars($social_links['instagram']); ?>" target="_blank"><i class="fab fa-instagram"></i></a><?php endif; ?>
                <a href="<?php echo htmlspecialchars($apply_link); ?>" class="btn btn-warning btn-sm text-dark ms-2 fw-bold" style="padding: 2px 10px; font-size: 0.75rem;">Apply Online</a>
            </div>
        </div>
    </div>
</div>

<!-- Main Navigation -->
<nav class="navbar navbar-expand-lg navbar-main sticky-top">
  <div class="container">
    <a class="navbar-brand" href="index.php">
        <?php if($site_logo): ?>
            <img src="<?php echo htmlspecialchars($site_logo); ?>" alt="Logo">
        <?php else: ?>
            <i class="fas fa-graduation-cap fa-2x text-warning"></i>
        <?php endif; ?>
        <span><?php echo htmlspecialchars($site_name); ?></span>
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="mainNav">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>

        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">About us</a>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="page.php?slug=vision-mission">Vision & Mission</a></li>
                <li><a class="dropdown-item" href="page.php?slug=history">History</a></li>
                <li><a class="dropdown-item" href="page.php?slug=principal-message">Principal's Message</a></li>
                <li><a class="dropdown-item" href="page.php?slug=core-team">Core Team</a></li>
            </ul>
        </li>

        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">Administration</a>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="page.php?slug=principal-office">Principal Office</a></li>
                <li><a class="dropdown-item" href="page.php?slug=vice-principal-office">Vice Principal Office</a></li>
                <li><a class="dropdown-item" href="page.php?slug=controller-office">Controller Office</a></li>
                <li><a class="dropdown-item" href="page.php?slug=student-affairs">Student Affairs</a></li>
            </ul>
        </li>

        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">Academics</a>
            <ul class="dropdown-menu">
                <li class="dropdown-submenu">
                    <a class="dropdown-item" href="#">Programs</a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="page.php?slug=program-intermediate">Intermediate</a></li>
                        <li><a class="dropdown-item" href="page.php?slug=program-bs-4ydp">BS-4YDP</a></li>
                    </ul>
                </li>
                <li><a class="dropdown-item" href="faculty.php">Faculty</a></li>
            </ul>
        </li>

        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">Admissions</a>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="downloads.php">Intermediate (Prospectus)</a></li>
                <li><a class="dropdown-item" href="downloads.php">BS-4YDP (Prospectus)</a></li>
            </ul>
        </li>

        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">Life at Campus</a>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="page.php?slug=facilities">Facilities</a></li>
                <li class="dropdown-submenu">
                    <a class="dropdown-item" href="#">Societies</a>
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

        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">Alumni</a>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="alumni.php">Our Best Graduates</a></li>
                <li><a class="dropdown-item" href="page.php?slug=success-stories">Success Stories</a></li>
                <li><a class="dropdown-item" href="page.php?slug=donate">Donate</a></li>
            </ul>
        </li>

        <li class="nav-item"><a class="nav-link" href="events.php">Events</a></li>
        <li class="nav-item"><a class="nav-link" href="contact.php">Contact</a></li>
        <li class="nav-item"><a class="nav-link" href="results.php"><i class="fas fa-search"></i> Results</a></li>
      </ul>
    </div>
  </div>
</nav>

<main class="container">
