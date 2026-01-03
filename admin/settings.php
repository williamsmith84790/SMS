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
        'footer_about_text', 'header_apply_link', 'footer_copyright_text',
        'feature_1_title', 'feature_1_text', 'feature_1_link',
        'feature_2_title', 'feature_2_text', 'feature_2_link',
        'result_card_board_name', 'result_card_title', 'result_card_exam_title',
        'contact_timing', 'contact_map_iframe'
    ];

    // File Upload Logic Helper
    $target_dir = "../media/settings/";
    if (!file_exists($target_dir)) { mkdir($target_dir, 0777, true); }

    function handle_upload($file_key, $existing_path, $prefix, $target_dir) {
        if (isset($_FILES[$file_key]) && $_FILES[$file_key]['error'] === 0) {
            $filename = $prefix . "_" . time() . "_" . basename($_FILES[$file_key]['name']);
            $target_file = $target_dir . $filename;
            if (move_uploaded_file($_FILES[$file_key]['tmp_name'], $target_file)) {
                return "media/settings/" . $filename;
            }
        }
        return $existing_path;
    }

    // Handle Logo Removal
    $current_logo = isset($settings['site_logo']) ? $settings['site_logo'] : '';
    if (isset($_POST['remove_logo']) && $_POST['remove_logo'] == '1') {
        $logo_path = '';
    } else {
        $logo_path = handle_upload('site_logo', $current_logo, 'logo', $target_dir);
    }

    $f1_img_path = handle_upload('feature_1_image', isset($settings['feature_1_image']) ? $settings['feature_1_image'] : '', 'f1', $target_dir);
    $f2_img_path = handle_upload('feature_2_image', isset($settings['feature_2_image']) ? $settings['feature_2_image'] : '', 'f2', $target_dir);

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

    // Process File Updates
    $file_updates = [
        'site_logo' => $logo_path,
        'feature_1_image' => $f1_img_path,
        'feature_2_image' => $f2_img_path
    ];

    foreach ($file_updates as $key => $path) {
        $check = $conn->query("SELECT id FROM site_settings WHERE setting_key = '$key'");
        if ($check->num_rows > 0) {
            $sql = "UPDATE site_settings SET setting_value = '$path' WHERE setting_key = '$key'";
        } else {
            $sql = "INSERT INTO site_settings (setting_key, setting_value) VALUES ('$key', '$path')";
        }
        $conn->query($sql);
    }

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
                        <li class="nav-item">
                            <button class="nav-link" id="homefeatures-tab" data-bs-toggle="tab" data-bs-target="#homefeatures" type="button">Home Feature Cards</button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link" id="results-tab" data-bs-toggle="tab" data-bs-target="#results" type="button">Result Card</button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link" id="contact-tab" data-bs-toggle="tab" data-bs-target="#contact" type="button">Contact Page</button>
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
                                    <div class="mb-2 p-2 bg-light border rounded d-flex align-items-center justify-content-between">
                                        <img src="../<?php echo $settings['site_logo']; ?>" height="60">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="remove_logo" value="1" id="remove_logo">
                                            <label class="form-check-label text-danger" for="remove_logo">Remove Logo</label>
                                        </div>
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

                        <!-- Home Feature Cards Tab -->
                        <div class="tab-pane fade" id="homefeatures">
                            <h6 class="mb-3 text-primary">Feature Card 1 (Left)</h6>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">Title</label>
                                    <input type="text" name="feature_1_title" class="form-control" value="<?php echo isset($settings['feature_1_title']) ? htmlspecialchars($settings['feature_1_title']) : ''; ?>">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Link (Page Slug or URL)</label>
                                    <input type="text" name="feature_1_link" class="form-control" value="<?php echo isset($settings['feature_1_link']) ? htmlspecialchars($settings['feature_1_link']) : ''; ?>">
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Description</label>
                                <textarea name="feature_1_text" class="form-control" rows="2"><?php echo isset($settings['feature_1_text']) ? htmlspecialchars($settings['feature_1_text']) : ''; ?></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Card Image</label>
                                <?php if(isset($settings['feature_1_image']) && $settings['feature_1_image']): ?>
                                    <div class="mb-2 p-2 bg-light border rounded">
                                        <img src="../<?php echo $settings['feature_1_image']; ?>" height="60">
                                    </div>
                                <?php endif; ?>
                                <input type="file" name="feature_1_image" class="form-control">
                            </div>

                            <hr>

                            <h6 class="mb-3 text-danger">Feature Card 2 (Right)</h6>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">Title</label>
                                    <input type="text" name="feature_2_title" class="form-control" value="<?php echo isset($settings['feature_2_title']) ? htmlspecialchars($settings['feature_2_title']) : ''; ?>">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Link (Page Slug or URL)</label>
                                    <input type="text" name="feature_2_link" class="form-control" value="<?php echo isset($settings['feature_2_link']) ? htmlspecialchars($settings['feature_2_link']) : ''; ?>">
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Description</label>
                                <textarea name="feature_2_text" class="form-control" rows="2"><?php echo isset($settings['feature_2_text']) ? htmlspecialchars($settings['feature_2_text']) : ''; ?></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Card Image</label>
                                <?php if(isset($settings['feature_2_image']) && $settings['feature_2_image']): ?>
                                    <div class="mb-2 p-2 bg-light border rounded">
                                        <img src="../<?php echo $settings['feature_2_image']; ?>" height="60">
                                    </div>
                                <?php endif; ?>
                                <input type="file" name="feature_2_image" class="form-control">
                            </div>
                        </div>

                        <!-- Result Card Tab -->
                        <div class="tab-pane fade" id="results">
                            <h6 class="mb-3">Result Card Header Configuration</h6>
                            <div class="mb-3">
                                <label class="form-label">Board/Institution Name</label>
                                <input type="text" name="result_card_board_name" class="form-control" value="<?php echo isset($settings['result_card_board_name']) ? htmlspecialchars($settings['result_card_board_name']) : 'BOARD OF INTERMEDIATE & SECONDARY EDUCATION'; ?>">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Result Heading</label>
                                <input type="text" name="result_card_title" class="form-control" value="<?php echo isset($settings['result_card_title']) ? htmlspecialchars($settings['result_card_title']) : 'RESULT INFORMATION'; ?>">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Exam Title Format</label>
                                <input type="text" name="result_card_exam_title" class="form-control" value="<?php echo isset($settings['result_card_exam_title']) ? htmlspecialchars($settings['result_card_exam_title']) : 'HIGHER SECONDARY SCHOOL CERTIFICATE (ANNUAL), EXAMINATION'; ?>">
                                <small class="text-muted">This text appears below the heading. The dynamic Class/Session will be prefixed if using the default code, or you can customize the layout in source.</small>
                            </div>
                        </div>

                        <!-- Contact Page Tab -->
                        <div class="tab-pane fade" id="contact">
                            <h6 class="mb-3">Contact Information</h6>
                            <div class="mb-3">
                                <label class="form-label">College Timing</label>
                                <input type="text" name="contact_timing" class="form-control" value="<?php echo isset($settings['contact_timing']) ? htmlspecialchars($settings['contact_timing']) : 'Monday - Saturday: 09:00 - 02:00'; ?>">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Google Maps Iframe URL (src attribute only)</label>
                                <input type="text" name="contact_map_iframe" class="form-control" value="<?php echo isset($settings['contact_map_iframe']) ? htmlspecialchars($settings['contact_map_iframe']) : 'https://maps.google.com/maps?q=Govt.%20Islamia%20Graduate%20College%20%28W%29%2C%20Cooper%20Road%2C%20Lahore&t=m&z=14&output=embed&iwloc=near'; ?>">
                                <small class="text-muted">Enter the URL from the 'src' attribute of the Google Maps embed code.</small>
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
