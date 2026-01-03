<?php
require_once '../config.php';
require_once 'auth_check.php';

// Check permissions early
if (!has_permission('settings')) {
    $page_title = "Manage Stats";
    require_once 'includes/header.php';
    echo '<div class="alert alert-danger">You do not have permission to access this page.</div>';
    require_once 'includes/footer.php';
    exit;
}

// Handle Delete before header
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $conn->query("DELETE FROM site_stats WHERE id = $id");
    header("Location: stats_list.php");
    exit;
}

$page_title = "Manage Stats";
require_once 'includes/header.php';

$result = $conn->query("SELECT * FROM site_stats ORDER BY sort_order ASC");
?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h4>Homepage Stats (Badges)</h4>
    <a href="stats_form.php" class="btn btn-primary"><i class="fas fa-plus"></i> Add New</a>
</div>

<div class="card">
    <div class="card-body p-0">
        <table class="table table-striped mb-0">
            <thead>
                <tr>
                    <th>Order</th>
                    <th>Icon</th>
                    <th>Number</th>
                    <th>Label</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['sort_order']; ?></td>
                    <td><i class="<?php echo htmlspecialchars($row['icon']); ?> fa-lg"></i> (<?php echo htmlspecialchars($row['icon']); ?>)</td>
                    <td><?php echo htmlspecialchars($row['number']); ?></td>
                    <td><?php echo htmlspecialchars($row['label']); ?></td>
                    <td>
                        <a href="stats_form.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></a>
                        <a href="?delete=<?php echo $row['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete?');"><i class="fas fa-trash"></i></a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
