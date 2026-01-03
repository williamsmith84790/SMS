<?php
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: events.php");
    exit;
}

require_once 'config.php';
$id = (int)$_GET['id'];

$sql = "SELECT * FROM events WHERE id = $id";
$result = $conn->query($sql);

if (!$result || $result->num_rows == 0) {
    $page_title = "Event Not Found";
    require_once 'includes/header.php';
    echo '<div class="alert alert-warning text-center my-5">Event not found. <a href="events.php" class="alert-link">Return to Events</a></div>';
    require_once 'includes/footer.php';
    exit;
}

$event = $result->fetch_assoc();
$page_title = $event['title'];
require_once 'includes/header.php';
?>

<div class="row justify-content-center mt-5 mb-5">
    <div class="col-md-10 col-lg-8">
        <h2 class="fw-bold mb-3"><?php echo htmlspecialchars($event['title']); ?></h2>

        <div class="text-muted mb-4 d-flex align-items-center flex-wrap gap-3">
            <span><i class="far fa-calendar-alt text-primary me-1"></i> <?php echo date('F d, Y', strtotime($event['date'])); ?></span>
            <?php if($event['time']): ?>
                <span><i class="far fa-clock text-primary me-1"></i> <?php echo htmlspecialchars($event['time']); ?></span>
            <?php endif; ?>
            <?php if($event['location']): ?>
                <span><i class="fas fa-map-marker-alt text-primary me-1"></i> <?php echo htmlspecialchars($event['location']); ?></span>
            <?php endif; ?>
        </div>

        <?php if($event['image']): ?>
            <img src="<?php echo htmlspecialchars($event['image']); ?>" class="img-fluid rounded shadow-sm mb-4 w-100" alt="<?php echo htmlspecialchars($event['title']); ?>" style="max-height: 500px; object-fit: cover;">
        <?php endif; ?>

        <div class="content card p-4 border-0 shadow-sm">
            <div class="card-body">
                <?php echo $event['description']; // Safe HTML from admin ?>
            </div>
        </div>

        <div class="mt-4">
            <a href="events.php" class="btn btn-outline-primary"><i class="fas fa-arrow-left"></i> Back to Events</a>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
