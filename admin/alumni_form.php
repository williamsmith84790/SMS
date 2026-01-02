<?php
$page_title = "Alumni Member";
require_once 'includes/header.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : null;
$alumni = null;

if ($id) {
    $result = $conn->query("SELECT * FROM alumni WHERE id = $id");
    if ($result->num_rows > 0) {
        $alumni = $result->fetch_assoc();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $conn->real_escape_string($_POST['name']);
    $batch = $conn->real_escape_string($_POST['batch']);
    $achievement = $conn->real_escape_string($_POST['achievement']);

    // Image Upload
    $image_path = $alumni ? $alumni['image'] : '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $target_dir = "../media/alumni/";
        if (!file_exists($target_dir)) { mkdir($target_dir, 0777, true); }

        $filename = time() . "_" . basename($_FILES['image']['name']);
        $target_file = $target_dir . $filename;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
            $image_path = "media/alumni/" . $filename;
        }
    }

    if ($id) {
        $sql = "UPDATE alumni SET name='$name', batch='$batch', achievement='$achievement', image='$image_path' WHERE id=$id";
    } else {
        $sql = "INSERT INTO alumni (name, batch, achievement, image) VALUES ('$name', '$batch', '$achievement', '$image_path')";
    }

    if ($conn->query($sql)) {
        $_SESSION['msg_success'] = "Alumni saved successfully.";
        echo "<script>window.location.href='alumni_list.php';</script>";
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
                <h5 class="mb-0"><?php echo $id ? 'Edit' : 'Add'; ?> Alumni</h5>
            </div>
            <div class="card-body">
                <?php if(isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
                <form method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label class="form-label">Name</label>
                        <input type="text" name="name" class="form-control" required value="<?php echo $alumni ? htmlspecialchars($alumni['name']) : ''; ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Batch (Year)</label>
                        <input type="text" name="batch" class="form-control" required value="<?php echo $alumni ? htmlspecialchars($alumni['batch']) : ''; ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Achievement / Designation</label>
                        <textarea name="achievement" class="form-control" rows="3"><?php echo $alumni ? htmlspecialchars($alumni['achievement']) : ''; ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Photo</label>
                        <?php if($alumni && $alumni['image']): ?>
                            <div class="mb-2"><img src="../<?php echo $alumni['image']; ?>" height="100"></div>
                        <?php endif; ?>
                        <input type="file" name="image" class="form-control">
                    </div>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                    <a href="alumni_list.php" class="btn btn-secondary">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
