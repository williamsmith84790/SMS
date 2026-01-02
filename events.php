<?php
$page_title = "Events";
require_once 'includes/header.php';

// Fetch Events
$sql = "SELECT * FROM events ORDER BY date DESC";
$result = $conn->query($sql);
?>

<div class="text-center mb-5">
    <h1 class="fw-bold">Upcoming Events</h1>
    <p class="text-muted">Join us for our upcoming activities and ceremonies.</p>
</div>

<div class="row g-4">
    <?php if ($result && $result->num_rows > 0): ?>
        <?php while($event = $result->fetch_assoc()): ?>
        <div class="col-md-6 col-lg-4">
            <div class="card h-100 shadow-sm border-0">
                <div class="bg-primary text-white text-center py-2" style="width: 60px; position: absolute; top: 10px; left: 10px; border-radius: 5px; box-shadow: 0 2px 5px rgba(0,0,0,0.2);">
                    <div class="fw-bold h5 mb-0"><?php echo date('d', strtotime($event['date'])); ?></div>
                    <small><?php echo strtoupper(date('M', strtotime($event['date']))); ?></small>
                </div>
                <?php if($event['image']): ?>
                    <img src="<?php echo htmlspecialchars($event['image']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($event['title']); ?>" style="height: 250px; object-fit: cover;">
                <?php else: ?>
                    <img src="https://via.placeholder.com/400x250?text=Event" class="card-img-top" alt="Event" style="height: 250px; object-fit: cover;">
                <?php endif; ?>
                <div class="card-body mt-2">
                    <h5 class="card-title text-primary fw-bold"><?php echo htmlspecialchars($event['title']); ?></h5>
                    <p class="card-text text-muted small">
                        <?php if($event['time']): ?><i class="far fa-clock text-warning me-1"></i> <?php echo htmlspecialchars($event['time']); ?> <br><?php endif; ?>
                        <?php if($event['location']): ?><i class="fas fa-map-marker-alt text-warning me-1"></i> <?php echo htmlspecialchars($event['location']); ?><?php endif; ?>
                    </p>
                    <div class="card-text description-text"><?php echo $event['description']; // Assumed safe HTML from admin ?></div>
                </div>
            </div>
        </div>
        <?php endwhile; ?>
    <?php else: ?>
        <div class="col-12 text-center py-5">
            <p class="text-muted">No upcoming events listed at the moment.</p>
        </div>
    <?php endif; ?>
</div>

<style>
    .description-text p { margin-bottom: 0.5rem; }
    .description-text { font-size: 0.95rem; color: #555; }
</style>

<?php require_once 'includes/footer.php'; ?>
