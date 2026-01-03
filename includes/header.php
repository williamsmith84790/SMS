<?php
// includes/header.php
require_once __DIR__ . '/../config.php';

// Fetch active urgent alert
$active_alerts = [];
$alert_sql = "SELECT * FROM urgent_alerts WHERE is_active = 1 ORDER BY created_at DESC";
$alert_result = $conn->query($alert_sql);
if ($alert_result && $alert_result->num_rows > 0) {
    while($row = $alert_result->fetch_assoc()) {
        $active_alerts[] = $row;
    }
}

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

// Helper to build menu tree
function build_menu_tree($items, $parentId = NULL) {
    $branch = [];
    foreach ($items as $item) {
        if ($item['parent_id'] == $parentId) {
            $children = build_menu_tree($items, $item['id']);
            if ($children) {
                $item['children'] = $children;
            }
            $branch[] = $item;
        }
    }
    return $branch;
}

// Fetch Header Menu Items
$menu_sql = "SELECT * FROM menu_items WHERE location = 'header' ORDER BY sort_order ASC";
$menu_result = $conn->query($menu_sql);
$raw_menu_items = [];
while ($row = $menu_result->fetch_assoc()) {
    $raw_menu_items[] = $row;
}
$header_menu_tree = build_menu_tree($raw_menu_items);

