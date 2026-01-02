<?php
$page_title = "Slider Image";
require_once 'includes/header.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : null;
$slider = null;

if ($id) {
    $result = $conn->query("SELECT * FROM slider_images WHERE id = $id");
    if ($result->num_rows > 0) {
        $slider = $result->fetch_assoc();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $conn->real_escape_string($_POST['title']);
    $order = (int)$_POST['order'];
    $is_active = isset($_POST['is_active']) ? 1 : 0;

    // Image Upload
    $image_path = $slider ? $slider['image'] : '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $target_dir = "../media/slider/";
        if (!file_exists($target_dir)) { mkdir($target_dir, 0777, true); }

        $filename = time() . "_" . basename($_FILES['image']['name']);
        $target_file = $target_dir . $filename;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
            $image_path = "media/slider/" . $filename;
        }
    }

    if ($id) {
        $sql = "UPDATE slider_images SET title='$title', `order`=$order, is_active=$is_active, image='$image_path' WHERE id=$id";
    } else {
        if (empty($image_path)) {
            $error = "Image is required.";
        } else {
            $sql = "INSERT INTO slider_images (title, `order`, is_active, image) VALUES ('$title', $order, $is_active, '$image_path')";
        }
    }

    if (!isset($error)) {
        if ($conn->query($sql)) {
            $_SESSION['msg_success'] = "Slider image saved successfully.";
            echo "<script>window.location.href='slider_list.php';</script>";
            exit;
        } else {
            $error = "Error: " . $conn->error;
        }
    }
}
?>

<div class="row">
    <div class="col-md-6 offset-md-3">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><?php echo $id ? 'Edit' : 'Add'; ?> Slider Image</h5>
            </div>
            <div class="card-body">
                <?php if(isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
                <form method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label class="form-label">Title (Optional)</label>
                        <input type="text" name="title" class="form-control" value="<?php echo $slider ? htmlspecialchars($slider['title']) : ''; ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Sort Order</label>
                        <input type="number" name="order" class="form-control" value="<?php echo $slider ? $slider['order'] : '0'; ?>">
                    </div>
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" name="is_active" id="is_active" <?php echo ($slider && $slider['is_active']) ? 'checked' : 'checked'; ?>>
                        <label class="form-check-label" for="is_active">Active?</label>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Image</label>
                        <?php if($slider && $slider['image']): ?>
                            <div class="mb-2"><img src="../<?php echo $slider['image']; ?>" width="100%"></div>
                        <?php endif; ?>
                        <input type="file" name="image" class="form-control" <?php echo $id ? '' : 'required'; ?>>
                    </div>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                    <a href="slider_list.php" class="btn btn-secondary">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
