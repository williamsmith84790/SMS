<?php
$page_title = "Student Result";
require_once 'includes/header.php';

if (!has_permission('results')) {
    echo '<div class="alert alert-danger">You do not have permission to access this page.</div>';
    require_once 'includes/footer.php';
    exit;
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : null;
$student_result = null;

if ($id) {
    $result = $conn->query("SELECT * FROM student_results WHERE id = $id");
    if ($result->num_rows > 0) {
        $student_result = $result->fetch_assoc();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $roll_number = $conn->real_escape_string($_POST['roll_number']);
    $session = $conn->real_escape_string($_POST['session']);
    $student_name = $conn->real_escape_string($_POST['student_name']);
    $father_name = $conn->real_escape_string($_POST['father_name']);
    $total_marks = $conn->real_escape_string($_POST['total_marks']);
    $obtained_marks = $conn->real_escape_string($_POST['obtained_marks']);
    $grade = $conn->real_escape_string($_POST['grade']);
    $result_data = $conn->real_escape_string($_POST['result_data']);

    // File Upload
    $file_path = $student_result ? $student_result['result_file'] : '';
    if (isset($_FILES['result_file']) && $_FILES['result_file']['error'] === 0) {
        $target_dir = "../media/results/";
        if (!file_exists($target_dir)) { mkdir($target_dir, 0777, true); }

        $filename = time() . "_" . basename($_FILES['result_file']['name']);
        $target_file = $target_dir . $filename;

        if (move_uploaded_file($_FILES['result_file']['tmp_name'], $target_file)) {
            $file_path = "media/results/" . $filename;
        }
    }

    if ($id) {
        $sql = "UPDATE student_results SET roll_number='$roll_number', session='$session', student_name='$student_name', father_name='$father_name', total_marks='$total_marks', obtained_marks='$obtained_marks', grade='$grade', result_data='$result_data', result_file='$file_path' WHERE id=$id";
    } else {
        $sql = "INSERT INTO student_results (roll_number, session, student_name, father_name, total_marks, obtained_marks, grade, result_data, result_file) VALUES ('$roll_number', '$session', '$student_name', '$father_name', '$total_marks', '$obtained_marks', '$grade', '$result_data', '$file_path')";
    }

    if ($conn->query($sql)) {
        $_SESSION['msg_success'] = "Result saved successfully.";
        echo "<script>window.location.href='results_list.php';</script>";
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
                <h5 class="mb-0"><?php echo $id ? 'Edit' : 'Add'; ?> Result</h5>
            </div>
            <div class="card-body">
                <?php if(isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
                <form method="POST" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Roll Number</label>
                            <input type="text" name="roll_number" class="form-control" required value="<?php echo $student_result ? htmlspecialchars($student_result['roll_number']) : ''; ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Session</label>
                            <select name="session" class="form-select" required>
                                <option value="2022-2023" <?php echo ($student_result && $student_result['session'] == '2022-2023') ? 'selected' : ''; ?>>2022-2023</option>
                                <option value="2023-2024" <?php echo ($student_result && $student_result['session'] == '2023-2024') ? 'selected' : ''; ?>>2023-2024</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Student Name</label>
                        <input type="text" name="student_name" class="form-control" required value="<?php echo $student_result ? htmlspecialchars($student_result['student_name']) : ''; ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Father's Name</label>
                        <input type="text" name="father_name" class="form-control" value="<?php echo $student_result ? htmlspecialchars($student_result['father_name']) : ''; ?>">
                    </div>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Total Marks</label>
                            <input type="text" name="total_marks" class="form-control" value="<?php echo $student_result ? htmlspecialchars($student_result['total_marks']) : ''; ?>">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Obtained Marks</label>
                            <input type="text" name="obtained_marks" class="form-control" value="<?php echo $student_result ? htmlspecialchars($student_result['obtained_marks']) : ''; ?>">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Grade</label>
                            <input type="text" name="grade" class="form-control" value="<?php echo $student_result ? htmlspecialchars($student_result['grade']) : ''; ?>">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Detailed Result (JSON or Text)</label>
                        <textarea name="result_data" class="form-control" rows="4"><?php echo $student_result ? htmlspecialchars($student_result['result_data']) : ''; ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Result Card PDF</label>
                        <?php if($student_result && $student_result['result_file']): ?>
                            <div class="mb-2"><a href="../<?php echo $student_result['result_file']; ?>" target="_blank">Current File</a></div>
                        <?php endif; ?>
                        <input type="file" name="result_file" class="form-control" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,.csv">
                        <small class="text-muted">Allowed types: PDF, DOC, CSV, Image</small>
                    </div>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                    <a href="results_list.php" class="btn btn-secondary">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
