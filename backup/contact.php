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

<div class="row">
    <div class="col-md-6 mb-4">
        <h2 class="mb-3">Get in Touch</h2>
        <p class="text-muted">We are here to answer any questions you may have about our programs and campus life.</p>

        <div class="d-flex align-items-start mb-3">
            <i class="fas fa-map-marker-alt fa-2x text-primary me-3 mt-1"></i>
            <div>
                <h5 class="fw-bold">Address</h5>
                <p><?php echo nl2br(htmlspecialchars($settings['contact_address'] ?? '123 Campus Road')); ?></p>
            </div>
        </div>

        <div class="d-flex align-items-start mb-3">
            <i class="fas fa-phone fa-2x text-primary me-3 mt-1"></i>
            <div>
                <h5 class="fw-bold">Phone</h5>
                <p><?php echo htmlspecialchars($settings['contact_phone'] ?? '(123) 456-7890'); ?></p>
            </div>
        </div>

        <div class="d-flex align-items-start mb-3">
            <i class="fas fa-envelope fa-2x text-primary me-3 mt-1"></i>
            <div>
                <h5 class="fw-bold">Email</h5>
                <p><a href="mailto:<?php echo htmlspecialchars($settings['contact_email'] ?? 'info@example.com'); ?>"><?php echo htmlspecialchars($settings['contact_email'] ?? 'info@example.com'); ?></a></p>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <h3 class="mb-4">Send us a Message</h3>
                <?php if($msg_sent): ?>
                    <div class="alert alert-success">Thank you! Your message has been sent.</div>
                <?php endif; ?>
                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label">Full Name</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email Address</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Subject</label>
                        <input type="text" name="subject" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Message</label>
                        <textarea name="message" class="form-control" rows="5" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Send Message</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Google Maps Placeholder -->
<div class="row mt-5">
    <div class="col-12">
        <div class="ratio ratio-21x9 bg-light border">
            <div class="d-flex align-items-center justify-content-center text-muted">
                <i class="fas fa-map-marked-alt fa-3x"></i>
                <span class="ms-3">Map Integration (Embed Code)</span>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
