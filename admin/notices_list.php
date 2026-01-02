<?php
$page_title = "Manage Notices";
require_once 'includes/header.php';

// Handle Delete
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $conn->query("DELETE FROM notices WHERE id = $id");
    $_SESSION['msg_success'] = "Notice deleted successfully.";
    echo "<script>window.location.href='notices_list.php';</script>";
    exit;
}

$result = $conn->query("SELECT * FROM notices ORDER BY is_pinned DESC, date_posted DESC");
?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h4>Notices</h4>
    <a href="notices_form.php" class="btn btn-primary"><i class="fas fa-plus"></i> Add New</a>
</div>

<table class="table table-bordered table-hover bg-white shadow-sm">
    <thead class="table-light">
        <tr>
            <th>Date</th>
            <th>Title</th>
            <th>Pinned</th>
            <th>Attachment</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php while($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?php echo $row['date_posted']; ?></td>
            <td><?php echo htmlspecialchars($row['title']); ?></td>
            <td>
                <?php if($row['is_pinned']): ?>
                    <span class="badge bg-success">Yes</span>
                <?php else: ?>
                    <span class="badge bg-secondary">No</span>
                <?php endif; ?>
            </td>
            <td>
                <?php if($row['file']): ?>
                    <a href="../<?php echo $row['file']; ?>" target="_blank"><i class="fas fa-paperclip"></i> View</a>
                <?php else: ?>
                    -
                <?php endif; ?>
            </td>
            <td>
                <a href="notices_form.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-info text-white"><i class="fas fa-edit"></i></a>
                <a href="?delete=<?php echo $row['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')"><i class="fas fa-trash"></i></a>
            </td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>

<?php require_once 'includes/footer.php'; ?>
