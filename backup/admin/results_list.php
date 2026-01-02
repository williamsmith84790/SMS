<?php
$page_title = "Manage Results";
require_once 'includes/header.php';

if (!has_permission('results')) {
    echo '<div class="alert alert-danger">You do not have permission to access this page.</div>';
    require_once 'includes/footer.php';
    exit;
}

// Handle Delete
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $conn->query("DELETE FROM student_results WHERE id = $id");
    $_SESSION['msg_success'] = "Result deleted successfully.";
    echo "<script>window.location.href='results_list.php';</script>";
    exit;
}

$search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';
$where = "WHERE 1=1";
if ($search) {
    $where .= " AND (student_name LIKE '%$search%' OR roll_number LIKE '%$search%')";
}

$result = $conn->query("SELECT * FROM student_results $where ORDER BY id DESC LIMIT 50");
?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h4>Student Results</h4>
    <div>
        <a href="results_import.php" class="btn btn-success me-2"><i class="fas fa-file-csv"></i> Import CSV</a>
        <a href="results_form.php" class="btn btn-primary"><i class="fas fa-plus"></i> Add New</a>
    </div>
</div>

<div class="card mb-4 bg-light">
    <div class="card-body">
        <form method="GET" class="row g-2">
            <div class="col-md-10">
                <input type="text" name="search" class="form-control" placeholder="Search by Name or Roll Number" value="<?php echo htmlspecialchars($search); ?>">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-secondary w-100">Search</button>
            </div>
        </form>
    </div>
</div>

<table class="table table-bordered table-hover bg-white shadow-sm">
    <thead class="table-light">
        <tr>
            <th>Roll No</th>
            <th>Session</th>
            <th>Name</th>
            <th>Total Marks</th>
            <th>Obtained</th>
            <th>Grade</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php while($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?php echo htmlspecialchars($row['roll_number']); ?></td>
            <td><?php echo htmlspecialchars($row['session']); ?></td>
            <td><?php echo htmlspecialchars($row['student_name']); ?></td>
            <td><?php echo htmlspecialchars($row['total_marks']); ?></td>
            <td><?php echo htmlspecialchars($row['obtained_marks']); ?></td>
            <td><?php echo htmlspecialchars($row['grade']); ?></td>
            <td>
                <a href="results_form.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-info text-white"><i class="fas fa-edit"></i></a>
                <a href="?delete=<?php echo $row['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')"><i class="fas fa-trash"></i></a>
            </td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>

<?php require_once 'includes/footer.php'; ?>
