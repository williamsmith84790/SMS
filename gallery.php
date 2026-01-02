<?php
$page_title = "Gallery";
require_once 'includes/header.php';

// Fetch Albums
$album_sql = "SELECT * FROM gallery_albums ORDER BY created_at DESC";
$album_result = $conn->query($album_sql);

// Fetch Videos
$video_sql = "SELECT * FROM videos ORDER BY id DESC";
$video_result = $conn->query($video_sql);
?>

<h2 class="mb-4 border-bottom pb-2">Photo Gallery</h2>

<div class="row row-cols-1 row-cols-md-3 g-4 mb-5">
    <?php
    if ($album_result && $album_result->num_rows > 0) {
        while($album = $album_result->fetch_assoc()) {
            ?>
            <div class="col">
                <div class="card h-100 shadow-sm">
                    <img src="<?php echo htmlspecialchars($album['cover_image']); ?>" class="card-img-top gallery-cover" alt="<?php echo htmlspecialchars($album['title']); ?>">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo htmlspecialchars($album['title']); ?></h5>
                        <p class="card-text text-muted small"><?php echo htmlspecialchars($album['description']); ?></p>
                        <a href="gallery_detail.php?id=<?php echo $album['id']; ?>" class="btn btn-outline-primary btn-sm stretched-link">View Album</a>
                    </div>
                    <div class="card-footer text-muted small">
                        <?php echo date('F d, Y', strtotime($album['created_at'])); ?>
                    </div>
                </div>
            </div>
            <?php
        }
    } else {
        echo '<div class="col-12"><p class="text-muted">No photo albums available.</p></div>';
    }
    ?>
</div>

<h2 class="mb-4 border-bottom pb-2">Video Gallery</h2>

<div class="row row-cols-1 row-cols-md-2 g-4">
    <?php
    if ($video_result && $video_result->num_rows > 0) {
        while($video = $video_result->fetch_assoc()) {
            // Simple logic to embed YouTube/Vimeo would go here. For now, just a link or basic iframe if it's a direct embed URL.
            // Assuming the user pastes the full URL, let's try to parse a simple YouTube ID for an iframe or just link it.
            $video_url = $video['video_url'];
            $embed_code = '';

            if (strpos($video_url, 'youtube.com/watch?v=') !== false) {
                parse_str(parse_url($video_url, PHP_URL_QUERY), $params);
                if (isset($params['v'])) {
                    $embed_code = '<iframe class="w-100" height="300" src="https://www.youtube.com/embed/'.$params['v'].'" frameborder="0" allowfullscreen></iframe>';
                }
            } elseif (strpos($video_url, 'youtu.be/') !== false) {
                $path = parse_url($video_url, PHP_URL_PATH);
                $vid = ltrim($path, '/');
                $embed_code = '<iframe class="w-100" height="300" src="https://www.youtube.com/embed/'.$vid.'" frameborder="0" allowfullscreen></iframe>';
            } else {
                // Fallback link
                $embed_code = '<div class="ratio ratio-16x9 bg-dark d-flex align-items-center justify-content-center"><a href="'.htmlspecialchars($video_url).'" target="_blank" class="btn btn-light"><i class="fas fa-play"></i> Watch Video</a></div>';
            }
            ?>
            <div class="col">
                <div class="card h-100 shadow-sm">
                    <div class="card-body p-0">
                        <?php echo $embed_code; ?>
                    </div>
                    <div class="card-body">
                        <h5 class="card-title"><?php echo htmlspecialchars($video['title']); ?></h5>
                        <p class="card-text small"><?php echo htmlspecialchars($video['description']); ?></p>
                    </div>
                </div>
            </div>
            <?php
        }
    } else {
        echo '<div class="col-12"><p class="text-muted">No videos available.</p></div>';
    }
    ?>
</div>

<?php require_once 'includes/footer.php'; ?>
