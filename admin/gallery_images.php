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
    $media_type = $conn->real_escape_string($_POST['media_type']);
    $video_embed = $conn->real_escape_string($_POST['video_embed']);

    // Video Embed
    if ($media_type == 'video' && !empty($video_embed)) {
        $sql = "INSERT INTO gallery_images (album_id, image, caption, media_type, video_embed) VALUES ($album_id, '', '$caption', 'video', '$video_embed')";
        $conn->query($sql);
        $_SESSION['msg_success'] = "Video added.";
        header("Location: gallery_images.php?album_id=$album_id");
        exit;
    }

    // File Upload (Images or Video Files)
    elseif (isset($_FILES['images'])) {
        $target_dir = "../media/gallery/images/";
        if (!file_exists($target_dir)) { mkdir($target_dir, 0777, true); }

        $total = count($_FILES['images']['name']);

        for($i=0; $i<$total; $i++) {
            if ($_FILES['images']['error'][$i] === 0) {
                $file_ext = strtolower(pathinfo($_FILES['images']['name'][$i], PATHINFO_EXTENSION));
                $filename = time() . "_" . $i . "_" . basename($_FILES['images']['name'][$i]);
                $target_file = $target_dir . $filename;

                // Determine type if not explicitly set (or override if file uploaded)
                $type = $media_type;
                if(in_array($file_ext, ['mp4', 'webm', 'ogg'])) {
                    $type = 'video';
                } elseif(in_array($file_ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                    $type = 'image';
                }

                if (move_uploaded_file($_FILES['images']['tmp_name'][$i], $target_file)) {
                    $image_path = "media/gallery/images/" . $filename;
                    $conn->query("INSERT INTO gallery_images (album_id, image, caption, media_type) VALUES ($album_id, '$image_path', '$caption', '$type')");
                }
            }
        }
        $_SESSION['msg_success'] = "Files uploaded successfully.";
        header("Location: gallery_images.php?album_id=$album_id");
        exit;
    }
}

$images = $conn->query("SELECT * FROM gallery_images WHERE album_id = $album_id ORDER BY id DESC");
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
            <div class="card-header bg-success text-white">Add Media</div>
            <div class="card-body">
                <form method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label class="form-label">Media Type</label>
                        <select name="media_type" class="form-select" id="mediaType" onchange="toggleInputs()">
                            <option value="image">Image(s)</option>
                            <option value="video">Video (File or Embed)</option>
                        </select>
                    </div>

                    <div class="mb-3" id="fileInput">
                        <label class="form-label">Select Files</label>
                        <input type="file" name="images[]" class="form-control" multiple>
                        <small class="text-muted">Images (.jpg, .png) or Videos (.mp4)</small>
                    </div>

                    <div class="mb-3 d-none" id="embedInput">
                        <label class="form-label">Video Embed Code (YouTube/Vimeo)</label>
                        <textarea name="video_embed" class="form-control" rows="3" placeholder='<iframe src="..."></iframe>'></textarea>
                        <small class="text-muted">Paste the embed code here if not uploading a file.</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Caption (Optional)</label>
                        <input type="text" name="caption" class="form-control" placeholder="Description">
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Add Media</button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="row row-cols-1 row-cols-md-3 g-3">
            <?php while($img = $images->fetch_assoc()): ?>
            <div class="col">
                <div class="card h-100">
                    <?php if($img['media_type'] == 'video'): ?>
                        <?php if($img['video_embed']): ?>
                            <div class="ratio ratio-16x9 bg-dark text-white d-flex align-items-center justify-content-center">
                                <i class="fas fa-play-circle fa-2x"></i> Embed
                            </div>
                        <?php else: ?>
                            <video src="../<?php echo $img['image']; ?>" class="card-img-top" style="height: 150px; object-fit: cover;" muted></video>
                            <div class="position-absolute top-50 start-50 translate-middle text-white">
                                <i class="fas fa-play-circle fa-2x"></i>
                            </div>
                        <?php endif; ?>
                    <?php else: ?>
                        <img src="../<?php echo $img['image']; ?>" class="card-img-top" style="height: 150px; object-fit: cover;">
                    <?php endif; ?>

                    <div class="card-body p-2 text-center">
                        <small class="d-block text-truncate"><?php echo htmlspecialchars($img['caption']); ?></small>
                        <span class="badge bg-secondary mb-1"><?php echo ucfirst($img['media_type']); ?></span>
                        <a href="?album_id=<?php echo $album_id; ?>&delete=<?php echo $img['id']; ?>" class="text-danger d-block mt-1" onclick="return confirm('Delete media?')"><i class="fas fa-trash"></i> Delete</a>
                    </div>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
    </div>
</div>

<script>
function toggleInputs() {
    var type = document.getElementById('mediaType').value;
    var fileInput = document.getElementById('fileInput');
    var embedInput = document.getElementById('embedInput');

    if (type === 'video') {
        embedInput.classList.remove('d-none');
    } else {
        embedInput.classList.add('d-none');
    }
}
</script>

<?php require_once 'includes/footer.php'; ?>
