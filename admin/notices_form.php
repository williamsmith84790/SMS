<?php
$page_title = "Notice Details";
require_once 'includes/header.php';

if (!has_permission('notices')) {
    echo '<div class="alert alert-danger">You do not have permission to access this page.</div>';
    require_once 'includes/footer.php';
    exit;
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : null;
$notice = null;

if ($id) {
    $result = $conn->query("SELECT * FROM notices WHERE id = $id");
    if ($result->num_rows > 0) {
        $notice = $result->fetch_assoc();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $conn->real_escape_string($_POST['title']);
    $content = $conn->real_escape_string($_POST['content']);
    $link = $conn->real_escape_string($_POST['link']);
    $is_pinned = isset($_POST['is_pinned']) ? 1 : 0;

    // File Upload
    $file_path = $notice ? $notice['file'] : '';
    if (isset($_FILES['file']) && $_FILES['file']['error'] === 0) {
        $target_dir = "../media/notices/";
        if (!file_exists($target_dir)) { mkdir($target_dir, 0777, true); }

        $filename = time() . "_" . basename($_FILES['file']['name']);
        $target_file = $target_dir . $filename;

        if (move_uploaded_file($_FILES['file']['tmp_name'], $target_file)) {
            $file_path = "media/notices/" . $filename;
        }
    }

    if ($id) {
        $sql = "UPDATE notices SET title='$title', content='$content', link='$link', is_pinned=$is_pinned, file='$file_path' WHERE id=$id";
    } else {
        $sql = "INSERT INTO notices (title, content, link, is_pinned, file) VALUES ('$title', '$content', '$link', $is_pinned, '$file_path')";
    }

    if ($conn->query($sql)) {
        $_SESSION['msg_success'] = "Notice saved successfully.";
        echo "<script>window.location.href='notices_list.php';</script>";
        exit;
    } else {
        $error = "Error: " . $conn->error;
    }
}
?>

<div class="row">
    <div class="col-md-8 offset-md-2">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><?php echo $id ? 'Edit' : 'Add'; ?> Notice</h5>
            </div>
            <div class="card-body">
                <?php if(isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
                <form method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label class="form-label">Title</label>
                        <input type="text" name="title" class="form-control" required value="<?php echo $notice ? htmlspecialchars($notice['title']) : ''; ?>">
                    </div>
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" name="is_pinned" id="is_pinned" <?php echo ($notice && $notice['is_pinned']) ? 'checked' : ''; ?>>
                        <label class="form-check-label" for="is_pinned">Pin to Top?</label>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">External Link (Optional)</label>
                        <input type="text" name="link" class="form-control" placeholder="http://..." value="<?php echo $notice ? htmlspecialchars($notice['link'] ?? '') : ''; ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Attachment (PDF/Doc)</label>
                        <?php if($notice && $notice['file']): ?>
                            <div class="mb-2"><a href="../<?php echo $notice['file']; ?>" target="_blank">Current File</a></div>
                        <?php endif; ?>
                        <input type="file" name="file" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Content (Rich Text)</label>
                        <textarea name="content" class="form-control summernote"><?php echo $notice ? $notice['content'] : ''; ?></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                    <a href="notices_list.php" class="btn btn-secondary">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
