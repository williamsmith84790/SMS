<?php
$page_title = "Album Details";
require_once 'includes/header.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : null;
$album = null;

if ($id) {
    $result = $conn->query("SELECT * FROM gallery_albums WHERE id = $id");
    if ($result->num_rows > 0) {
        $album = $result->fetch_assoc();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $conn->real_escape_string($_POST['title']);
    $description = $conn->real_escape_string($_POST['description']);

    // Image Upload
    $image_path = $album ? $album['cover_image'] : '';
    if (isset($_FILES['cover_image']) && $_FILES['cover_image']['error'] === 0) {
        $target_dir = "../media/gallery/covers/";
        if (!file_exists($target_dir)) { mkdir($target_dir, 0777, true); }

        $filename = time() . "_" . basename($_FILES['cover_image']['name']);
        $target_file = $target_dir . $filename;

        if (move_uploaded_file($_FILES['cover_image']['tmp_name'], $target_file)) {
            $image_path = "media/gallery/covers/" . $filename;
        }
    }

    if ($id) {
        $sql = "UPDATE gallery_albums SET title='$title', description='$description', cover_image='$image_path' WHERE id=$id";
    } else {
        if (empty($image_path)) {
            $error = "Cover image is required.";
        } else {
            $sql = "INSERT INTO gallery_albums (title, description, cover_image) VALUES ('$title', '$description', '$image_path')";
        }
    }

    if (!isset($error)) {
        if ($conn->query($sql)) {
            $_SESSION['msg_success'] = "Album saved successfully.";
            echo "<script>window.location.href='albums_list.php';</script>";
            exit;
        } else {
            $error = "Error: " . $conn->error;
        }
    }
}
?>

<div class="row">
    <div class="col-md-6 offset-md-3">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><?php echo $id ? 'Edit' : 'Create'; ?> Album</h5>
            </div>
            <div class="card-body">
                <?php if(isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
                <form method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label class="form-label">Album Title</label>
                        <input type="text" name="title" class="form-control" required value="<?php echo $album ? htmlspecialchars($album['title']) : ''; ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="3"><?php echo $album ? htmlspecialchars($album['description']) : ''; ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Cover Image</label>
                        <?php if($album && $album['cover_image']): ?>
                            <div class="mb-2"><img src="../<?php echo $album['cover_image']; ?>" width="100%"></div>
                        <?php endif; ?>
                        <input type="file" name="cover_image" class="form-control" <?php echo $id ? '' : 'required'; ?>>
                    </div>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                    <a href="albums_list.php" class="btn btn-secondary">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
