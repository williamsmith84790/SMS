<?php
$page_title = "Manage Events";
require_once 'includes/header.php';

if (!has_permission('events')) {
    echo '<div class="alert alert-danger">You do not have permission to access this page.</div>';
    require_once 'includes/footer.php';
    exit;
}

// Handle Delete
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $conn->query("DELETE FROM events WHERE id = $id");
    $_SESSION['msg_success'] = "Event deleted successfully.";
    echo "<script>window.location.href='events_list.php';</script>";
    exit;
}

$result = $conn->query("SELECT * FROM events ORDER BY date DESC");
?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h4>Events</h4>
    <a href="events_form.php" class="btn btn-primary"><i class="fas fa-plus"></i> Add New</a>
</div>

<table class="table table-bordered table-hover bg-white shadow-sm">
    <thead class="table-light">
        <tr>
            <th>Date</th>
            <th>Title</th>
            <th>Location</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php while($row = $result->fetch_assoc()): ?>
        <tr>
            <td>
                <?php echo date('d M Y', strtotime($row['date'])); ?>
                <?php if($row['time']): ?><br><small class="text-muted"><?php echo htmlspecialchars($row['time']); ?></small><?php endif; ?>
            </td>
            <td><?php echo htmlspecialchars($row['title']); ?></td>
            <td><?php echo htmlspecialchars($row['location']); ?></td>
            <td>
                <a href="events_form.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-info text-white"><i class="fas fa-edit"></i></a>
                <a href="?delete=<?php echo $row['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')"><i class="fas fa-trash"></i></a>
            </td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>

<?php require_once 'includes/footer.php'; ?>
