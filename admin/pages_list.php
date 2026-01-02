<?php
$page_title = "Manage Pages";
require_once 'includes/header.php';

if (!has_permission('pages')) {
    echo '<div class="alert alert-danger">You do not have permission to access this page.</div>';
    require_once 'includes/footer.php';
    exit;
}

// Handle Delete
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    // Prevent deletion of critical pages if necessary, but for now allow all
    $conn->query("DELETE FROM pages WHERE id = $id");
    $_SESSION['msg_success'] = "Page deleted successfully.";
    echo "<script>window.location.href='pages_list.php';</script>";
    exit;
}

$result = $conn->query("SELECT * FROM pages ORDER BY title ASC");
?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h4>Pages</h4>
    <a href="pages_form.php" class="btn btn-primary"><i class="fas fa-plus"></i> Add New</a>
</div>

<table class="table table-bordered table-hover bg-white shadow-sm">
    <thead class="table-light">
        <tr>
            <th>Title</th>
            <th>Slug (URL)</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php while($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?php echo htmlspecialchars($row['title']); ?></td>
            <td><?php echo htmlspecialchars($row['slug']); ?></td>
            <td>
                <a href="../page.php?slug=<?php echo $row['slug']; ?>" target="_blank" class="btn btn-sm btn-secondary"><i class="fas fa-eye"></i></a>
                <a href="pages_form.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-info text-white"><i class="fas fa-edit"></i></a>
                <a href="?delete=<?php echo $row['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')"><i class="fas fa-trash"></i></a>
            </td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>

<?php require_once 'includes/footer.php'; ?>
