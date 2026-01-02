<?php
if (!isset($_GET['slug'])) {
    header("Location: index.php");
    exit;
}

require_once 'config.php';
$slug = $conn->real_escape_string($_GET['slug']);

$page_sql = "SELECT * FROM pages WHERE slug = '$slug'";
$page_result = $conn->query($page_sql);

if (!$page_result || $page_result->num_rows == 0) {
    http_response_code(404);
    $page_title = "Page Not Found";
    require_once 'includes/header.php';
    echo '<div class="alert alert-warning">Page not found.</div>';
    require_once 'includes/footer.php';
    exit;
}

$page = $page_result->fetch_assoc();
$page_title = $page['title'];

require_once 'includes/header.php';
?>

<h2 class="mb-4 border-bottom pb-2"><?php echo htmlspecialchars($page['title']); ?></h2>

<div class="content">
    <?php echo $page['content']; // Assumed safe HTML ?>
</div>

<?php require_once 'includes/footer.php'; ?>
