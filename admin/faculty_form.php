<?php
$page_title = "Faculty Member";
require_once 'includes/header.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : null;
$faculty = null;

if ($id) {
    $result = $conn->query("SELECT * FROM faculty_members WHERE id = $id");
    if ($result->num_rows > 0) {
        $faculty = $result->fetch_assoc();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $conn->real_escape_string($_POST['name']);
    $designation = $conn->real_escape_string($_POST['designation']);
    $department = $conn->real_escape_string($_POST['department']);
    $bio = $conn->real_escape_string($_POST['bio']);
    $order = (int)$_POST['order'];

    // Image Upload
    $image_path = $faculty ? $faculty['image'] : '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $target_dir = "../media/faculty/";
        if (!file_exists($target_dir)) { mkdir($target_dir, 0777, true); }

        $filename = time() . "_" . basename($_FILES['image']['name']);
        $target_file = $target_dir . $filename;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
            $image_path = "media/faculty/" . $filename;
        }
    }

    if ($id) {
        $sql = "UPDATE faculty_members SET name='$name', designation='$designation', department='$department', bio='$bio', `order`=$order, image='$image_path' WHERE id=$id";
    } else {
        $sql = "INSERT INTO faculty_members (name, designation, department, bio, `order`, image) VALUES ('$name', '$designation', '$department', '$bio', $order, '$image_path')";
    }

    if ($conn->query($sql)) {
        $_SESSION['msg_success'] = "Faculty member saved successfully.";
        echo "<script>window.location.href='faculty_list.php';</script>";
        exit;
    } else {
        $error = "Error: " . $conn->error;
    }
}
?>

<div class="row">
    <div class="col-md-8 offset-md-2">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><?php echo $id ? 'Edit' : 'Add'; ?> Faculty Member</h5>
            </div>
            <div class="card-body">
                <?php if(isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
                <form method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label class="form-label">Name</label>
                        <input type="text" name="name" class="form-control" required value="<?php echo $faculty ? htmlspecialchars($faculty['name']) : ''; ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Designation</label>
                        <input type="text" name="designation" class="form-control" required value="<?php echo $faculty ? htmlspecialchars($faculty['designation']) : ''; ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Department</label>
                        <input type="text" name="department" class="form-control" value="<?php echo $faculty ? htmlspecialchars($faculty['department']) : ''; ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Sort Order</label>
                        <input type="number" name="order" class="form-control" value="<?php echo $faculty ? $faculty['order'] : '0'; ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Profile Image</label>
                        <?php if($faculty && $faculty['image']): ?>
                            <div class="mb-2"><img src="../<?php echo $faculty['image']; ?>" height="100"></div>
                        <?php endif; ?>
                        <input type="file" name="image" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Bio (Rich Text)</label>
                        <textarea name="bio" class="form-control summernote"><?php echo $faculty ? $faculty['bio'] : ''; ?></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                    <a href="faculty_list.php" class="btn btn-secondary">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
