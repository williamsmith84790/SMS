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

    // Get context from dropdowns
    $class_context = $conn->real_escape_string($_POST['class']);
    $part_context = $conn->real_escape_string($_POST['part']);
    $session_context = $conn->real_escape_string($_POST['session']);

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
                $headers_raw = fgetcsv($handle, 2000, ",");
                if (!$headers_raw) {
                    $errors[] = "File appears empty or invalid.";
                } else {
                    // Normalize headers: remove spaces, lowercase, handle BOM
                    $headers = array_map(function($h) {
                        return strtolower(trim(preg_replace('/[\x00-\x1F\x7F\xEF\xBB\xBF]/', '', $h)));
                    }, $headers_raw);

                    // Standard columns we expect (mapped to database columns)
                    $standard_cols = [
                        'roll no' => 'roll_number',
                        'roll_number' => 'roll_number',
                        'student name' => 'student_name',
                        'student_name' => 'student_name',
                        'father name' => 'father_name',
                        'father_name' => 'father_name',
                        'total marks' => 'total_marks',
                        'total_marks' => 'total_marks',
                        'obtained marks' => 'obtained_marks',
                        'obtained_marks' => 'obtained_marks',
                        'result' => 'result_status',
                        'result_status' => 'result_status',
                        'status' => 'result_status',
                        'institution' => 'institution',
                        'registration no' => 'registration_number',
                        'registration_number' => 'registration_number'
                    ];

                    // Identify column indices
                    $col_map = [];
                    $subject_indices = [];

                    foreach ($headers as $index => $header) {
                        if (empty($header)) continue;

                        if (array_key_exists($header, $standard_cols)) {
                            $col_map[$standard_cols[$header]] = $index;
                        } else {
                            // Assume it's a subject
                            // We treat the header as the subject name
                            // Filter out common non-subject words if necessary, but simpler is better
                            if (!in_array($header, ['class', 'part', 'session'])) {
                                $subject_indices[$headers_raw[$index]] = $index; // Use raw header for display name
                            }
                        }
                    }

                    // Check required
                    if (!isset($col_map['roll_number']) || !isset($col_map['student_name'])) {
                        $errors[] = "Missing required columns: Roll No, Student Name.";
                    } else {
                        while (($data = fgetcsv($handle, 2000, ",")) !== FALSE) {
                            // Skip empty rows
                            if (array_filter($data) == []) continue;

                            $roll = isset($col_map['roll_number']) ? $conn->real_escape_string(trim($data[$col_map['roll_number']])) : '';
                            $name = isset($col_map['student_name']) ? $conn->real_escape_string(trim($data[$col_map['student_name']])) : '';
                            $father = isset($col_map['father_name']) ? $conn->real_escape_string(trim($data[$col_map['father_name']])) : '';
                            $total = isset($col_map['total_marks']) ? $conn->real_escape_string(trim($data[$col_map['total_marks']])) : '';
                            $obtained = isset($col_map['obtained_marks']) ? $conn->real_escape_string(trim($data[$col_map['obtained_marks']])) : '';
                            $status = isset($col_map['result_status']) ? $conn->real_escape_string(trim($data[$col_map['result_status']])) : '';
                            $inst = isset($col_map['institution']) ? $conn->real_escape_string(trim($data[$col_map['institution']])) : '';
                            $reg = isset($col_map['registration_number']) ? $conn->real_escape_string(trim($data[$col_map['registration_number']])) : '';

                            if (empty($roll) || empty($name)) {
                                $error_count++;
                                continue;
                            }

                            // Build Subject Data JSON
                            $subjects = [];
                            foreach ($subject_indices as $sub_name => $idx) {
                                if (isset($data[$idx]) && trim($data[$idx]) !== '') {
                                    $subjects[] = [
                                        'subject' => $sub_name,
                                        'obtained' => trim($data[$idx]),
                                        'total' => 100 // Default, or we could add logic to find "Subject Total" cols
                                    ];
                                }
                            }
                            $result_data_json = $conn->real_escape_string(json_encode($subjects));

                            // Use context from dropdowns
                            $class = $class_context;
                            $part = $part_context;
                            $session = $session_context;

                            // Check duplicate (Roll + Session + Class + Part to be safe, but usually Roll+Session is unique enough per board)
                            // Prompt says unique constraint on roll+session in existing DB.
                            $check = $conn->query("SELECT id FROM student_results WHERE roll_number = '$roll' AND session = '$session'");

                            if ($check && $check->num_rows > 0) {
                                // Update
                                $sql = "UPDATE student_results SET
                                        student_name='$name',
                                        father_name='$father',
                                        total_marks='$total',
                                        obtained_marks='$obtained',
                                        result_status='$status',
                                        class='$class',
                                        part='$part',
                                        institution='$inst',
                                        registration_number='$reg',
                                        result_data='$result_data_json'
                                        WHERE roll_number='$roll' AND session='$session'";
                            } else {
                                // Insert
                                $sql = "INSERT INTO student_results
                                        (roll_number, session, student_name, father_name, total_marks, obtained_marks, result_status, class, part, institution, registration_number, result_data)
                                        VALUES
                                        ('$roll', '$session', '$name', '$father', '$total', '$obtained', '$status', '$class', '$part', '$inst', '$reg', '$result_data_json')";
                            }

                            if ($conn->query($sql)) {
                                $success_count++;
                            } else {
                                $error_count++;
                                $errors[] = "Error for Roll $roll: " . $conn->error;
                            }
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
    <div class="col-md-8 offset-md-2">
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

                <form method="POST" enctype="multipart/form-data">
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Select Class</label>
                            <select name="class" class="form-select" required>
                                <option value="Intermediate">Intermediate</option>
                                <option value="Matric">Matric</option>
                                <option value="BS">BS</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Select Part</label>
                            <select name="part" class="form-select" required>
                                <option value="First">Part First</option>
                                <option value="Second">Part Second</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Select Session</label>
                            <select name="session" class="form-select" required>
                                <?php for($y = date('Y'); $y >= 2020; $y--): ?>
                                    <option value="<?php echo ($y-1).'-'.$y; ?>"><?php echo ($y-1).'-'.$y; ?></option>
                                <?php endfor; ?>
                                <option value="2023">2023</option>
                                <option value="2024">2024</option>
                                <option value="2025">2025</option>
                                <option value="2026">2026</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3 border p-3 rounded bg-light">
                        <label class="form-label fw-bold">CSV File</label>
                        <p class="text-muted small">
                            <strong>Standard Columns:</strong> Roll No, Student Name, Father Name, Total Marks, Obtained Marks, Result, Institution, Registration No.<br>
                            <strong>Subjects:</strong> Any other column will be treated as a subject (Header = Subject Name, Value = Obtained Marks).
                        </p>
                        <input type="file" name="csv_file" class="form-control" accept=".csv" required>
                    </div>

                    <button type="submit" class="btn btn-success w-100">Import Results</button>
                    <a href="results_list.php" class="btn btn-secondary w-100 mt-2">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
