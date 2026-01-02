<?php
require_once '../config.php';
require_once 'includes/header.php';

if (!has_permission('manage_users')) {
    echo '<div class="alert alert-danger">You do not have permission to access this page.</div>';
    require_once 'includes/footer.php';
    exit;
}

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$user = null;
$username = '';
$permissions = [];
$is_edit = false;

// Define available permissions
$available_permissions = [
    'sliders' => 'Manage Sliders',
    'menus' => 'Manage Menus',
    'news_ticker' => 'Manage News Ticker',
    'events' => 'Manage Events',
    'urgent_alerts' => 'Manage Urgent Alerts',
    'notices' => 'Manage Notices',
    'pages' => 'Manage Pages',
    'faculty' => 'Manage Faculty',
    'gallery' => 'Manage Gallery',
    'alumni' => 'Manage Alumni',
    'downloads' => 'Manage Downloads',
    'results' => 'Manage Results',
    'settings' => 'Manage Settings',
    'manage_users' => 'Manage Users'
];

if ($id) {
    $result = $conn->query("SELECT * FROM admins WHERE id = $id");
    if ($result->num_rows) {
        $user = $result->fetch_assoc();
        $username = $user['username'];
        $permissions = !empty($user['permissions']) ? explode(',', $user['permissions']) : [];
        $is_edit = true;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username_post = $conn->real_escape_string($_POST['username']);
    $password_post = $_POST['password'];
    // Sanitize permissions
    $permissions_input = isset($_POST['permissions']) ? $_POST['permissions'] : [];
    $sanitized_permissions = [];
    foreach ($permissions_input as $perm) {
        if (array_key_exists($perm, $available_permissions)) {
            $sanitized_permissions[] = $perm;
        }
    }
    $permissions_post = $conn->real_escape_string(implode(',', $sanitized_permissions));

    if ($is_edit) {
        // Update
        if (!empty($password_post)) {
            $password_hash = password_hash($password_post, PASSWORD_DEFAULT);
            $sql = "UPDATE admins SET username = '$username_post', password = '$password_hash', permissions = '$permissions_post' WHERE id = $id";
        } else {
            $sql = "UPDATE admins SET username = '$username_post', permissions = '$permissions_post' WHERE id = $id";
        }
    } else {
        // Insert
        if (empty($password_post)) {
            $error = "Password is required for new users.";
        } else {
            $password_hash = password_hash($password_post, PASSWORD_DEFAULT);
            $sql = "INSERT INTO admins (username, password, permissions) VALUES ('$username_post', '$password_hash', '$permissions_post')";
        }
    }

    if (!isset($error)) {
        if ($conn->query($sql)) {
            $_SESSION['msg_success'] = "User saved successfully.";
            echo "<script>window.location.href='users_list.php';</script>";
            exit;
        } else {
            $error = "Error saving user: " . $conn->error;
        }
    }
}
?>

<div class="card">
    <div class="card-header">
        <?php echo $is_edit ? 'Edit User' : 'Add New User'; ?>
    </div>
    <div class="card-body">
        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Username</label>
                <input type="text" name="username" class="form-control" value="<?php echo htmlspecialchars($username); ?>" required <?php echo ($is_edit && $id == 1) ? 'readonly' : ''; ?>>
            </div>

            <div class="mb-3">
                <label class="form-label">Password <?php echo $is_edit ? '(Leave blank to keep current)' : ''; ?></label>
                <input type="password" name="password" class="form-control" <?php echo $is_edit ? '' : 'required'; ?>>
            </div>

            <?php if ($id != 1): ?>
            <div class="mb-3">
                <label class="form-label">Permissions</label>
                <div class="row">
                    <?php foreach ($available_permissions as $key => $label): ?>
                    <div class="col-md-4">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="permissions[]" value="<?php echo $key; ?>" id="perm_<?php echo $key; ?>" <?php echo in_array($key, $permissions) ? 'checked' : ''; ?>>
                            <label class="form-check-label" for="perm_<?php echo $key; ?>">
                                <?php echo $label; ?>
                            </label>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php else: ?>
                <div class="alert alert-info">Super Admin has all permissions.</div>
            <?php endif; ?>

            <button type="submit" class="btn btn-primary">Save User</button>
            <a href="users_list.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
