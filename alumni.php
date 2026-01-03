<?php
$page_title = "Alumni";
require_once 'includes/header.php';

$alumni_sql = "SELECT * FROM alumni ORDER BY batch DESC, name ASC";
$alumni_result = $conn->query($alumni_sql);
?>

<h2 class="mt-5 mb-4 border-bottom pb-2">Distinguished Alumni</h2>

<div class="row row-cols-1 row-cols-md-4 g-4">
    <?php
    if ($alumni_result && $alumni_result->num_rows > 0) {
        while($alum = $alumni_result->fetch_assoc()) {
            ?>
            <div class="col">
                <div class="card h-100 text-center shadow-sm">
                    <div class="card-body">
                        <?php if($alum['image']): ?>
                            <img src="<?php echo htmlspecialchars($alum['image']); ?>" class="rounded-circle mb-3" style="width: 120px; height: 120px; object-fit: cover;" alt="<?php echo htmlspecialchars($alum['name']); ?>">
                        <?php else: ?>
                            <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 120px; height: 120px; font-size: 2rem;">
                                <i class="fas fa-user"></i>
                            </div>
                        <?php endif; ?>

                        <h5 class="card-title"><?php echo htmlspecialchars($alum['name']); ?></h5>
                        <h6 class="card-subtitle mb-2 text-primary">Batch of <?php echo htmlspecialchars($alum['batch']); ?></h6>
                        <?php if($alum['achievement']): ?>
                            <p class="card-text small text-muted"><?php echo htmlspecialchars($alum['achievement']); ?></p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php
        }
    } else {
        echo '<div class="col-12"><p class="alert alert-info">No alumni records found.</p></div>';
    }
    ?>
</div>

<?php require_once 'includes/footer.php'; ?>
