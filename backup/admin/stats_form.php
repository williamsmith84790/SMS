<?php
$page_title = "Stat Badge";
require_once 'includes/header.php';

if (!has_permission('settings')) {
    echo '<div class="alert alert-danger">You do not have permission to access this page.</div>';
    require_once 'includes/footer.php';
    exit;
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : null;
$stat = null;

if ($id) {
    $result = $conn->query("SELECT * FROM site_stats WHERE id = $id");
    if ($result->num_rows > 0) $stat = $result->fetch_assoc();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $icon = $conn->real_escape_string($_POST['icon']);
    $number = $conn->real_escape_string($_POST['number']);
    $label = $conn->real_escape_string($_POST['label']);
    $sort_order = (int)$_POST['sort_order'];

    if ($id) {
        $sql = "UPDATE site_stats SET icon='$icon', number='$number', label='$label', sort_order=$sort_order WHERE id=$id";
    } else {
        $sql = "INSERT INTO site_stats (icon, number, label, sort_order) VALUES ('$icon', '$number', '$label', $sort_order)";
    }

    if ($conn->query($sql)) {
        header("Location: stats_list.php");
        exit;
    } else {
        $error = "Error: " . $conn->error;
    }
}
?>

<div class="card">
    <div class="card-header"><?php echo $id ? 'Edit' : 'Add'; ?> Stat Badge</div>
    <div class="card-body">
        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Icon Class (FontAwesome)</label>
                <input type="text" name="icon" class="form-control" placeholder="e.g. fas fa-user" value="<?php echo $stat ? htmlspecialchars($stat['icon']) : ''; ?>" required>
                <small class="text-muted"><a href="https://fontawesome.com/v5/search" target="_blank">Find Icons</a></small>
            </div>
            <div class="mb-3">
                <label class="form-label">Number/Value</label>
                <input type="text" name="number" class="form-control" placeholder="e.g. 1500" value="<?php echo $stat ? htmlspecialchars($stat['number']) : ''; ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Label</label>
                <input type="text" name="label" class="form-control" placeholder="e.g. Students" value="<?php echo $stat ? htmlspecialchars($stat['label']) : ''; ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Sort Order</label>
                <input type="number" name="sort_order" class="form-control" value="<?php echo $stat ? $stat['sort_order'] : 0; ?>">
            </div>
            <button type="submit" class="btn btn-primary">Save</button>
            <a href="stats_list.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
