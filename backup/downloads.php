<?php
$page_title = "Download Center";
require_once 'includes/header.php';

// Fetch Categories
$cat_sql = "SELECT * FROM document_categories ORDER BY name ASC";
$cat_result = $conn->query($cat_sql);
?>

<h2 class="mb-4 border-bottom pb-2">Download Center</h2>

<div class="accordion" id="downloadsAccordion">
    <?php
    if ($cat_result && $cat_result->num_rows > 0) {
        $i = 0;
        while($cat = $cat_result->fetch_assoc()) {
            $cat_id = $cat['id'];
            $collapse_id = "collapse" . $cat_id;
            $header_id = "heading" . $cat_id;
            $show = $i === 0 ? "show" : ""; // Open first item

            // Fetch Documents for this category
            $doc_sql = "SELECT * FROM documents WHERE category_id = $cat_id ORDER BY uploaded_at DESC";
            $doc_result = $conn->query($doc_sql);
            ?>
            <div class="accordion-item">
                <h2 class="accordion-header" id="<?php echo $header_id; ?>">
                    <button class="accordion-button <?php echo $i !== 0 ? 'collapsed' : ''; ?>" type="button" data-bs-toggle="collapse" data-bs-target="#<?php echo $collapse_id; ?>" aria-expanded="<?php echo $i === 0 ? 'true' : 'false'; ?>" aria-controls="<?php echo $collapse_id; ?>">
                        <?php echo htmlspecialchars($cat['name']); ?>
                    </button>
                </h2>
                <div id="<?php echo $collapse_id; ?>" class="accordion-collapse collapse <?php echo $show; ?>" aria-labelledby="<?php echo $header_id; ?>" data-bs-parent="#downloadsAccordion">
                    <div class="accordion-body">
                        <?php if ($doc_result && $doc_result->num_rows > 0): ?>
                            <ul class="list-group list-group-flush">
                                <?php while($doc = $doc_result->fetch_assoc()): ?>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <div>
                                            <i class="fas fa-file-alt text-primary me-2"></i>
                                            <?php echo htmlspecialchars($doc['title']); ?>
                                            <small class="text-muted d-block ms-4"><?php echo date('M d, Y', strtotime($doc['uploaded_at'])); ?></small>
                                        </div>
                                        <a href="<?php echo htmlspecialchars($doc['file']); ?>" class="btn btn-sm btn-outline-secondary" download><i class="fas fa-download"></i> Download</a>
                                    </li>
                                <?php endwhile; ?>
                            </ul>
                        <?php else: ?>
                            <p class="text-muted mb-0">No documents in this category.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php
            $i++;
        }
    } else {
        echo '<p class="alert alert-info">No downloads available at the moment.</p>';
    }
    ?>
</div>

<?php require_once 'includes/footer.php'; ?>
