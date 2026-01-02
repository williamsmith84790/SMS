<?php
$page_title = "Manage Faculty";
require_once 'includes/header.php';

// Handle Delete
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $conn->query("DELETE FROM faculty_members WHERE id = $id");
    $_SESSION['msg_success'] = "Faculty member deleted successfully.";
    echo "<script>window.location.href='faculty_list.php';</script>";
    exit;
}

$result = $conn->query("SELECT * FROM faculty_members ORDER BY `order` ASC, name ASC");
?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h4>Faculty Members</h4>
    <a href="faculty_form.php" class="btn btn-primary"><i class="fas fa-plus"></i> Add New</a>
</div>

<table class="table table-bordered table-hover bg-white shadow-sm">
    <thead class="table-light">
        <tr>
            <th>Order</th>
            <th>Image</th>
            <th>Name</th>
            <th>Designation</th>
            <th>Department</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php while($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?php echo $row['order']; ?></td>
            <td>
                <?php if($row['image']): ?>
                    <img src="../<?php echo $row['image']; ?>" height="50">
                <?php endif; ?>
            </td>
            <td><?php echo htmlspecialchars($row['name']); ?></td>
            <td><?php echo htmlspecialchars($row['designation']); ?></td>
            <td><?php echo htmlspecialchars($row['department']); ?></td>
            <td>
                <a href="faculty_form.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-info text-white"><i class="fas fa-edit"></i></a>
                <a href="?delete=<?php echo $row['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')"><i class="fas fa-trash"></i></a>
            </td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>

<?php require_once 'includes/footer.php'; ?>
