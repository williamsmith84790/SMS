<?php
$page_title = "Menu Item";
require_once 'includes/header.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : null;
$location = isset($_GET['location']) ? $_GET['location'] : 'header';
$item = null;

if ($id) {
    $result = $conn->query("SELECT * FROM menu_items WHERE id = $id");
    if ($result->num_rows > 0) {
        $item = $result->fetch_assoc();
        $location = $item['location']; // Override location if editing
    }
}

// Fetch potential parents (only for header usually, footer is flat in this design but code supports hierarchy)
// Exclude self from parents list to avoid loops
$parents_sql = "SELECT * FROM menu_items WHERE location = '$location' AND parent_id IS NULL";
if ($id) {
    $parents_sql .= " AND id != $id";
}
$parents_sql .= " ORDER BY sort_order ASC";
$parents_result = $conn->query($parents_sql);


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $label = $conn->real_escape_string($_POST['label']);
    $link = $conn->real_escape_string($_POST['link']);
    $parent_id = !empty($_POST['parent_id']) ? (int)$_POST['parent_id'] : "NULL";
    $sort_order = (int)$_POST['sort_order'];
    $location_post = $conn->real_escape_string($_POST['location']);

    if ($id) {
        $sql = "UPDATE menu_items SET label='$label', link='$link', parent_id=$parent_id, sort_order=$sort_order, location='$location_post' WHERE id=$id";
    } else {
        $sql = "INSERT INTO menu_items (label, link, parent_id, sort_order, location) VALUES ('$label', '$link', $parent_id, $sort_order, '$location_post')";
    }

    if ($conn->query($sql)) {
        $_SESSION['msg_success'] = "Menu item saved.";
        echo "<script>window.location.href='menus.php?location=$location_post';</script>";
        exit;
    } else {
        $error = "Error: " . $conn->error;
    }
}
?>

<div class="row">
    <div class="col-md-6 offset-md-3">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><?php echo $id ? 'Edit' : 'Add'; ?> Menu Item</h5>
            </div>
            <div class="card-body">
                <?php if(isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
                <form method="POST">
                    <input type="hidden" name="location" value="<?php echo htmlspecialchars($location); ?>">
                    <div class="mb-3">
                        <label class="form-label">Label</label>
                        <input type="text" name="label" class="form-control" required value="<?php echo $item ? htmlspecialchars($item['label']) : ''; ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Link</label>
                        <input type="text" name="link" class="form-control" required value="<?php echo $item ? htmlspecialchars($item['link']) : '#'; ?>">
                        <small class="text-muted">Use '#' for parent items with no direct link.</small>
                    </div>

                    <?php if ($location == 'header'): ?>
                    <div class="mb-3">
                        <label class="form-label">Parent Item</label>
                        <select name="parent_id" class="form-select">
                            <option value="">-- No Parent (Top Level) --</option>
                            <?php while($p = $parents_result->fetch_assoc()): ?>
                                <option value="<?php echo $p['id']; ?>" <?php echo ($item && $item['parent_id'] == $p['id']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($p['label']); ?>
                                </option>
                                <!-- Basic support for 2nd level parents (grandparents) if needed, though recursive function is better for deep trees -->
                                <?php
                                // Simple check for sub-items of this parent to show as options?
                                // Keeping it 2 levels deep for selection simplicity as per requirements (Header > Dropdown > Sub-Dropdown)
                                // Fetch children of this parent to allow them as parents too (level 2)
                                $sub_parents_sql = "SELECT * FROM menu_items WHERE parent_id = " . $p['id'] . " AND id != " . ($id ?: 0);
                                $sub_parents = $conn->query($sub_parents_sql);
                                while($sp = $sub_parents->fetch_assoc()):
                                ?>
                                    <option value="<?php echo $sp['id']; ?>" <?php echo ($item && $item['parent_id'] == $sp['id']) ? 'selected' : ''; ?>>
                                        &nbsp;&nbsp;-- <?php echo htmlspecialchars($sp['label']); ?>
                                    </option>
                                <?php endwhile; ?>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <?php endif; ?>

                    <div class="mb-3">
                        <label class="form-label">Sort Order</label>
                        <input type="number" name="sort_order" class="form-control" value="<?php echo $item ? $item['sort_order'] : '0'; ?>">
                    </div>

                    <button type="submit" class="btn btn-primary">Save Changes</button>
                    <a href="menus.php?location=<?php echo $location; ?>" class="btn btn-secondary">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
