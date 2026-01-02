<?php
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: gallery.php");
    exit;
}

require_once 'config.php';
$album_id = (int)$_GET['id'];

// Fetch Album Details
$album_sql = "SELECT * FROM gallery_albums WHERE id = $album_id";
$album_result = $conn->query($album_sql);

if (!$album_result || $album_result->num_rows == 0) {
    header("Location: gallery.php");
    exit;
}

$album = $album_result->fetch_assoc();
$page_title = $album['title'];

require_once 'includes/header.php';

// Fetch Images
$images_sql = "SELECT * FROM gallery_images WHERE album_id = $album_id";
$images_result = $conn->query($images_sql);
?>

<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="gallery.php">Gallery</a></li>
    <li class="breadcrumb-item active" aria-current="page"><?php echo htmlspecialchars($album['title']); ?></li>
  </ol>
</nav>

<h2 class="mb-3"><?php echo htmlspecialchars($album['title']); ?></h2>
<p class="text-muted"><?php echo htmlspecialchars($album['description']); ?></p>

<div class="row row-cols-1 row-cols-md-4 g-3 mt-4">
    <?php
    if ($images_result && $images_result->num_rows > 0) {
        while($img = $images_result->fetch_assoc()) {
            ?>
            <div class="col">
                <a href="<?php echo htmlspecialchars($img['image']); ?>" target="_blank">
                    <img src="<?php echo htmlspecialchars($img['image']); ?>" class="img-thumbnail w-100" style="height: 200px; object-fit: cover;" alt="<?php echo htmlspecialchars($img['caption'] ?: 'Gallery Image'); ?>">
                </a>
                <?php if($img['caption']): ?>
                    <p class="text-center small mt-1 text-muted"><?php echo htmlspecialchars($img['caption']); ?></p>
                <?php endif; ?>
            </div>
            <?php
        }
    } else {
        echo '<div class="col-12"><p class="text-muted">No images in this album yet.</p></div>';
    }
    ?>
</div>

<?php require_once 'includes/footer.php'; ?>
