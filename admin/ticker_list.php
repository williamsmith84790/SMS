<?php
$page_title = "Manage News Ticker";
require_once 'includes/header.php';

if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $conn->query("DELETE FROM ticker_items WHERE id = $id");
    header("Location: ticker_list.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $content = $conn->real_escape_string($_POST['content']);
    $is_active = isset($_POST['is_active']) ? 1 : 0;

    if (isset($_POST['id']) && !empty($_POST['id'])) {
        $id = (int)$_POST['id'];
        $sql = "UPDATE ticker_items SET content='$content', is_active=$is_active WHERE id=$id";
    } else {
        $sql = "INSERT INTO ticker_items (content, is_active) VALUES ('$content', $is_active)";
    }

    $conn->query($sql);
    header("Location: ticker_list.php");
    exit;
}

$edit_item = null;
if (isset($_GET['edit'])) {
    $id = (int)$_GET['edit'];
    $result = $conn->query("SELECT * FROM ticker_items WHERE id = $id");
    $edit_item = $result->fetch_assoc();
}

$result = $conn->query("SELECT * FROM ticker_items ORDER BY created_at DESC");
?>

<div class="row">
    <div class="col-md-4">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><?php echo $edit_item ? 'Edit' : 'Add'; ?> Ticker Item</h5>
            </div>
            <div class="card-body">
                <form method="POST">
                    <?php if($edit_item): ?>
                        <input type="hidden" name="id" value="<?php echo $edit_item['id']; ?>">
                    <?php endif; ?>
                    <div class="mb-3">
                        <label class="form-label">Content</label>
                        <textarea name="content" class="form-control" rows="3" required><?php echo $edit_item ? htmlspecialchars($edit_item['content']) : ''; ?></textarea>
                    </div>
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" name="is_active" id="is_active" <?php echo ($edit_item && $edit_item['is_active']) ? 'checked' : 'checked'; ?>>
                        <label class="form-check-label" for="is_active">Active?</label>
                    </div>
                    <button type="submit" class="btn btn-primary w-100"><?php echo $edit_item ? 'Update' : 'Add'; ?></button>
                    <?php if($edit_item): ?>
                        <a href="ticker_list.php" class="btn btn-secondary w-100 mt-2">Cancel</a>
                    <?php endif; ?>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Ticker Items</h5>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Content</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['content']); ?></td>
                            <td><?php echo date('d M Y', strtotime($row['created_at'])); ?></td>
                            <td>
                                <span class="badge <?php echo $row['is_active'] ? 'bg-success' : 'bg-secondary'; ?>">
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
