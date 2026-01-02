<?php
$page_title = "Dashboard";
require_once 'includes/header.php';

// Quick Stats
$stats = [];
$stats['notices'] = $conn->query("SELECT COUNT(*) FROM notices")->fetch_row()[0];
$stats['faculty'] = $conn->query("SELECT COUNT(*) FROM faculty_members")->fetch_row()[0];
$stats['students'] = $conn->query("SELECT COUNT(*) FROM student_results")->fetch_row()[0];
$stats['albums'] = $conn->query("SELECT COUNT(*) FROM gallery_albums")->fetch_row()[0];
?>

<div class="row g-4">
    <div class="col-md-3">
        <div class="card text-white bg-primary h-100">
            <div class="card-body">
                <h5 class="card-title">Notices</h5>
                <p class="card-text display-4"><?php echo $stats['notices']; ?></p>
            </div>
            <div class="card-footer d-flex justify-content-between align-items-center">
                <a href="notices_list.php" class="text-white text-decoration-none stretched-link">View Details</a>
                <i class="fas fa-arrow-right"></i>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-success h-100">
            <div class="card-body">
                <h5 class="card-title">Faculty</h5>
                <p class="card-text display-4"><?php echo $stats['faculty']; ?></p>
            </div>
            <div class="card-footer d-flex justify-content-between align-items-center">
                <a href="faculty_list.php" class="text-white text-decoration-none stretched-link">View Details</a>
                <i class="fas fa-arrow-right"></i>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-warning h-100">
            <div class="card-body">
                <h5 class="card-title">Results</h5>
                <p class="card-text display-4"><?php echo $stats['students']; ?></p>
            </div>
            <div class="card-footer d-flex justify-content-between align-items-center">
                <a href="results_list.php" class="text-white text-decoration-none stretched-link">View Details</a>
                <i class="fas fa-arrow-right"></i>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-info h-100">
            <div class="card-body">
                <h5 class="card-title">Gallery Albums</h5>
                <p class="card-text display-4"><?php echo $stats['albums']; ?></p>
            </div>
            <div class="card-footer d-flex justify-content-between align-items-center">
                <a href="albums_list.php" class="text-white text-decoration-none stretched-link">View Details</a>
                <i class="fas fa-arrow-right"></i>
            </div>
        </div>
    </div>
</div>

<div class="row mt-5">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                Welcome to Admin Panel
            </div>
            <div class="card-body">
                <p>Select an option from the sidebar to manage website content.</p>
                <p>Use the <strong>"View Site"</strong> link to see your changes live.</p>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
