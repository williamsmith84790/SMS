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
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? $page_title . ' - ' . SITE_NAME : SITE_NAME; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        .ticker-container { background: #002147; color: white; overflow: hidden; height: 40px; }
        .vertical-ticker { height: 40px; overflow: hidden; position: relative; }
        .vertical-ticker ul { list-style: none; padding: 0; margin: 0; position: absolute; width: 100%; animation: scrollUp 10s linear infinite; }
        .vertical-ticker li { height: 40px; line-height: 40px; text-align: center; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        @keyframes scrollUp {
            0% { top: 100%; }
            100% { top: -100%; }
        }

        .navbar-brand img { height: 50px; }
        .footer { background: #333; color: white; padding: 20px 0; }
        .alert-modal { display: block; background: rgba(0,0,0,0.5); }

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
<div class="container-fluid bg-dark text-white">
    <div class="row">
        <div class="col-md-2 bg-primary text-center py-2 fw-bold">LATEST NEWS</div>
        <div class="col-md-10 vertical-ticker">
            <ul>
                <?php if ($ticker_result && $ticker_result->num_rows > 0): ?>
                    <?php while($item = $ticker_result->fetch_assoc()): ?>
                    <li><?php echo htmlspecialchars($item['content']); ?></li>
                    <?php endwhile; ?>
                <?php else: ?>
                    <li>Welcome to <?php echo SITE_NAME; ?></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</div>

<!-- Navigation -->
<nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm sticky-top">
  <div class="container">
    <a class="navbar-brand" href="index.php">
        <strong><?php echo SITE_NAME; ?></strong>
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="aboutDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">About</a>
            <ul class="dropdown-menu" aria-labelledby="aboutDropdown">
                <!-- Fetch pages dynamically ideally, but hardcoding for demo structure -->
                <li><a class="dropdown-item" href="page.php?slug=vision-mission">Vision & Mission</a></li>
                <li><a class="dropdown-item" href="page.php?slug=history">History</a></li>
                <li><a class="dropdown-item" href="page.php?slug=principal-message">Principal's Message</a></li>
            </ul>
        </li>
        <li class="nav-item"><a class="nav-link" href="faculty.php">Faculty</a></li>
        <li class="nav-item"><a class="nav-link" href="gallery.php">Gallery</a></li>
        <li class="nav-item"><a class="nav-link" href="alumni.php">Alumni</a></li>
        <li class="nav-item"><a class="nav-link" href="downloads.php">Downloads</a></li>
        <li class="nav-item"><a class="nav-link btn btn-primary text-white ms-2" href="results.php">Results</a></li>
      </ul>
    </div>
  </div>
</nav>

<main class="container my-4" style="min-height: 60vh;">
