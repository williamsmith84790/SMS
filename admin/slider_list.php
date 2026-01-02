<?php
$page_title = "Manage Slider Images";
require_once 'includes/header.php';

// Handle Delete
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $conn->query("DELETE FROM slider_images WHERE id = $id");
    $_SESSION['msg_success'] = "Image deleted successfully.";
    echo "<script>window.location.href='slider_list.php';</script>";
    exit;
}

// Handle Toggle Status
if (isset($_GET['toggle'])) {
    $id = (int)$_GET['toggle'];
    $conn->query("UPDATE slider_images SET is_active = NOT is_active WHERE id = $id");
    header("Location: slider_list.php");
    exit;
}

$result = $conn->query("SELECT * FROM slider_images ORDER BY `order` ASC");
?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h4>Slider Images</h4>
    <a href="slider_form.php" class="btn btn-primary"><i class="fas fa-plus"></i> Add New</a>
</div>

<table class="table table-bordered table-hover bg-white shadow-sm">
    <thead class="table-light">
        <tr>
            <th>Order</th>
            <th>Image</th>
            <th>Title</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php while($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?php echo $row['order']; ?></td>
            <td><img src="../<?php echo $row['image']; ?>" height="60"></td>
            <td><?php echo htmlspecialchars($row['title']); ?></td>
            <td>
                <a href="?toggle=<?php echo $row['id']; ?>" class="badge <?php echo $row['is_active'] ? 'bg-success' : 'bg-secondary'; ?> text-decoration-none">
                    <?php echo $row['is_active'] ? 'Active' : 'Inactive'; ?>
                </a>
            </td>
            <td>
                <a href="slider_form.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-info text-white"><i class="fas fa-edit"></i></a>
                <a href="?delete=<?php echo $row['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')"><i class="fas fa-trash"></i></a>
            </td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>

<?php require_once 'includes/footer.php'; ?>
