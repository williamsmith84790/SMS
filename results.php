<?php
$page_title = "Exam Results";
require_once 'includes/header.php';

$result = null;
$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['roll_number'])) {
    $roll = $conn->real_escape_string($_GET['roll_number']);
    $session = isset($_GET['session']) ? $conn->real_escape_string($_GET['session']) : '';
    $class = isset($_GET['class']) ? $conn->real_escape_string($_GET['class']) : '';
    $part = isset($_GET['part']) ? $conn->real_escape_string($_GET['part']) : '';

    $sql = "SELECT * FROM student_results WHERE roll_number = '$roll'";
    if ($session) $sql .= " AND session = '$session'";
    if ($class) $sql .= " AND class = '$class'";
    if ($part) $sql .= " AND part = '$part'";

    $query_result = $conn->query($sql);

    if ($query_result && $query_result->num_rows > 0) {
        $result = $query_result->fetch_assoc();
    } else {
        $error = "Result not found for the given criteria.";
    }
}
?>

<style>
    @media print {
        body * { visibility: hidden; }
        #result-card, #result-card * { visibility: visible; }
        #result-card { position: absolute; left: 0; top: 0; width: 100%; }
        .no-print { display: none !important; }
    }
    .result-table th, .result-table td { border: 1px solid #000 !important; vertical-align: middle; text-align: center; }
    .result-header { text-align: center; margin-bottom: 20px; }
    .result-header img { max-height: 80px; }
    .student-info td { border: none !important; text-align: left; padding: 5px 10px; }
    @media print {
        #result-card {
            /* Removed fixed height and overflow hidden to prevent data loss */
            width: 100% !important;
            box-shadow: none !important;
            padding: 0 !important;
            margin: 0 !important;
        }
        body * { visibility: hidden; }
        #result-card, #result-card * { visibility: visible; }
        #result-card { position: absolute; left: 0; top: 0; }
        .container { max-width: 100% !important; }
        @page { size: A4; margin: 10mm; }
    }
</style>

<div class="row justify-content-center no-print mt-5">
    <div class="col-md-10">
        <div class="card shadow mb-4">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0"><i class="fas fa-search"></i> Check Result</h4>
            </div>
            <div class="card-body">
                <form method="GET" action="results.php" class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Class</label>
                        <select name="class" class="form-select">
                            <option value="">Select Class</option>
                            <option value="Intermediate">Intermediate</option>
                            <option value="Matric">Matric</option>
                            <option value="BS">BS</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Part</label>
                        <select name="part" class="form-select">
                            <option value="">Select Part</option>
                            <option value="First">Part First</option>
                            <option value="Second">Part Second</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Session</label>
                        <select name="session" class="form-select">
                            <option value="">Select Session</option>
                            <?php for($y = date('Y'); $y >= 2020; $y--): ?>
                                <option value="<?php echo ($y-1).'-'.$y; ?>"><?php echo ($y-1).'-'.$y; ?></option>
                            <?php endfor; ?>
                            <option value="2023">2023</option>
                            <option value="2024">2024</option>
                            <option value="2025">2025</option>
                            <option value="2026">2026</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Roll Number</label>
                        <input type="text" class="form-control" name="roll_number" placeholder="Roll No" required value="<?php echo isset($_GET['roll_number']) ? htmlspecialchars($_GET['roll_number']) : ''; ?>">
                    </div>
                    <div class="col-12 text-center mt-3">
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
    </div>
</div>

