<?php
$page_title = "Event Details";
require_once 'includes/header.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : null;
$event = null;

if ($id) {
    $result = $conn->query("SELECT * FROM events WHERE id = $id");
    if ($result->num_rows > 0) {
        $event = $result->fetch_assoc();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $conn->real_escape_string($_POST['title']);
    $date = $conn->real_escape_string($_POST['date']);
    $time = $conn->real_escape_string($_POST['time']);
    $location = $conn->real_escape_string($_POST['location']);
    $description = $conn->real_escape_string($_POST['description']);

    // Image Upload
    $image_path = $event ? $event['image'] : '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $target_dir = "../media/events/";
        if (!file_exists($target_dir)) { mkdir($target_dir, 0777, true); }

        $filename = time() . "_" . basename($_FILES['image']['name']);
        $target_file = $target_dir . $filename;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
            $image_path = "media/events/" . $filename;
        }
    }

    if ($id) {
        $sql = "UPDATE events SET title='$title', date='$date', time='$time', location='$location', description='$description', image='$image_path' WHERE id=$id";
    } else {
        $sql = "INSERT INTO events (title, date, time, location, description, image) VALUES ('$title', '$date', '$time', '$location', '$description', '$image_path')";
    }

    if ($conn->query($sql)) {
        $_SESSION['msg_success'] = "Event saved successfully.";
        echo "<script>window.location.href='events_list.php';</script>";
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
                <h5 class="mb-0"><?php echo $id ? 'Edit' : 'Add'; ?> Event</h5>
            </div>
            <div class="card-body">
                <?php if(isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
                <form method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label class="form-label">Event Title</label>
                        <input type="text" name="title" class="form-control" required value="<?php echo $event ? htmlspecialchars($event['title']) : ''; ?>">
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Date</label>
                            <input type="date" name="date" class="form-control" required value="<?php echo $event ? htmlspecialchars($event['date']) : ''; ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Time</label>
                            <input type="text" name="time" class="form-control" placeholder="e.g. 10:00 AM" value="<?php echo $event ? htmlspecialchars($event['time']) : ''; ?>">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Location</label>
                        <input type="text" name="location" class="form-control" value="<?php echo $event ? htmlspecialchars($event['location']) : ''; ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description (Rich Text)</label>
                        <textarea name="description" class="form-control summernote"><?php echo $event ? $event['description'] : ''; ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Event Image</label>
                        <?php if($event && $event['image']): ?>
                            <div class="mb-2"><img src="../<?php echo $event['image']; ?>" height="100"></div>
                        <?php endif; ?>
                        <input type="file" name="image" class="form-control">
                    </div>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                    <a href="events_list.php" class="btn btn-secondary">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
