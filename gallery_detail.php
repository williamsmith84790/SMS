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

// Fetch Images/Videos
$images_sql = "SELECT * FROM gallery_images WHERE album_id = $album_id ORDER BY id DESC";
$images_result = $conn->query($images_sql);
?>

<!-- Lightbox CSS -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/glightbox/3.2.0/css/glightbox.min.css" rel="stylesheet" />

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
        while($media = $images_result->fetch_assoc()) {
            $is_video = ($media['media_type'] == 'video');
            $src = htmlspecialchars($media['image']);
            $video_embed = $media['video_embed'];

            // Determine href for lightbox
            if ($is_video) {
                // If embed code exists, we need to extract src if simple, or just use a placeholder mechanism.
                // GLightbox handles URLs nicely.
                if ($video_embed) {
                    // Try to extract src from iframe if user pasted full iframe
                    if (preg_match('/src="([^"]+)"/', $video_embed, $match)) {
                        $href = $match[1];
                    } else {
                        // Fallback or if it's just a URL
                        $href = $video_embed; // Assuming user might paste URL or iframe
                    }
                } else {
                    $href = $src; // Video file path
                }
            } else {
                $href = $src;
            }
            ?>
            <div class="col">
                <a href="<?php echo $href; ?>" class="glightbox" data-gallery="album-<?php echo $album_id; ?>" data-type="<?php echo $is_video ? 'video' : 'image'; ?>" data-description="<?php echo htmlspecialchars($media['caption']); ?>">
                    <div class="card h-100 border-0 shadow-sm position-relative overflow-hidden group-hover">
                        <?php if($is_video): ?>
                            <div class="ratio ratio-4x3 bg-dark d-flex align-items-center justify-content-center">
                                <i class="fas fa-play-circle fa-3x text-white position-absolute top-50 start-50 translate-middle" style="z-index: 10;"></i>
                                <?php if(!$video_embed): ?>
                                    <video src="<?php echo $src; ?>" class="w-100 h-100" style="object-fit: cover; opacity: 0.7;"></video>
                                <?php else: ?>
                                    <!-- Thumbnail for embed? Just a placeholder icon -->
                                    <div class="w-100 h-100 d-flex align-items-center justify-content-center bg-secondary text-white">
                                        Video
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php else: ?>
                            <img src="<?php echo $src; ?>" class="img-thumbnail w-100 p-0 border-0" style="height: 200px; object-fit: cover; transition: transform 0.3s;" alt="<?php echo htmlspecialchars($media['caption']); ?>">
                        <?php endif; ?>

                        <div class="card-img-overlay d-flex align-items-end p-0">
                            <?php if($media['caption']): ?>
                            <div class="bg-dark bg-opacity-75 text-white w-100 p-2 small text-truncate">
                                <?php echo htmlspecialchars($media['caption']); ?>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </a>
            </div>
            <?php
        }
    } else {
        echo '<div class="col-12"><p class="text-muted">No media in this album yet.</p></div>';
    }
    ?>
</div>

<!-- Lightbox JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/glightbox/3.2.0/js/glightbox.min.js"></script>
<script>
    const lightbox = GLightbox({
        touchNavigation: true,
        loop: true,
        autoplayVideos: true
    });
</script>

<style>
    .glightbox:hover img { transform: scale(1.05); }
    .glightbox:hover .fa-play-circle { transform: translate(-50%, -50%) scale(1.1); transition: 0.3s; color: var(--secondary-color) !important; }
</style>

<?php require_once 'includes/footer.php'; ?>
