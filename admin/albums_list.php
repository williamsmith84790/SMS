<?php
$page_title = "Manage Gallery Albums";
require_once 'includes/header.php';

if (!has_permission('gallery')) {
    echo '<div class="alert alert-danger">You do not have permission to access this page.</div>';
    require_once 'includes/footer.php';
    exit;
}

// Handle Delete
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $conn->query("DELETE FROM gallery_albums WHERE id = $id");
    $_SESSION['msg_success'] = "Album deleted successfully.";
    echo "<script>window.location.href='albums_list.php';</script>";
    exit;
}

$result = $conn->query("SELECT * FROM gallery_albums ORDER BY created_at DESC");
?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h4>Gallery Albums</h4>
    <a href="albums_form.php" class="btn btn-primary"><i class="fas fa-plus"></i> Create New Album</a>
</div>

<div class="row row-cols-1 row-cols-md-3 g-4">
    <?php while($row = $result->fetch_assoc()): ?>
    <div class="col">
        <div class="card h-100 shadow-sm">
            <img src="../<?php echo $row['cover_image']; ?>" class="card-img-top" style="height: 200px; object-fit: cover;">
            <div class="card-body">
                <h5 class="card-title"><?php echo htmlspecialchars($row['title']); ?></h5>
                <p class="card-text text-muted small"><?php echo htmlspecialchars($row['description']); ?></p>
            </div>
            <div class="card-footer bg-white d-flex justify-content-between">
                <a href="gallery_images.php?album_id=<?php echo $row['id']; ?>" class="btn btn-sm btn-success"><i class="fas fa-images"></i> Manage Photos</a>
                <div>
                    <a href="albums_form.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-info text-white"><i class="fas fa-edit"></i></a>
                    <a href="?delete=<?php echo $row['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete album and all photos?')"><i class="fas fa-trash"></i></a>
                </div>
            </div>
        </div>
    </div>
    <?php endwhile; ?>
</div>

<?php require_once 'includes/footer.php'; ?>