// Recursive Function to Render Menu
function render_menu_item($item, $level = 0) {
    $has_children = isset($item['children']) && count($item['children']) > 0;
    $url = htmlspecialchars($item['link']);
    $label = htmlspecialchars($item['label']);

    // Check if external link
    $target = (strpos($url, 'http') === 0) ? '_blank' : '_self';

    if ($has_children) {
        // Dropdown
        $dropdown_class = ($level === 0) ? 'nav-item dropdown' : 'dropdown-submenu';
        $link_class = ($level === 0) ? 'nav-link dropdown-toggle' : 'dropdown-item dropdown-toggle';

        echo '<li class="' . $dropdown_class . '">';
        echo '<a class="' . $link_class . '" href="' . $url . '" data-bs-toggle="dropdown">' . $label . '</a>';
        echo '<ul class="dropdown-menu shadow-lg border-0">';
        foreach ($item['children'] as $child) {
            render_menu_item($child, $level + 1);
        }
        echo '</ul>';
        echo '</li>';
    } else {
        // Standard Link
        if ($level === 0) {
            echo '<li class="nav-item"><a class="nav-link" href="' . $url . '" target="' . $target . '">' . $label . '</a></li>';
        } else {
            echo '<li><a class="dropdown-item" href="' . $url . '" target="' . $target . '">' . $label . '</a></li>';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? $page_title . ' - ' . $site_name : $site_name; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts: Merriweather (Serif) for headings, Open Sans (Sans) for body -->
    <link href="https://fonts.googleapis.com/css2?family=Merriweather:wght@400;700&family=Open+Sans:wght@400;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #002147; /* Deep Royal Blue */
            --secondary-color: #b30000; /* Crimson Red (Professional accent) */
            --accent-light: #e6e6e6; /* Silver/Light Grey */
            --text-color: #333333;
            --bg-light: #f4f6f9;
            --white: #ffffff;
        }

        body {
            font-family: 'Open Sans', sans-serif;
            background-color: var(--bg-light);
            color: var(--text-color);
            line-height: 1.6;
        }

        /* Wider Container */
        @media (min-width: 1400px) {
            .container { max-width: 1440px; }
        }
        @media (min-width: 1200px) and (max-width: 1399px) {
            .container { max-width: 1240px; }
        }

        h1, h2, h3, h4, h5, h6 {
            font-family: 'Merriweather', serif;
            color: var(--primary-color);
            font-weight: 700;
        }

        a { color: var(--primary-color); text-decoration: none; transition: 0.3s; }
        a:hover { color: var(--secondary-color); }

        /* --- Ticker (Notice Bar) --- */
        /* Updated: 3px gap below */
        .notice-wrapper { background-color: var(--secondary-color); padding: 0; border: none; margin-bottom: 3px; }
        .notice-bar { background-color: var(--secondary-color); color: white; font-size: 0.9rem; height: 40px; line-height: 40px; overflow: hidden; position: relative; margin: 0; border-radius: 0; }
        .notice-label { background: #8a0000; padding: 0 20px; position: absolute; z-index: 10; height: 100%; font-weight: bold; text-transform: uppercase; font-size: 0.8rem; display: flex; align-items: center; letter-spacing: 1px; }
        .marquee-container { overflow: hidden; white-space: nowrap; position: absolute; left: 130px; right: 0; top: 0; bottom: 0; }
        .marquee-content { display: inline-block; padding-left: 100%; animation: marquee 35s linear infinite; }
        .marquee-content span { display: inline-block; margin-right: 50px; font-weight: 600; }
        @keyframes marquee {
            0% { transform: translate(0, 0); }
            100% { transform: translate(-100%, 0); }
        }

        /* --- Top Bar --- */
        .top-bar { background-color: var(--primary-color); color: #fff; font-size: 0.85rem; padding: 10px 0; border-bottom: 3px solid var(--secondary-color); }
        .top-bar a { color: #e0e0e0; font-weight: 500; }
        .top-bar a:hover { color: #fff; text-decoration: underline; }
        .top-bar i { color: #ccc; margin-right: 6px; }
        .top-bar .social-icons a { margin-left: 15px; }
        .top-bar .divider { margin: 0 12px; color: rgba(255,255,255,0.3); }

        /* --- Main Navbar --- */
        .navbar-main { background-color: var(--white); padding: 0; box-shadow: 0 4px 12px rgba(0,0,0,0.05); }
        .navbar-brand { padding: 15px 0; }
        .navbar-brand img { height: 60px; width: auto; }
        .navbar-brand span { color: var(--primary-color); font-size: 1.6rem; font-family: 'Merriweather', serif; font-weight: 700; text-transform: uppercase; margin-left: 12px; vertical-align: middle; letter-spacing: -0.5px; }

        .navbar-nav .nav-link {
            color: var(--primary-color) !important;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 0.85rem;
            padding: 25px 18px !important;
            letter-spacing: 0.5px;
            border-bottom: 3px solid transparent;
        }
        .navbar-nav .nav-link:hover, .navbar-nav .nav-item.show .nav-link {
            color: var(--secondary-color) !important;
            border-bottom: 3px solid var(--secondary-color);
            background-color: rgba(0, 0, 0, 0.02);
        }
        .navbar-nav .nav-item.active .nav-link {
            color: var(--secondary-color) !important;
            border-bottom: 3px solid var(--secondary-color);
        }

        /* Dropdown Styling */
        .dropdown-menu { border-top: 3px solid var(--secondary-color); border-radius: 0 0 5px 5px; padding: 0; margin-top: 0; background: var(--white); }
        .dropdown-item { padding: 12px 20px; font-size: 0.9rem; color: #555; border-bottom: 1px solid #f0f0f0; transition: all 0.2s; font-family: 'Open Sans', sans-serif; font-weight: 600; }
        .dropdown-item:last-child { border-bottom: none; }
        .dropdown-item:hover { background-color: #f8f9fa; color: var(--secondary-color); padding-left: 25px; border-left: 3px solid var(--secondary-color); }

        /* Multilevel Dropdown */
        @media (min-width: 992px) {
            .navbar-expand-lg .navbar-nav .dropdown-menu { min-width: 240px; }
            .dropdown-submenu { position: relative; }
            .dropdown-submenu > .dropdown-menu { top: 0; left: 100%; margin-top: -3px; display: none; }
            .dropdown-submenu:hover > .dropdown-menu { display: block; }
            .dropdown-submenu > a::after { content: "\f0da"; font-family: "Font Awesome 5 Free"; font-weight: 900; float: right; margin-top: 4px; color: #ccc; }
            .dropdown-submenu:hover > a::after { color: var(--secondary-color); }
        }

        /* --- Common UI Components --- */
        .btn-primary { background-color: var(--primary-color); border-color: var(--primary-color); padding: 10px 25px; border-radius: 4px; font-weight: 600; text-transform: uppercase; font-size: 0.85rem; letter-spacing: 0.5px; }
        .btn-primary:hover { background-color: #00152e; border-color: #00152e; }

        .btn-warning { background-color: var(--secondary-color); border-color: var(--secondary-color); color: white; font-weight: 700; }
        .btn-warning:hover { background-color: #8a0000; border-color: #8a0000; color: white; }

        .card { border: none; border-radius: 8px; box-shadow: 0 5px 20px rgba(0,0,0,0.05); transition: transform 0.3s ease, box-shadow 0.3s ease; background: var(--white); }
        .card:hover { transform: translateY(-5px); box-shadow: 0 10px 30px rgba(0,0,0,0.1); }
        .card-header { background-color: var(--white); border-bottom: 1px solid #eee; padding: 20px; font-family: 'Merriweather', serif; font-weight: 700; color: var(--primary-color); }

        main { min-height: 60vh; padding: 0 0 50px 0; }
        .slider-img { height: 500px; object-fit: cover; }
        .section-title { position: relative; margin-bottom: 40px; }
        .section-title::after { content: ''; display: block; width: 60px; height: 4px; background: var(--secondary-color); margin-top: 15px; border-radius: 2px; }

        /* Mobile Adjustments */
        @media (max-width: 991px) {
            .top-bar { text-align: center; }
            .top-bar .d-flex { justify-content: center !important; flex-wrap: wrap; }
            .top-bar .contact-info, .top-bar .social-icons { margin-bottom: 5px; width: 100%; }
            .navbar-brand span { font-size: 1.2rem; }
            .navbar-nav .nav-link { padding: 12px 15px !important; border-bottom: 1px solid #eee; }
        }
    </style>
</head>
<body>

<!-- Urgent Alert Modal -->
<?php if (!empty($active_alerts) && basename($_SERVER['PHP_SELF']) == 'index.php'): ?>
<div class="modal fade show" id="urgentAlertModal" tabindex="-1" style="display: block; background: rgba(0,0,0,0.5); z-index: 2000;" onclick="document.getElementById('urgentAlertModal').style.display='none'">
  <div class="modal-dialog" onclick="event.stopPropagation()">
    <div class="modal-content border-0 shadow-lg">
      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title"><i class="fas fa-exclamation-triangle"></i> Urgent Alert</h5>
        <button type="button" class="btn-close btn-close-white" onclick="document.getElementById('urgentAlertModal').style.display='none'"></button>
      </div>
      <div class="modal-body p-4">
        <div class="list-group list-group-flush">
            <?php foreach($active_alerts as $index => $alert): ?>
            <div class="list-group-item border-0 text-start <?php echo $index > 0 ? 'border-top' : ''; ?> py-1">
                <div class="small">
                    <?php if(!empty($alert['link'])): ?>
                        <a href="<?php echo htmlspecialchars($alert['link']); ?>" class="text-dark text-decoration-underline"><?php echo nl2br(htmlspecialchars($alert['message'])); ?></a>
                    <?php else: ?>
                        <?php echo nl2br(htmlspecialchars($alert['message'])); ?>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
      </div>
    </div>
  </div>
</div>
<?php endif; ?>

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
                <?php if($social_links['facebook'] != '#'): ?><a href="<?php echo htmlspecialchars($social_links['facebook']); ?>" target="_blank"><i class="fab fa-facebook-f"></i></a><?php endif; ?>
                <?php if($social_links['twitter'] != '#'): ?><a href="<?php echo htmlspecialchars($social_links['twitter']); ?>" target="_blank"><i class="fab fa-twitter"></i></a><?php endif; ?>
                <?php if($social_links['linkedin'] != '#'): ?><a href="<?php echo htmlspecialchars($social_links['linkedin']); ?>" target="_blank"><i class="fab fa-linkedin-in"></i></a><?php endif; ?>
                <?php if($social_links['instagram'] != '#'): ?><a href="<?php echo htmlspecialchars($social_links['instagram']); ?>" target="_blank"><i class="fab fa-instagram"></i></a><?php endif; ?>
                <a href="<?php echo htmlspecialchars($apply_link); ?>" class="btn btn-warning btn-sm ms-3 rounded-pill px-3 shadow-sm">Apply Online</a>
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
            <div class="d-flex align-items-center">
                <span class="fa-stack fa-lg" style="font-size: 1.2rem; color: var(--primary-color);">
                  <i class="fas fa-circle fa-stack-2x" style="color: #e0e0e0;"></i>
                  <i class="fas fa-graduation-cap fa-stack-1x"></i>
                </span>
                <span><?php echo htmlspecialchars($site_name); ?></span>
            </div>
        <?php endif; ?>
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="mainNav">
      <ul class="navbar-nav ms-auto">
        <?php
        foreach ($header_menu_tree as $item) {
            render_menu_item($item);
        }
        ?>
      </ul>
    </div>
  </div>
</nav>

<!-- Notice Bar (Ticker) - Moved below navbar -->
<div class="notice-wrapper">
    <div class="container">
        <div class="notice-bar">
            <div class="notice-label">LATEST UPDATES</div>
            <div class="marquee-container">
                <div class="marquee-content">
                    <?php if ($ticker_result && $ticker_result->num_rows > 0): ?>
                        <?php while($item = $ticker_result->fetch_assoc()): ?>
                            <span>
                                <i class="fas fa-bullhorn" style="margin-right: 8px;"></i>
                                <?php if(!empty($item['link'])): ?>
                                    <a href="<?php echo htmlspecialchars($item['link']); ?>" class="text-white text-decoration-underline" target="_blank"><?php echo htmlspecialchars($item['content']); ?></a>
                                <?php else: ?>
                                    <?php echo htmlspecialchars($item['content']); ?>
                                <?php endif; ?>
                            </span>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <span>Welcome to <?php echo htmlspecialchars($site_name); ?>. Admissions are open!</span>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<main class="container">
