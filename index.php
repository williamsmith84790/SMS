<?php
$page_title = "Home";
require_once 'includes/header.php';

// Fetch Active Slider Images
$slider_sql = "SELECT * FROM slider_images WHERE is_active = 1 ORDER BY `order` ASC";
$slider_result = $conn->query($slider_sql);

// Fetch Latest Notices
$notices_sql = "SELECT * FROM notices ORDER BY is_pinned DESC, date_posted DESC LIMIT 5";
$notices_result = $conn->query($notices_sql);

// Fetch Stats
$stats_sql = "SELECT * FROM site_stats ORDER BY sort_order ASC";
$stats_result = $conn->query($stats_sql);

// Fetch Events
$events_sql = "SELECT * FROM events ORDER BY date DESC LIMIT 4";
$events_result = $conn->query($events_sql);

// Fetch Gallery Albums
$gallery_sql = "SELECT * FROM gallery_albums ORDER BY created_at DESC LIMIT 4";
$gallery_result = $conn->query($gallery_sql);

// Feature Card Data
$f1_title = isset($settings['feature_1_title']) ? $settings['feature_1_title'] : 'Intermediate Program';
$f1_text = isset($settings['feature_1_text']) ? $settings['feature_1_text'] : 'Comprehensive F.Sc, F.A, and I.Com programs designed to build a strong academic foundation.';
$f1_link = isset($settings['feature_1_link']) ? $settings['feature_1_link'] : 'page.php?slug=program-intermediate';
$f1_img = isset($settings['feature_1_image']) && !empty($settings['feature_1_image']) ? $settings['feature_1_image'] : "https://via.placeholder.com/600x400/002147/ffffff?text=" . urlencode($f1_title);

$f2_title = isset($settings['feature_2_title']) ? $settings['feature_2_title'] : 'BS-4YDP Program';
$f2_text = isset($settings['feature_2_text']) ? $settings['feature_2_text'] : 'Four-year degree programs offering in-depth specialization and research opportunities.';
$f2_link = isset($settings['feature_2_link']) ? $settings['feature_2_link'] : 'page.php?slug=program-bs-4ydp';
$f2_img = isset($settings['feature_2_image']) && !empty($settings['feature_2_image']) ? $settings['feature_2_image'] : "https://via.placeholder.com/600x400/b30000/ffffff?text=" . urlencode($f2_title);
?>

