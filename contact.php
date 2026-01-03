<?php
$page_title = "Contact Us";
require_once 'includes/header.php';

// Fetch Settings
$settings = [];
$settings_result = $conn->query("SELECT * FROM site_settings");
while($row = $settings_result->fetch_assoc()) {
    $settings[$row['setting_key']] = $row['setting_value'];
}

$msg_sent = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Basic form handling demo
    $msg_sent = true;
}
?>

<div class="mt-5">
    <h2 class="mb-4 text-center">Contact Us</h2>

    <!-- Info Cards Row -->
    <div class="row g-4 mb-5">
        <!-- Address Card -->
        <div class="col-md-4">
            <div class="card h-100 border-0 shadow-sm text-center py-4">
                <div class="card-body">
                    <div class="mb-3 text-primary"><i class="fas fa-map-marker-alt fa-2x"></i></div>
                    <h5 class="card-title fw-bold text-uppercase">Address</h5>
                    <p class="card-text text-muted small"><?php echo nl2br(htmlspecialchars($settings['contact_address'] ?? '21 cooper road, Lahore, Pakistan')); ?></p>
                </div>
            </div>
        </div>

        <!-- Contact Info Card -->
        <div class="col-md-4">
            <div class="card h-100 border-0 shadow-sm text-center py-4">
                <div class="card-body">
                    <div class="mb-3 text-primary"><i class="fas fa-phone-alt fa-2x"></i></div>
                    <h5 class="card-title fw-bold text-uppercase">Contact info</h5>
                    <p class="card-text text-muted small mb-1"><strong>Contact:</strong> <?php echo htmlspecialchars($settings['contact_phone'] ?? '(042) 99200776'); ?></p>
                    <p class="card-text text-muted small"><strong>Mail:</strong> <a href="mailto:<?php echo htmlspecialchars($settings['contact_email'] ?? 'info@gigcwcooperroad.edu.pk'); ?>" class="text-decoration-none"><?php echo htmlspecialchars($settings['contact_email'] ?? 'info@gigcwcooperroad.edu.pk'); ?></a></p>
                </div>
            </div>
        </div>

        <!-- Timing Card -->
        <div class="col-md-4">
            <div class="card h-100 border-0 shadow-sm text-center py-4">
                <div class="card-body">
                    <div class="mb-3 text-primary"><i class="far fa-clock fa-2x"></i></div>
                    <h5 class="card-title fw-bold text-uppercase">College Timing</h5>
                    <p class="card-text text-muted small"><?php echo htmlspecialchars($settings['contact_timing'] ?? 'Monday - Saturday: 09:00 - 02:00'); ?></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Map Section -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="card border-0 shadow-sm overflow-hidden">
                <div class="ratio ratio-21x9">
                    <iframe src="<?php echo htmlspecialchars($settings['contact_map_iframe'] ?? 'https://maps.google.com/maps?q=Govt.%20Islamia%20Graduate%20College%20%28W%29%2C%20Cooper%20Road%2C%20Lahore&t=m&z=14&output=embed&iwloc=near'); ?>" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
