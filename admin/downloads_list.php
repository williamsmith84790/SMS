<?php
$page_title = "Manage Downloads";
require_once 'includes/header.php';

if (!has_permission('downloads')) {
    echo '<div class="alert alert-danger">You do not have permission to access this page.</div>';
    require_once 'includes/footer.php';
    exit;
}

// Handle Category Delete
if (isset($_GET['delete_cat'])) {
    $id = (int)$_GET['delete_cat'];
    $conn->query("DELETE FROM document_categories WHERE id = $id");
    $_SESSION['msg_success'] = "Category deleted successfully.";
    echo "<script>window.location.href='downloads_list.php';</script>";
    exit;
}

// Handle Document Delete
if (isset($_GET['delete_doc'])) {
    $id = (int)$_GET['delete_doc'];
    $conn->query("DELETE FROM documents WHERE id = $id");
    $_SESSION['msg_success'] = "Document deleted successfully.";
    echo "<script>window.location.href='downloads_list.php';</script>";
    exit;
}

// Handle Add Category
if (isset($_POST['add_category'])) {
    $name = $conn->real_escape_string($_POST['name']);
    $conn->query("INSERT INTO document_categories (name) VALUES ('$name')");
    $_SESSION['msg_success'] = "Category added.";
    echo "<script>window.location.href='downloads_list.php';</script>";
    exit;
}

// Handle Add Document
if (isset($_POST['add_document'])) {
    $title = $conn->real_escape_string($_POST['title']);
    $category_id = (int)$_POST['category_id'];

    // File Upload
    $target_dir = "../media/downloads/";
    if (!file_exists($target_dir)) { mkdir($target_dir, 0777, true); }

    $filename = time() . "_" . basename($_FILES['file']['name']);
    $target_file = $target_dir . $filename;

    if (move_uploaded_file($_FILES['file']['tmp_name'], $target_file)) {
        $file_path = "media/downloads/" . $filename;
        $conn->query("INSERT INTO documents (title, category_id, file) VALUES ('$title', $category_id, '$file_path')");
        $_SESSION['msg_success'] = "Document uploaded.";
    } else {
        $_SESSION['msg_error'] = "File upload failed.";
    }
    echo "<script>window.location.href='downloads_list.php';</script>";
    exit;
}

$categories = $conn->query("SELECT * FROM document_categories ORDER BY name ASC");
$documents = $conn->query("SELECT d.*, c.name as category_name FROM documents d LEFT JOIN document_categories c ON d.category_id = c.id ORDER BY d.uploaded_at DESC");
?>

<div class="row">
    <!-- Categories Section -->
    <div class="col-md-4">
        <div class="card mb-4">
            <div class="card-header bg-secondary text-white">Categories</div>
            <div class="card-body">
                <form method="POST" class="mb-3 input-group">
                    <input type="text" name="name" class="form-control" placeholder="New Category" required>
                    <button type="submit" name="add_category" class="btn btn-primary">Add</button>
                </form>
                <ul class="list-group">
                    <?php while($cat = $categories->fetch_assoc()): ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <?php echo htmlspecialchars($cat['name']); ?>
                            <a href="?delete_cat=<?php echo $cat['id']; ?>" class="text-danger" onclick="return confirm('Delete category and all its documents?')"><i class="fas fa-trash"></i></a>
                        </li>
                    <?php endwhile; ?>
                </ul>
            </div>
        </div>
    </div>

    <!-- Documents Section -->
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Documents</h5>
            </div>
            <div class="card-body">
                <button class="btn btn-success mb-3" type="button" data-bs-toggle="collapse" data-bs-target="#addDocForm">
                    <i class="fas fa-plus"></i> Upload New Document
                </button>

                <div class="collapse mb-3" id="addDocForm">
                    <div class="card card-body bg-light">
                        <form method="POST" enctype="multipart/form-data">
                            <div class="mb-2">
                                <label>Title</label>
                                <input type="text" name="title" class="form-control" required>
                            </div>
                            <div class="mb-2">
                                <label>Category</label>
                                <select name="category_id" class="form-select" required>
                                    <?php
                                    // Reset pointer
                                    $categories->data_seek(0);
                                    while($cat = $categories->fetch_assoc()):
                                    ?>
                                        <option value="<?php echo $cat['id']; ?>"><?php echo htmlspecialchars($cat['name']); ?></option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                            <div class="mb-2">
                                <label>File</label>
                                <input type="file" name="file" class="form-control" required>
                            </div>
                            <button type="submit" name="add_document" class="btn btn-primary">Upload</button>
                        </form>
                    </div>
                </div>

                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Category</th>
                            <th>Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($doc = $documents->fetch_assoc()): ?>
                        <tr>
                            <td>
                                <a href="../<?php echo $doc['file']; ?>" target="_blank"><?php echo htmlspecialchars($doc['title']); ?></a>
                            </td>
                            <td><?php echo htmlspecialchars($doc['category_name']); ?></td>
                            <td><?php echo date('d M Y', strtotime($doc['uploaded_at'])); ?></td>
                            <td>
                                <a href="?delete_doc=<?php echo $doc['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete?')"><i class="fas fa-trash"></i></a>
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
