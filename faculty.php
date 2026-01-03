<?php
$page_title = "Faculty";
require_once 'includes/header.php';

$faculty_sql = "SELECT * FROM faculty_members ORDER BY `order` ASC, name ASC";
$faculty_result = $conn->query($faculty_sql);
?>

<h2 class="mt-5 mb-4 border-bottom pb-2">Our Faculty</h2>

<div class="row row-cols-1 row-cols-md-3 g-4">
    <?php
    if ($faculty_result && $faculty_result->num_rows > 0) {
        while($faculty = $faculty_result->fetch_assoc()) {
            ?>
            <div class="col">
                <div class="card h-100 shadow-sm">
                    <?php if($faculty['image']): ?>
                        <img src="<?php echo htmlspecialchars($faculty['image']); ?>" class="card-img-top card-img-top-faculty" alt="<?php echo htmlspecialchars($faculty['name']); ?>">
                    <?php else: ?>
                        <img src="https://via.placeholder.com/300x300?text=No+Image" class="card-img-top card-img-top-faculty" alt="No Image">
                    <?php endif; ?>
                    <div class="card-body">
                        <h5 class="card-title"><?php echo htmlspecialchars($faculty['name']); ?></h5>
                        <h6 class="card-subtitle mb-2 text-muted"><?php echo htmlspecialchars($faculty['designation']); ?></h6>
                        <?php if($faculty['department']): ?>
                            <p class="card-text"><small class="text-secondary"><?php echo htmlspecialchars($faculty['department']); ?></small></p>
                        <?php endif; ?>

                        <?php if($faculty['bio']): ?>
                            <div class="card-text mt-2 small">
                                <?php echo $faculty['bio']; // Assumed safe HTML from rich text editor, or use strip_tags if strict ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php
        }
    } else {
        echo '<div class="col-12"><p class="alert alert-info">No faculty members found.</p></div>';
    }
    ?>
</div>

<?php require_once 'includes/footer.php'; ?>
