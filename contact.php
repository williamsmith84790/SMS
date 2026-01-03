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

<div class="row mt-5">
    <div class="col-md-6 mb-4">
        <h2 class="mb-4 pb-2 border-bottom">Contact Us</h2>

        <div class="mb-4">
            <h5 class="fw-bold text-uppercase">Address</h5>
            <p class="text-muted"><?php echo nl2br(htmlspecialchars($settings['contact_address'] ?? '21 cooper road, Lahore, Pakistan')); ?></p>
        </div>

        <div class="mb-4">
            <h5 class="fw-bold text-uppercase">Contact info</h5>
            <p class="text-muted mb-1"><strong>Contact:</strong> <?php echo htmlspecialchars($settings['contact_phone'] ?? '(042) 99200776'); ?></p>
            <p class="text-muted"><strong>Mail:</strong> <a href="mailto:<?php echo htmlspecialchars($settings['contact_email'] ?? 'info@gigcwcooperroad.edu.pk'); ?>" class="text-decoration-none"><?php echo htmlspecialchars($settings['contact_email'] ?? 'info@gigcwcooperroad.edu.pk'); ?></a></p>
        </div>

        <div class="mb-4">
            <h5 class="fw-bold text-uppercase">College Timing</h5>
            <p class="text-muted">Monday - Saturday: 09:00 - 02:00</p>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card shadow-sm border-0 h-100">
            <div class="ratio ratio-1x1 h-100">
                <iframe src="https://maps.google.com/maps?q=Govt.%20Islamia%20Graduate%20College%20%28W%29%2C%20Cooper%20Road%2C%20Lahore&t=m&z=14&output=embed&iwloc=near" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
