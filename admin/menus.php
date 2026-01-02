<?php
$page_title = "Manage Menus";
require_once 'includes/header.php';

// Handle Delete
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $conn->query("DELETE FROM menu_items WHERE id = $id");
    // Also delete children (optional, but good practice to avoid orphans)
    $conn->query("DELETE FROM menu_items WHERE parent_id = $id");
    $_SESSION['msg_success'] = "Menu item deleted.";
    echo "<script>window.location.href='menus.php';</script>";
    exit;
}

$location = isset($_GET['location']) ? $_GET['location'] : 'header';

// Fetch Menu Items (Recursive function not needed for display, just flat list with indentation)
function get_menu_tree($conn, $parent_id = NULL, $location = 'header', $level = 0) {
    $output = [];
    $sql = "SELECT * FROM menu_items WHERE location = '$location' AND " . ($parent_id === NULL ? "parent_id IS NULL" : "parent_id = $parent_id") . " ORDER BY sort_order ASC";
    $result = $conn->query($sql);

    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $row['level'] = $level;
            $output[] = $row;
            $children = get_menu_tree($conn, $row['id'], $location, $level + 1);
            $output = array_merge($output, $children);
        }
    }
    return $output;
}

$menu_items = get_menu_tree($conn, NULL, $location);
?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h4 class="d-inline-block me-3">Menus</h4>
        <div class="btn-group">
            <a href="?location=header" class="btn btn-outline-primary <?php echo $location == 'header' ? 'active' : ''; ?>">Header Menu</a>
            <a href="?location=footer" class="btn btn-outline-primary <?php echo $location == 'footer' ? 'active' : ''; ?>">Footer Menu</a>
        </div>
    </div>
    <a href="menu_form.php?location=<?php echo $location; ?>" class="btn btn-primary"><i class="fas fa-plus"></i> Add Menu Item</a>
</div>

<div class="card">
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>Label</th>
                    <th>Link</th>
                    <th>Order</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($menu_items) > 0): ?>
                    <?php foreach ($menu_items as $item): ?>
                    <tr>
                        <td style="padding-left: <?php echo ($item['level'] * 30) + 15; ?>px;">
                            <?php if ($item['level'] > 0): ?><i class="fas fa-level-up-alt fa-rotate-90 text-muted me-2"></i><?php endif; ?>
                            <?php echo htmlspecialchars($item['label']); ?>
                        </td>
                        <td><?php echo htmlspecialchars($item['link']); ?></td>
                        <td><?php echo $item['sort_order']; ?></td>
                        <td>
                            <a href="menu_form.php?id=<?php echo $item['id']; ?>" class="btn btn-sm btn-info text-white"><i class="fas fa-edit"></i></a>
                            <a href="?delete=<?php echo $item['id']; ?>&location=<?php echo $location; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this item?')"><i class="fas fa-trash"></i></a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="4" class="text-center py-3">No menu items found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
