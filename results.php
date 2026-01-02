<?php
$page_title = "Exam Results";
require_once 'includes/header.php';

$result = null;
$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['roll_number']) && isset($_GET['session'])) {
    $roll = $conn->real_escape_string($_GET['roll_number']);
    $session = $conn->real_escape_string($_GET['session']);

    $sql = "SELECT * FROM student_results WHERE roll_number = '$roll' AND session = '$session'";
    $query_result = $conn->query($sql);

    if ($query_result && $query_result->num_rows > 0) {
        $result = $query_result->fetch_assoc();
    } else {
        $error = "Result not found for the given Roll Number and Session.";
    }
}
?>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0"><i class="fas fa-search"></i> Check Result</h4>
            </div>
            <div class="card-body">
                <form method="GET" action="results.php" class="row g-3">
                    <div class="col-md-6">
                        <label for="roll_number" class="form-label">Roll Number</label>
                        <input type="text" class="form-control" id="roll_number" name="roll_number" placeholder="e.g. 1001" required value="<?php echo isset($_GET['roll_number']) ? htmlspecialchars($_GET['roll_number']) : ''; ?>">
                    </div>
                    <div class="col-md-6">
                        <label for="session" class="form-label">Session</label>
                        <select class="form-select" id="session" name="session" required>
                            <option value="">Select Session</option>
                            <option value="2022-2023" <?php echo (isset($_GET['session']) && $_GET['session'] == '2022-2023') ? 'selected' : ''; ?>>2022-2023</option>
                            <option value="2023-2024" <?php echo (isset($_GET['session']) && $_GET['session'] == '2023-2024') ? 'selected' : ''; ?>>2023-2024</option>
                        </select>
                    </div>
                    <div class="col-12 text-center mt-4">
                        <button type="submit" class="btn btn-primary px-5">Search Result</button>
                    </div>
                </form>
            </div>
        </div>

        <?php if ($error): ?>
            <div class="alert alert-danger mt-4 text-center">
                <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <?php if ($result): ?>
            <div class="card mt-4 border-success shadow">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">Result Details</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-sm-4 fw-bold">Student Name:</div>
                        <div class="col-sm-8"><?php echo htmlspecialchars($result['student_name']); ?></div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-4 fw-bold">Father's Name:</div>
                        <div class="col-sm-8"><?php echo htmlspecialchars($result['father_name']); ?></div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-4 fw-bold">Roll Number:</div>
                        <div class="col-sm-8"><?php echo htmlspecialchars($result['roll_number']); ?></div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-4 fw-bold">Session:</div>
                        <div class="col-sm-8"><?php echo htmlspecialchars($result['session']); ?></div>
                    </div>
                    <hr>
                    <div class="row mb-3">
                        <div class="col-sm-4 fw-bold">Total Marks:</div>
                        <div class="col-sm-8"><?php echo htmlspecialchars($result['total_marks']); ?></div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-4 fw-bold">Obtained Marks:</div>
                        <div class="col-sm-8"><?php echo htmlspecialchars($result['obtained_marks']); ?></div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-4 fw-bold">Grade:</div>
                        <div class="col-sm-8">
                            <span class="badge bg-<?php echo ($result['grade'] == 'F') ? 'danger' : 'success'; ?> fs-6">
                                <?php echo htmlspecialchars($result['grade']); ?>
                            </span>
                        </div>
                    </div>

                    <?php if ($result['result_data']): ?>
                        <div class="mt-4">
                            <h6 class="fw-bold">Detailed Marks:</h6>
                            <pre class="bg-light p-3 border rounded"><?php echo htmlspecialchars($result['result_data']); ?></pre>
                        </div>
                    <?php endif; ?>

                    <?php if ($result['result_file']): ?>
                        <div class="mt-4 text-center">
                            <a href="<?php echo htmlspecialchars($result['result_file']); ?>" class="btn btn-outline-success" target="_blank"><i class="fas fa-file-pdf"></i> Download Result Card</a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
