<?php
require_once '../config.php';
require_once 'includes/header.php';

// Check if user has permission to manage users
if (!has_permission('manage_users')) {
    echo '<div class="alert alert-danger">You do not have permission to access this page.</div>';
    require_once 'includes/footer.php';
    exit;
}

// Handle Delete
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    if ($id != 1 && $id != $_SESSION['admin_id']) { // Cannot delete super admin or self
        $conn->query("DELETE FROM admins WHERE id = $id");
        $_SESSION['msg_success'] = "User deleted successfully.";
        echo "<script>window.location.href='users_list.php';</script>";
        exit;
    } else {
        $_SESSION['msg_error'] = "You cannot delete this user.";
    }
}

// Fetch Users
$result = $conn->query("SELECT * FROM admins ORDER BY id ASC");
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>User Management</h2>
    <a href="users_form.php" class="btn btn-primary"><i class="fas fa-plus"></i> Add New User</a>
</div>

<div class="card">
    <div class="card-body p-0">
        <table class="table table-striped table-hover mb-0">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Created At</th>
                    <th>Permissions</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo htmlspecialchars($row['username']); ?></td>
                    <td><?php echo date('M d, Y', strtotime($row['created_at'])); ?></td>
                    <td>
                        <?php
                        if ($row['id'] == 1) {
                            echo '<span class="badge bg-success">Super Admin</span>';
                        } elseif (empty($row['permissions'])) {
                            echo '<span class="badge bg-secondary">None</span>';
                        } else {
                            $perms = explode(',', $row['permissions']);
                            $count = count($perms);
                            if ($count > 3) {
                                echo '<span class="badge bg-info">' . $count . ' permissions</span>';
                            } else {
                                foreach ($perms as $p) {
                                    echo '<span class="badge bg-info me-1">' . htmlspecialchars(str_replace('_', ' ', $p)) . '</span>';
                                }
                            }
                        }
                        ?>
                    </td>
                    <td>
                        <a href="users_form.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></a>
                        <?php if ($row['id'] != 1 && $row['id'] != $_SESSION['admin_id']): ?>
                            <a href="users_list.php?delete=<?php echo $row['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this user?');"><i class="fas fa-trash"></i></a>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
