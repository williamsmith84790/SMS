<?php
require_once '../config.php';
require_once 'auth_check.php';

// Check permissions
if (!has_permission('urgent_alerts')) {
    require_once 'includes/header.php';
    echo '<div class="alert alert-danger">You do not have permission to access this page.</div>';
    require_once 'includes/footer.php';
    exit;
}

// Handle Delete
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $conn->query("DELETE FROM urgent_alerts WHERE id = $id");
    header("Location: alerts_list.php");
    exit;
}

// Handle POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $conn->real_escape_string($_POST['title']);
    $message = $conn->real_escape_string($_POST['message']);
    $is_active = isset($_POST['is_active']) ? 1 : 0;

    // Allow multiple active alerts
    // if ($is_active) {
    //    $conn->query("UPDATE urgent_alerts SET is_active = 0");
    // }

    $link = $conn->real_escape_string($_POST['link']);

    if (isset($_POST['id']) && !empty($_POST['id'])) {
        $id = (int)$_POST['id'];
        $sql = "UPDATE urgent_alerts SET title='$title', message='$message', link='$link', is_active=$is_active WHERE id=$id";
    } else {
        $sql = "INSERT INTO urgent_alerts (title, message, link, is_active) VALUES ('$title', '$message', '$link', $is_active)";
    }

    $conn->query($sql);
    header("Location: alerts_list.php");
    exit;
}

$page_title = "Manage Urgent Alerts";
require_once 'includes/header.php';

$edit_item = null;
if (isset($_GET['edit'])) {
    $id = (int)$_GET['edit'];
    $result = $conn->query("SELECT * FROM urgent_alerts WHERE id = $id");
    $edit_item = $result->fetch_assoc();
}

$result = $conn->query("SELECT * FROM urgent_alerts ORDER BY created_at DESC");
?>

<div class="row">
    <div class="col-md-4">
        <div class="card">
            <div class="card-header bg-danger text-white">
                <h5 class="mb-0"><?php echo $edit_item ? 'Edit' : 'Add'; ?> Alert</h5>
            </div>
            <div class="card-body">
                <form method="POST">
                    <?php if($edit_item): ?>
                        <input type="hidden" name="id" value="<?php echo $edit_item['id']; ?>">
                    <?php endif; ?>
                    <div class="mb-3">
                        <label class="form-label">Title</label>
                        <input type="text" name="title" class="form-control" required value="<?php echo $edit_item ? htmlspecialchars($edit_item['title']) : ''; ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Message</label>
                        <textarea name="message" class="form-control" rows="4" required><?php echo $edit_item ? htmlspecialchars($edit_item['message']) : ''; ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Link (Optional)</label>
                        <input type="text" name="link" class="form-control" placeholder="e.g., http://google.com" value="<?php echo $edit_item && isset($edit_item['link']) ? htmlspecialchars($edit_item['link']) : ''; ?>">
                    </div>
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" name="is_active" id="is_active" <?php echo ($edit_item && $edit_item['is_active']) ? 'checked' : ''; ?>>
                        <label class="form-check-label" for="is_active">Active (Shows Pop-up)</label>
                    </div>
                    <button type="submit" class="btn btn-primary w-100"><?php echo $edit_item ? 'Update' : 'Add'; ?></button>
                    <?php if($edit_item): ?>
                        <a href="alerts_list.php" class="btn btn-secondary w-100 mt-2">Cancel</a>
                    <?php endif; ?>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Alert History</h5>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['title']); ?></td>
                            <td><?php echo date('d M Y', strtotime($row['created_at'])); ?></td>
                            <td>
                                <span class="badge <?php echo $row['is_active'] ? 'bg-danger' : 'bg-secondary'; ?>">
                                    <?php echo $row['is_active'] ? 'Active' : 'Inactive'; ?>
                                </span>
                            </td>
                            <td>
                                <a href="?edit=<?php echo $row['id']; ?>" class="btn btn-sm btn-info text-white"><i class="fas fa-edit"></i></a>
                                <a href="?delete=<?php echo $row['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete?')"><i class="fas fa-trash"></i></a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
