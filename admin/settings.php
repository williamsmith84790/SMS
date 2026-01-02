<?php
$page_title = "Site Settings";
require_once 'includes/header.php';

if (!has_permission('settings')) {
    echo '<div class="alert alert-danger">You do not have permission to access this page.</div>';
    require_once 'includes/footer.php';
    exit;
}

// Fetch current settings
$settings = [];
$result = $conn->query("SELECT * FROM site_settings");
while($row = $result->fetch_assoc()) {
    $settings[$row['setting_key']] = $row['setting_value'];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect all posted fields (excluding file)
    $fields = [
        'site_name', 'contact_email', 'contact_phone', 'contact_address',
        'social_facebook', 'social_twitter', 'social_instagram', 'social_linkedin',
        'footer_about_text', 'header_apply_link', 'footer_copyright_text'
    ];

    // Logo Upload logic
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

    // Process text fields
    foreach ($fields as $field) {
        $val = isset($_POST[$field]) ? $conn->real_escape_string($_POST[$field]) : '';
        // Insert or Update
        $check = $conn->query("SELECT id FROM site_settings WHERE setting_key = '$field'");
        if ($check->num_rows > 0) {
            $sql = "UPDATE site_settings SET setting_value = '$val' WHERE setting_key = '$field'";
        } else {
            $sql = "INSERT INTO site_settings (setting_key, setting_value) VALUES ('$field', '$val')";
        }
        $conn->query($sql);
    }

    // Process Logo Update
    $check_logo = $conn->query("SELECT id FROM site_settings WHERE setting_key = 'site_logo'");
    if ($check_logo->num_rows > 0) {
        $sql = "UPDATE site_settings SET setting_value = '$logo_path' WHERE setting_key = 'site_logo'";
    } else {
        $sql = "INSERT INTO site_settings (setting_key, setting_value) VALUES ('site_logo', '$logo_path')";
    }
    $conn->query($sql);

    $_SESSION['msg_success'] = "Settings updated successfully.";
    echo "<script>window.location.href='settings.php';</script>";
    exit;
}
?>

<div class="row">
    <div class="col-md-10 offset-md-1">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-cogs"></i> Site Settings</h5>
            </div>
            <div class="card-body">
                <form method="POST" enctype="multipart/form-data">
                    <ul class="nav nav-tabs mb-4" id="settingsTab" role="tablist">
                        <li class="nav-item">
                            <button class="nav-link active" id="general-tab" data-bs-toggle="tab" data-bs-target="#general" type="button">General</button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link" id="social-tab" data-bs-toggle="tab" data-bs-target="#social" type="button">Social Media</button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link" id="headerfooter-tab" data-bs-toggle="tab" data-bs-target="#headerfooter" type="button">Header & Footer</button>
                        </li>
                    </ul>

                    <div class="tab-content" id="settingsTabContent">
                        <!-- General Tab -->
                        <div class="tab-pane fade show active" id="general">
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
                            </div>
                            <hr>
                            <h6 class="mb-3">Contact Information</h6>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Email</label>
                                    <input type="email" name="contact_email" class="form-control" value="<?php echo isset($settings['contact_email']) ? htmlspecialchars($settings['contact_email']) : ''; ?>">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Phone</label>
                                    <input type="text" name="contact_phone" class="form-control" value="<?php echo isset($settings['contact_phone']) ? htmlspecialchars($settings['contact_phone']) : ''; ?>">
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Address</label>
                                <textarea name="contact_address" class="form-control" rows="2"><?php echo isset($settings['contact_address']) ? htmlspecialchars($settings['contact_address']) : ''; ?></textarea>
                            </div>
                        </div>

                        <!-- Social Media Tab -->
                        <div class="tab-pane fade" id="social">
                            <div class="mb-3">
                                <label class="form-label"><i class="fab fa-facebook text-primary"></i> Facebook URL</label>
                                <input type="text" name="social_facebook" class="form-control" value="<?php echo isset($settings['social_facebook']) ? htmlspecialchars($settings['social_facebook']) : ''; ?>">
                            </div>
                            <div class="mb-3">
                                <label class="form-label"><i class="fab fa-twitter text-info"></i> Twitter URL</label>
                                <input type="text" name="social_twitter" class="form-control" value="<?php echo isset($settings['social_twitter']) ? htmlspecialchars($settings['social_twitter']) : ''; ?>">
                            </div>
                            <div class="mb-3">
                                <label class="form-label"><i class="fab fa-instagram text-danger"></i> Instagram URL</label>
                                <input type="text" name="social_instagram" class="form-control" value="<?php echo isset($settings['social_instagram']) ? htmlspecialchars($settings['social_instagram']) : ''; ?>">
                            </div>
                            <div class="mb-3">
                                <label class="form-label"><i class="fab fa-linkedin text-primary"></i> LinkedIn URL</label>
                                <input type="text" name="social_linkedin" class="form-control" value="<?php echo isset($settings['social_linkedin']) ? htmlspecialchars($settings['social_linkedin']) : ''; ?>">
                            </div>
                        </div>

                        <!-- Header & Footer Tab -->
                        <div class="tab-pane fade" id="headerfooter">
                            <h6 class="mb-3">Header Settings</h6>
                            <div class="mb-3">
                                <label class="form-label">"Apply Online" Button Link</label>
                                <input type="text" name="header_apply_link" class="form-control" value="<?php echo isset($settings['header_apply_link']) ? htmlspecialchars($settings['header_apply_link']) : ''; ?>">
                            </div>

                            <hr>

                            <h6 class="mb-3">Footer Settings</h6>
                            <div class="mb-3">
                                <label class="form-label">Footer "About Us" Text</label>
                                <textarea name="footer_about_text" class="form-control" rows="3"><?php echo isset($settings['footer_about_text']) ? htmlspecialchars($settings['footer_about_text']) : ''; ?></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Copyright Text</label>
                                <div class="input-group">
                                    <span class="input-group-text">&copy; <?php echo date('Y'); ?> [Site Name].</span>
                                    <input type="text" name="footer_copyright_text" class="form-control" value="<?php echo isset($settings['footer_copyright_text']) ? htmlspecialchars($settings['footer_copyright_text']) : 'All Rights Reserved'; ?>">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4 text-end">
                        <button type="submit" class="btn btn-primary px-4"><i class="fas fa-save"></i> Save All Settings</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