<?php if ($result): ?>
<div class="container bg-white p-5 shadow" id="result-card">
    <!-- Header -->
    <div class="result-header">
        <h4><?php echo htmlspecialchars($settings['result_card_board_name'] ?? 'BOARD OF INTERMEDIATE & SECONDARY EDUCATION'); ?></h4>
        <h5><?php echo htmlspecialchars($settings['result_card_title'] ?? 'RESULT INFORMATION'); ?></h5>
        <h6 class="text-uppercase fw-bold"><?php echo htmlspecialchars($settings['result_card_exam_title'] ?? ($result['class'] ?? 'HIGHER SECONDARY SCHOOL') . ' CERTIFICATE (' . ($result['part'] ?? 'ANNUAL') . '), EXAMINATION'); ?></h6>
        <p class="small text-muted">(Errors & Omissions Excepted)</p>
    </div>

    <!-- Student Info -->
    <table class="table table-borderless student-info mb-4">
        <tr>
            <!-- Left Column -->
            <td width="50%" style="vertical-align: top; padding: 0;">
                <table class="table table-borderless m-0">
                    <tr>
                        <td width="40%" class="fw-bold py-1">Roll No.</td>
                        <td width="60%" class="py-1"><?php echo htmlspecialchars($result['roll_number']); ?></td>
                    </tr>
                    <tr>
                        <td class="fw-bold py-1">Name:</td>
                        <td class="py-1"><?php echo htmlspecialchars($result['student_name']); ?></td>
                    </tr>
                    <tr>
                        <td class="fw-bold py-1">Father's Name:</td>
                        <td class="py-1"><?php echo htmlspecialchars($result['father_name']); ?></td>
                    </tr>
                </table>
            </td>
            <!-- Right Column -->
            <td width="50%" style="vertical-align: top; padding: 0;">
                <table class="table table-borderless m-0">
                    <tr>
                        <td width="40%" class="fw-bold py-1">Institute:</td>
                        <td width="60%" class="py-1"><?php echo htmlspecialchars($result['institution'] ?? '-'); ?></td>
                    </tr>
                    <tr>
                        <td class="fw-bold py-1">Registration No.</td>
                        <td class="py-1"><?php echo htmlspecialchars($result['registration_number'] ?? '-'); ?></td>
                    </tr>
                    <tr>
                        <td class="fw-bold py-1">Session:</td>
                        <td class="py-1"><?php echo htmlspecialchars($result['session']); ?></td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <!-- Marks Detail -->
    <div class="text-end mb-1 fw-bold">DETAIL OF MARKS</div>
    <div class="table-responsive">
    <table class="table result-table">
        <thead>
            <tr class="bg-light">
                <th rowspan="2" class="text-start">SUBJECT</th>
                <th colspan="3"><?php echo strtoupper($result['part'] ?? 'PART'); ?></th>
                <!-- If we had Part 2 data separately we would duplicate these columns -->
            </tr>
            <tr class="bg-light">
                <th>MAX</th>
                <th>MIN</th>
                <th>OBTAINED</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $subjects = json_decode($result['result_data'], true);
            if (is_array($subjects)):
                foreach($subjects as $sub):
            ?>
            <tr>
                <td class="text-start"><?php echo htmlspecialchars($sub['subject']); ?></td>
                <td><?php echo htmlspecialchars($sub['total'] ?? '100'); ?></td>
                <td><?php echo floor(($sub['total'] ?? 100) * 0.33); ?></td> <!-- Approx Min -->
                <td><?php echo htmlspecialchars($sub['obtained']); ?></td>
            </tr>
            <?php
                endforeach;
            endif;
            ?>
            <tr class="fw-bold">
                <td class="text-start">Grand Total</td>
                <td><?php echo htmlspecialchars($result['total_marks'] ?? '-'); ?></td>
                <td></td>
                <td><?php echo htmlspecialchars($result['obtained_marks']); ?></td>
            </tr>
        </tbody>
    </table>
    </div>

    <div class="mt-4">
        <p><strong>Result Status:</strong> <span class="fw-bold text-uppercase"><?php echo htmlspecialchars($result['result_status'] ?? $result['grade']); ?></span></p>
        <p>The candidate has passed the said examination in full, securing marks: <strong><?php echo htmlspecialchars($result['obtained_marks']); ?></strong></p>
    </div>

    <div class="row mt-5 pt-5">
        <div class="col-6">
            <p><strong>PRINTED BY:</strong> ADMIN</p>
            <p>Result Declared on: <?php echo date('d-m-Y'); ?></p>
        </div>
        <div class="col-6 text-end">
            <p><strong>CONTROLLER OF EXAMINATIONS</strong></p>
        </div>
    </div>
</div>

<div class="text-center mb-5 mt-4 no-print">
    <button onclick="window.print()" class="btn btn-success btn-lg"><i class="fas fa-print"></i> Print / Download PDF</button>
    <br><br>
    <?php if ($result['result_file']): ?>
        <a href="<?php echo htmlspecialchars($result['result_file']); ?>" class="btn btn-outline-primary" target="_blank"><i class="fas fa-file-download"></i> Download Original File</a>
    <?php endif; ?>
</div>
<?php endif; ?>

<?php require_once 'includes/footer.php'; ?>
