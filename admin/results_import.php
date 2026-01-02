<?php
$page_title = "Import Results";
require_once 'includes/header.php';

if (!has_permission('results')) {
    echo '<div class="alert alert-danger">You do not have permission to access this page.</div>';
    require_once 'includes/footer.php';
    exit;
}

$success_count = 0;
$error_count = 0;
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['csv_file'])) {
    $file = $_FILES['csv_file'];

    // Check for errors
    if ($file['error'] !== UPLOAD_ERR_OK) {
        $errors[] = "File upload failed with error code: " . $file['error'];
    } else {
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if ($ext !== 'csv') {
            $errors[] = "Only CSV files are allowed.";
        } else {
            $handle = fopen($file['tmp_name'], "r");
            if ($handle !== FALSE) {
                // Get headers
                $headers = fgetcsv($handle, 1000, ",");
                // Normalize headers: remove spaces, lowercase
                $headers = array_map(function($h) { return strtolower(trim($h)); }, $headers);

                // Expected columns
                $required = ['roll_number', 'session', 'student_name', 'total_marks', 'obtained_marks', 'grade'];

                // Check if required columns exist
                $missing = array_diff($required, $headers);

                if (!empty($missing)) {
                    $errors[] = "Missing required columns: " . implode(', ', $missing);
                } else {
                    // Map indices
                    $map = array_flip($headers);

                    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                        // Skip empty rows
                        if (array_filter($data) == []) continue;

                        $roll = isset($map['roll_number']) ? $conn->real_escape_string(trim($data[$map['roll_number']])) : '';
                        $session = isset($map['session']) ? $conn->real_escape_string(trim($data[$map['session']])) : '';
                        $name = isset($map['student_name']) ? $conn->real_escape_string(trim($data[$map['student_name']])) : '';
                        $father = isset($map['father_name']) ? $conn->real_escape_string(trim($data[$map['father_name']])) : '';
                        $total = isset($map['total_marks']) ? $conn->real_escape_string(trim($data[$map['total_marks']])) : '';
                        $obtained = isset($map['obtained_marks']) ? $conn->real_escape_string(trim($data[$map['obtained_marks']])) : '';
                        $grade = isset($map['grade']) ? $conn->real_escape_string(trim($data[$map['grade']])) : '';

                        if (empty($roll) || empty($session) || empty($name)) {
                            $error_count++;
                            continue;
                        }

                        // Check duplicate
                        $check = $conn->query("SELECT id FROM student_results WHERE roll_number = '$roll' AND session = '$session'");
                        if ($check->num_rows > 0) {
                            // Update
                             $sql = "UPDATE student_results SET student_name='$name', father_name='$father', total_marks='$total', obtained_marks='$obtained', grade='$grade' WHERE roll_number='$roll' AND session='$session'";
                        } else {
                            // Insert
                            $sql = "INSERT INTO student_results (roll_number, session, student_name, father_name, total_marks, obtained_marks, grade) VALUES ('$roll', '$session', '$name', '$father', '$total', '$obtained', '$grade')";
                        }

                        if ($conn->query($sql)) {
                            $success_count++;
                        } else {
                            $error_count++;
                            $errors[] = "Error for Roll $roll: " . $conn->error;
                        }
                    }
                }
                fclose($handle);
            } else {
                $errors[] = "Could not open file.";
            }
        }
    }
}
?>

<div class="row">
    <div class="col-md-6 offset-md-3">
        <div class="card">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0">Import Results (CSV)</h5>
            </div>
            <div class="card-body">
                <?php if ($success_count > 0): ?>
                    <div class="alert alert-success">Successfully imported/updated <?php echo $success_count; ?> records.</div>
                <?php endif; ?>

                <?php if (!empty($errors)): ?>
                    <div class="alert alert-danger">
                        <ul>
                            <?php foreach ($errors as $e): ?>
                                <li><?php echo $e; ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <?php if ($error_count > 0 && empty($errors)): ?>
                    <div class="alert alert-warning">Skipped <?php echo $error_count; ?> rows (duplicates or empty required fields).</div>
                <?php endif; ?>

                <p>Upload a CSV file with the following headers (order doesn't matter):</p>
                <code>roll_number, session, student_name, father_name, total_marks, obtained_marks, grade</code>
                <br><br>
                <div class="alert alert-info small">
                    <strong>Note:</strong> Existing records with the same Roll Number and Session will be updated.
                </div>

                <form method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label class="form-label">CSV File</label>
                        <input type="file" name="csv_file" class="form-control" accept=".csv" required>
                    </div>
                    <button type="submit" class="btn btn-success w-100">Import CSV</button>
                    <a href="results_list.php" class="btn btn-secondary w-100 mt-2">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
