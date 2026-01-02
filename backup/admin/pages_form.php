<?php
$page_title = "Page Editor";
require_once 'includes/header.php';

if (!has_permission('pages')) {
    echo '<div class="alert alert-danger">You do not have permission to access this page.</div>';
    require_once 'includes/footer.php';
    exit;
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : null;
$page = null;

if ($id) {
    $result = $conn->query("SELECT * FROM pages WHERE id = $id");
    if ($result->num_rows > 0) {
        $page = $result->fetch_assoc();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $conn->real_escape_string($_POST['title']);
    $slug = $conn->real_escape_string($_POST['slug']);
    $content = $conn->real_escape_string($_POST['content']);

    // Check unique slug
    $check = $conn->query("SELECT id FROM pages WHERE slug = '$slug' AND id != " . ($id ?: 0));
    if ($check->num_rows > 0) {
        $error = "Slug already exists. Please choose another.";
    } else {
        if ($id) {
            $sql = "UPDATE pages SET title='$title', slug='$slug', content='$content' WHERE id=$id";
        } else {
            $sql = "INSERT INTO pages (title, slug, content) VALUES ('$title', '$slug', '$content')";
        }

        if ($conn->query($sql)) {
            $_SESSION['msg_success'] = "Page saved successfully.";
            echo "<script>window.location.href='pages_list.php';</script>";
            exit;
        } else {
            $error = "Error: " . $conn->error;
        }
    }
}
?>

<div class="row">
    <div class="col-md-10 offset-md-1">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><?php echo $id ? 'Edit' : 'Add'; ?> Page</h5>
            </div>
            <div class="card-body">
                <?php if(isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
                <form method="POST">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Page Title</label>
                            <input type="text" name="title" class="form-control" required value="<?php echo $page ? htmlspecialchars($page['title']) : ''; ?>" onkeyup="generateSlug(this.value)">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Slug (URL Identifier)</label>
                            <input type="text" name="slug" id="slug" class="form-control" required value="<?php echo $page ? htmlspecialchars($page['slug']) : ''; ?>">
                            <small class="text-muted">No spaces or special characters.</small>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Content</label>
                        <textarea name="content" class="form-control summernote"><?php echo $page ? $page['content'] : ''; ?></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                    <a href="pages_list.php" class="btn btn-secondary">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function generateSlug(text) {
    if (!document.getElementById('slug').value || document.getElementById('slug').value.trim() === '') {
        var slug = text.toLowerCase()
            .replace(/[^\w ]+/g, '')
            .replace(/ +/g, '-');
        document.getElementById('slug').value = slug;
    }
}
</script>

<?php require_once 'includes/footer.php'; ?>
