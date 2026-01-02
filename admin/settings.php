<?php
$page_title = "Site Settings";
require_once 'includes/header.php';

// Fetch current settings
$settings = [];
$result = $conn->query("SELECT * FROM site_settings");
while($row = $result->fetch_assoc()) {
    $settings[$row['setting_key']] = $row['setting_value'];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $site_name = $conn->real_escape_string($_POST['site_name']);
    $contact_email = $conn->real_escape_string($_POST['contact_email']);
    $contact_phone = $conn->real_escape_string($_POST['contact_phone']);
    $contact_address = $conn->real_escape_string($_POST['contact_address']);

    // Logo Upload
    $logo_path = isset($settings['site_logo']) ? $settings['site_logo'] : '';
    if (isset($_FILES['site_logo']) && $_FILES['site_logo']['error'] === 0) {
        $target_dir = "../media/settings/";
        if (!file_exists($target_dir)) { mkdir($target_dir, 0777, true); }

        $filename = "logo_" . time() . "_" . basename($_FILES['site_logo']['name']);
        $target_file = $target_dir . $filename;

        if (move_uploaded_file($_FILES['site_logo']['tmp_name'], $target_file)) {
            $logo_path = "media/settings/" . $filename;
        }
    }

    // Update or Insert Settings
    $updates = [
        'site_name' => $site_name,
        'contact_email' => $contact_email,
        'contact_phone' => $contact_phone,
        'contact_address' => $contact_address,
        'site_logo' => $logo_path
    ];

    foreach ($updates as $key => $value) {
        // Check if exists
        $check = $conn->query("SELECT id FROM site_settings WHERE setting_key = '$key'");
        if ($check->num_rows > 0) {
            $sql = "UPDATE site_settings SET setting_value = '$value' WHERE setting_key = '$key'";
        } else {
            $sql = "INSERT INTO site_settings (setting_key, setting_value) VALUES ('$key', '$value')";
        }
        $conn->query($sql);
    }

    $_SESSION['msg_success'] = "Settings updated successfully.";
    echo "<script>window.location.href='settings.php';</script>";
    exit;
}
?>

<div class="row">
    <div class="col-md-8 offset-md-2">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">General Settings</h5>
            </div>
            <div class="card-body">
                <form method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label class="form-label">Site Name</label>
                        <input type="text" name="site_name" class="form-control" value="<?php echo isset($settings['site_name']) ? htmlspecialchars($settings['site_name']) : ''; ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Site Logo</label>
                        <?php if(isset($settings['site_logo']) && $settings['site_logo']): ?>
                            <div class="mb-2 p-2 bg-light border rounded">
                                <img src="../<?php echo $settings['site_logo']; ?>" height="60">
                            </div>
                        <?php endif; ?>
                        <input type="file" name="site_logo" class="form-control">
                        <small class="text-muted">Recommended height: 50-80px.</small>
                    </div>
                    <hr>
                    <h6 class="mb-3">Contact Information</h6>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="contact_email" class="form-control" value="<?php echo isset($settings['contact_email']) ? htmlspecialchars($settings['contact_email']) : ''; ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Phone</label>
                        <input type="text" name="contact_phone" class="form-control" value="<?php echo isset($settings['contact_phone']) ? htmlspecialchars($settings['contact_phone']) : ''; ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Address</label>
                        <textarea name="contact_address" class="form-control" rows="2"><?php echo isset($settings['contact_address']) ? htmlspecialchars($settings['contact_address']) : ''; ?></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Save Settings</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
