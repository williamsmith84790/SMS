<?php
$page_title = "Home";
require_once 'includes/header.php';

// Fetch Active Slider Images
$slider_sql = "SELECT * FROM slider_images WHERE is_active = 1 ORDER BY `order` ASC";
$slider_result = $conn->query($slider_sql);

// Fetch Latest Notices
$notices_sql = "SELECT * FROM notices ORDER BY is_pinned DESC, date_posted DESC LIMIT 5";
$notices_result = $conn->query($notices_sql);
?>

<!-- Slider Section (Full Width) -->
<div class="row mb-5">
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

<!-- Notices Sidebar (Right Side Below Slider) -->
<div class="row mb-4">
    <div class="col-md-4 ms-auto">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-bullhorn"></i> Notice Board</h5>
            </div>
            <div class="list-group list-group-flush">
                <?php
                if ($notices_result && $notices_result->num_rows > 0) {
                    while($notice = $notices_result->fetch_assoc()) {
                        ?>
                        <div class="list-group-item">
                            <div class="d-flex w-100 justify-content-between">
                                <h6 class="mb-1 text-primary">
                                    <?php if($notice['is_pinned']): ?><i class="fas fa-thumbtack text-danger"></i><?php endif; ?>
                                    <?php echo htmlspecialchars($notice['title']); ?>
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
            <div class="card-footer text-center">
                <a href="#" class="btn btn-sm btn-outline-primary">View All Notices</a>
            </div>
        </div>
    </div>
</div>

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

<?php require_once 'includes/footer.php'; ?>
