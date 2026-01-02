<?php
require_once '../config.php';
require_once 'includes/header.php';

$admin_id = $_SESSION['admin_id'];
$success = "";
$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    if ($new_password !== $confirm_password) {
        $error = "New passwords do not match.";
    } else {
        // Fetch current password hash
        $sql = "SELECT password FROM admins WHERE id = $admin_id";
        $result = $conn->query($sql);
        if ($result && $result->num_rows === 1) {
            $user = $result->fetch_assoc();
            if (password_verify($current_password, $user['password'])) {
                // Update password
                $new_hash = password_hash($new_password, PASSWORD_DEFAULT);
                $update_sql = "UPDATE admins SET password = '$new_hash' WHERE id = $admin_id";
                if ($conn->query($update_sql)) {
                    $success = "Password updated successfully.";
                } else {
                    $error = "Error updating password: " . $conn->error;
                }
            } else {
                $error = "Incorrect current password.";
            }
        } else {
            $error = "User not found.";
        }
    }
}
?>

<div class="row">
    <div class="col-md-6 offset-md-3">
        <div class="card">
            <div class="card-header">
                Manage Profile
            </div>
            <div class="card-body">
                <h5 class="card-title">Change Password</h5>
                <?php if ($success): ?>
                    <div class="alert alert-success"><?php echo $success; ?></div>
                <?php endif; ?>
                <?php if ($error): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>

                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label">Current Password</label>
                        <input type="password" name="current_password" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">New Password</label>
                        <input type="password" name="new_password" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Confirm New Password</label>
                        <input type="password" name="confirm_password" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Update Password</button>
                </form>
            </div>
        </div>

        <div class="card mt-4">
             <div class="card-header">
                Profile Info
            </div>
            <div class="card-body">
                <p><strong>Username:</strong> <?php echo $_SESSION['admin_username']; ?></p>
                <p><strong>Permissions:</strong>
                <?php
                    if ($_SESSION['admin_id'] == 1) {
                        echo '<span class="badge bg-success">Super Admin (All Permissions)</span>';
                    } else {
                         $perms = $_SESSION['admin_permissions'];
                         if (empty($perms)) {
                             echo '<span class="badge bg-secondary">None</span>';
                         } else {
                             $perm_list = explode(',', $perms);
                             foreach ($perm_list as $p) {
                                 echo '<span class="badge bg-info me-1">' . htmlspecialchars(ucfirst(str_replace('_', ' ', $p))) . '</span>';
                             }
                         }
                    }
                ?>
                </p>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