<style>
    /* Stats Section */
    .stat-card { text-align: center; padding: 30px 20px; background: #fff; border: 1px solid #eee; transition: 0.3s; height: 100%; }
    .stat-card:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.05); border-color: var(--secondary-color); }
    .stat-card i { font-size: 2.5rem; color: #777; margin-bottom: 20px; display: block; }
    .stat-card .count { font-size: 2rem; font-weight: 700; color: #000; margin-bottom: 5px; display: block; }
    .stat-card .label { color: #666; font-size: 0.95rem; font-weight: 500; }

    /* Events Section */
    .event-card { border: none; box-shadow: 0 5px 15px rgba(0,0,0,0.05); transition: 0.3s; overflow: hidden; }
    .event-card:hover { transform: translateY(-5px); box-shadow: 0 8px 25px rgba(0,0,0,0.1); }
    .event-date-box { position: absolute; top: 15px; left: 15px; background: var(--secondary-color); color: #fff; padding: 5px 10px; text-align: center; border-radius: 4px; }
    .event-date-box .day { display: block; font-size: 1.2rem; font-weight: 700; line-height: 1; }
    .event-date-box .month { font-size: 0.75rem; text-transform: uppercase; }

    /* Vertical Marquee for Notices */
    .marquee-vertical { height: 300px; overflow: hidden; position: relative; }
    .marquee-vertical .list-group { position: absolute; width: 100%; animation: marquee-up 15s linear infinite; }
    .marquee-vertical:hover .list-group { animation-play-state: paused; }
    @keyframes marquee-up {
        0% { top: 100%; }
        100% { top: -100%; }
    }
</style>

<!-- Slider Section (Full Width) -->
<div class="row mb-4">
    <div class="col-12">
        <div id="mainSlider" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">
                <?php
                $active = true;
                if ($slider_result && $slider_result->num_rows > 0) {
                    while($slide = $slider_result->fetch_assoc()) {
                        ?>
                        <div class="carousel-item <?php echo $active ? 'active' : ''; ?>">
                            <img src="<?php echo htmlspecialchars($slide['image']); ?>" class="d-block w-100 slider-img" alt="<?php echo htmlspecialchars($slide['title']); ?>">
                            <?php if($slide['title']): ?>
                            <div class="carousel-caption d-none d-md-block bg-dark bg-opacity-50 p-2 rounded">
                                <h5><?php echo htmlspecialchars($slide['title']); ?></h5>
                            </div>
                            <?php endif; ?>
                        </div>
                        <?php
                        $active = false;
                    }
                } else {
                    // Default slide if no images
                    echo '<div class="carousel-item active"><img src="https://via.placeholder.com/1200x500?text=Welcome+to+EduPortal" class="d-block w-100 slider-img" alt="Welcome"></div>';
                }
                ?>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#mainSlider" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#mainSlider" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
    </div>
</div>

<!-- Programs and Notices -->
<div class="row mb-5">
    <div class="col-md-8">
        <div class="row h-100">
            <div class="col-md-6 mb-3">
                <div class="card h-100 shadow-sm border-0">
                    <img src="<?php echo $f1_img; ?>" class="card-img-top" alt="<?php echo htmlspecialchars($f1_title); ?>" style="height: 200px; object-fit: cover;">
                    <div class="card-body">
                        <h5 class="card-title fw-bold" style="color: var(--primary-color);"><?php echo htmlspecialchars($f1_title); ?></h5>
                        <p class="card-text small"><?php echo htmlspecialchars($f1_text); ?></p>
                        <a href="<?php echo htmlspecialchars($f1_link); ?>" class="btn btn-sm btn-outline-primary">Learn More</a>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-3">
                <div class="card h-100 shadow-sm border-0">
                    <img src="<?php echo $f2_img; ?>" class="card-img-top" alt="<?php echo htmlspecialchars($f2_title); ?>" style="height: 200px; object-fit: cover;">
                    <div class="card-body">
                        <h5 class="card-title fw-bold" style="color: var(--primary-color);"><?php echo htmlspecialchars($f2_title); ?></h5>
                        <p class="card-text small"><?php echo htmlspecialchars($f2_text); ?></p>
                        <a href="<?php echo htmlspecialchars($f2_link); ?>" class="btn btn-sm btn-outline-primary">Learn More</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-bullhorn"></i> Notice Board</h5>
            </div>
            <div class="marquee-vertical">
                <div class="list-group list-group-flush">
                    <?php
                    if ($notices_result && $notices_result->num_rows > 0) {
                        while($notice = $notices_result->fetch_assoc()) {
                            ?>
                            <div class="list-group-item">
                                <div class="d-flex w-100 justify-content-between">
                                    <h6 class="mb-1 text-primary">
                                        <?php if($notice['is_pinned']): ?><i class="fas fa-thumbtack text-danger"></i><?php endif; ?>
                                        <?php if(!empty($notice['link'])): ?>
                                            <a href="<?php echo htmlspecialchars($notice['link']); ?>" target="_blank" class="text-decoration-none"><?php echo htmlspecialchars($notice['title']); ?></a>
                                        <?php else: ?>
                                            <?php echo htmlspecialchars($notice['title']); ?>
                                        <?php endif; ?>
                                    </h6>
                                    <small class="text-muted"><?php echo date('M d', strtotime($notice['date_posted'])); ?></small>
                                </div>
                                <p class="mb-1 small text-truncate"><?php echo strip_tags($notice['content']); ?></p>
                                <?php if($notice['file']): ?>
                                    <a href="<?php echo htmlspecialchars($notice['file']); ?>" class="badge bg-secondary text-decoration-none" target="_blank">Download File</a>
                                <?php endif; ?>
                            </div>
                            <?php
                        }
                    } else {
                        echo '<div class="list-group-item">No recent notices.</div>';
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Events Section -->
<?php if($events_result && $events_result->num_rows > 0): ?>
<div class="mb-5">
    <div class="section-title">
        <h3 class="mb-0">Upcoming Events</h3>
    </div>
    <div class="row">
        <?php while($event = $events_result->fetch_assoc()): ?>
        <div class="col-md-3 col-sm-6 mb-4">
            <a href="event_detail.php?id=<?php echo $event['id']; ?>" class="text-decoration-none text-dark">
                <div class="card event-card h-100">
                    <div class="position-relative">
                        <?php if($event['image']): ?>
                            <img src="<?php echo htmlspecialchars($event['image']); ?>" class="card-img-top" style="height: 180px; object-fit: cover;">
                        <?php else: ?>
                            <div class="bg-light d-flex align-items-center justify-content-center" style="height: 180px;">
                                <i class="fas fa-calendar-alt fa-3x text-muted"></i>
                            </div>
                        <?php endif; ?>
                        <div class="event-date-box">
                            <span class="day"><?php echo date('d', strtotime($event['date'])); ?></span>
                            <span class="month"><?php echo date('M', strtotime($event['date'])); ?></span>
                        </div>
                    </div>
                    <div class="card-body">
                        <h6 class="card-title fw-bold text-primary mb-2"><?php echo htmlspecialchars($event['title']); ?></h6>
                        <p class="card-text small text-muted mb-2"><i class="far fa-clock"></i> <?php echo htmlspecialchars($event['time'] ?? 'All Day'); ?></p>
                        <p class="card-text small"><?php echo substr(strip_tags($event['description']), 0, 60) . '...'; ?></p>
                    </div>
                </div>
            </a>
        </div>
        <?php endwhile; ?>
    </div>
</div>
<?php endif; ?>

<div class="row mt-5">
    <div class="col-md-4 text-center mb-4">
        <div class="card h-100 border-0 shadow-sm p-3">
            <div class="card-body">
                <i class="fas fa-user-graduate fa-3x text-success mb-3"></i>
                <h3>Academic Excellence</h3>
                <p>Providing top-tier education with modern facilities and experienced faculty.</p>
            </div>
        </div>
    </div>
    <div class="col-md-4 text-center mb-4">
        <div class="card h-100 border-0 shadow-sm p-3">
            <div class="card-body">
                <i class="fas fa-flask fa-3x text-warning mb-3"></i>
                <h3>Research & Innovation</h3>
                <p>Fostering a culture of inquiry and discovery through state-of-the-art labs.</p>
            </div>
        </div>
    </div>
    <div class="col-md-4 text-center mb-4">
        <div class="card h-100 border-0 shadow-sm p-3">
            <div class="card-body">
                <i class="fas fa-users fa-3x text-info mb-3"></i>
                <h3>Community Life</h3>
                <p>A vibrant campus life with diverse clubs, sports, and cultural activities.</p>
            </div>
        </div>
    </div>
</div>

<!-- Stats / Badges Section (Bottom) -->
<?php if($stats_result && $stats_result->num_rows > 0): ?>
<div class="row mb-5 pt-4">
    <div class="col-12">
        <div class="row g-4 justify-content-center">
            <?php
            // Reset pointer if reused or fetch again. Since we used fetch_assoc loop above, the pointer is at end.
            // But wait, we deleted the loop above. So pointer is at start. Correct.
            // Check if stats_result is valid
            if ($stats_result) $stats_result->data_seek(0);
            while($stat = $stats_result->fetch_assoc()): ?>
            <div class="col-md-2 col-sm-4 col-6">
                <div class="stat-card">
                    <i class="<?php echo htmlspecialchars($stat['icon']); ?>"></i>
                    <span class="count"><?php echo htmlspecialchars($stat['number']); ?></span>
                    <span class="label"><?php echo htmlspecialchars($stat['label']); ?></span>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Photo Gallery Section -->
<?php if($gallery_result && $gallery_result->num_rows > 0): ?>
<div class="mb-5 mt-5">
    <div class="section-title">
        <h3 class="mb-0">Photo Gallery</h3>
    </div>
    <div class="row g-4">
        <?php while($album = $gallery_result->fetch_assoc()): ?>
        <div class="col-md-3 col-sm-6">
            <div class="card h-100 shadow-sm border-0">
                <img src="<?php echo htmlspecialchars($album['cover_image']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($album['title']); ?>" style="height: 200px; object-fit: cover;">
                <div class="card-body text-center">
                    <h5 class="card-title h6 fw-bold"><?php echo htmlspecialchars($album['title']); ?></h5>
                    <a href="gallery_detail.php?id=<?php echo $album['id']; ?>" class="btn btn-sm btn-outline-primary stretched-link">View Album</a>
                </div>
            </div>
        </div>
        <?php endwhile; ?>
    </div>
</div>
<?php endif; ?>

<!-- Stats / Badges Section (Bottom) -->
<?php if($stats_result && $stats_result->num_rows > 0): ?>
<div class="row mb-5 pt-4">
    <div class="col-12">
        <div class="row g-4 justify-content-center">
            <?php
            // Reset pointer if reused or fetch again. Since we used fetch_assoc loop above, the pointer is at end.
            // But wait, we deleted the loop above. So pointer is at start. Correct.
            // Check if stats_result is valid
            if ($stats_result) $stats_result->data_seek(0);
            while($stat = $stats_result->fetch_assoc()): ?>
            <div class="col-md-2 col-sm-4 col-6">
                <div class="stat-card">
                    <i class="<?php echo htmlspecialchars($stat['icon']); ?>"></i>
                    <span class="count"><?php echo htmlspecialchars($stat['number']); ?></span>
                    <span class="label"><?php echo htmlspecialchars($stat['label']); ?></span>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
    </div>
</div>
<?php endif; ?>

<?php require_once 'includes/footer.php'; ?>
