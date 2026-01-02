<?php
$page_title = "Manage Photos";
require_once 'includes/header.php';

if (!isset($_GET['album_id'])) {
    header("Location: albums_list.php");
    exit;
}

$album_id = (int)$_GET['album_id'];
$album = $conn->query("SELECT * FROM gallery_albums WHERE id = $album_id")->fetch_assoc();

if (!$album) {
    die("Album not found.");
}

// Handle Delete
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $conn->query("DELETE FROM gallery_images WHERE id = $id");
    header("Location: gallery_images.php?album_id=$album_id");
    exit;
}

// Handle Upload
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $caption = $conn->real_escape_string($_POST['caption']);

    // Multiple Files logic could be added here, but keeping it simple single upload for now or multiple attribute
    // Simple single upload loop for clarity or use 'multiple' attribute on input

    if (isset($_FILES['images'])) {
        $target_dir = "../media/gallery/images/";
        if (!file_exists($target_dir)) { mkdir($target_dir, 0777, true); }

        $total = count($_FILES['images']['name']);

        for($i=0; $i<$total; $i++) {
            if ($_FILES['images']['error'][$i] === 0) {
                $filename = time() . "_" . $i . "_" . basename($_FILES['images']['name'][$i]);
                $target_file = $target_dir . $filename;

                if (move_uploaded_file($_FILES['images']['tmp_name'][$i], $target_file)) {
                    $image_path = "media/gallery/images/" . $filename;
                    $conn->query("INSERT INTO gallery_images (album_id, image, caption) VALUES ($album_id, '$image_path', '$caption')");
                }
            }
        }
        $_SESSION['msg_success'] = "Photos uploaded successfully.";
        header("Location: gallery_images.php?album_id=$album_id");
        exit;
    }
}

$images = $conn->query("SELECT * FROM gallery_images WHERE album_id = $album_id");
?>

<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="albums_list.php">Albums</a></li>
    <li class="breadcrumb-item active" aria-current="page"><?php echo htmlspecialchars($album['title']); ?></li>
  </ol>
</nav>

<div class="row">
    <div class="col-md-4">
        <div class="card mb-4">
            <div class="card-header bg-success text-white">Upload Photos</div>
            <div class="card-body">
                <form method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label class="form-label">Select Images</label>
                        <input type="file" name="images[]" class="form-control" multiple required>
                        <small class="text-muted">You can select multiple files.</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Default Caption (Optional)</label>
                        <input type="text" name="caption" class="form-control" placeholder="Applied to all uploaded images">
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Upload</button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="row row-cols-1 row-cols-md-3 g-3">
            <?php while($img = $images->fetch_assoc()): ?>
            <div class="col">
                <div class="card h-100">
                    <img src="../<?php echo $img['image']; ?>" class="card-img-top" style="height: 150px; object-fit: cover;">
                    <div class="card-body p-2 text-center">
                        <small><?php echo htmlspecialchars($img['caption']); ?></small>
                        <a href="?album_id=<?php echo $album_id; ?>&delete=<?php echo $img['id']; ?>" class="text-danger d-block mt-1" onclick="return confirm('Delete photo?')"><i class="fas fa-trash"></i> Delete</a>
                    </div>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
