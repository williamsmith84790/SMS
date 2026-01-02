<?php
$page_title = "Events";
require_once 'includes/header.php';

// Ideally, create an events table. For now, using Notices categorized as 'Event' or just all notices for demo
// Or creating a simple static structure or leveraging the Pages system if needed.
// Let's assume we want a specific "Events" page. For this task, I'll create a simple listing based on a new 'events' logic or re-use notices with a specific flag if we had one.
// Since we don't have an Events table yet, I will display a "Coming Soon" or a static list.
?>

<div class="text-center mb-5">
    <h1 class="fw-bold">Upcoming Events</h1>
    <p class="text-muted">Join us for our upcoming activities and ceremonies.</p>
</div>

<div class="row g-4">
    <!-- Static Demo Event 1 -->
    <div class="col-md-6 col-lg-4">
        <div class="card h-100 shadow-sm border-0">
            <div class="bg-primary text-white text-center py-2" style="width: 60px; position: absolute; top: 10px; left: 10px; border-radius: 5px;">
                <div class="fw-bold h5 mb-0">15</div>
                <small>AUG</small>
            </div>
            <img src="https://via.placeholder.com/400x250?text=Event+Image" class="card-img-top" alt="Event">
            <div class="card-body mt-2">
                <h5 class="card-title"><a href="#" class="text-dark text-decoration-none">Annual Sports Gala</a></h5>
                <p class="card-text text-muted small"><i class="far fa-clock"></i> 09:00 AM - 05:00 PM <br> <i class="fas fa-map-marker-alt"></i> Main Sports Ground</p>
                <p class="card-text">Annual sports competitions involving various athletics and team games.</p>
            </div>
            <div class="card-footer bg-white border-top-0">
                <a href="#" class="btn btn-outline-primary btn-sm rounded-pill px-3">Read More</a>
            </div>
        </div>
    </div>

    <!-- Static Demo Event 2 -->
    <div class="col-md-6 col-lg-4">
        <div class="card h-100 shadow-sm border-0">
            <div class="bg-primary text-white text-center py-2" style="width: 60px; position: absolute; top: 10px; left: 10px; border-radius: 5px;">
                <div class="fw-bold h5 mb-0">22</div>
                <small>SEP</small>
            </div>
            <img src="https://via.placeholder.com/400x250?text=Convocation" class="card-img-top" alt="Event">
            <div class="card-body mt-2">
                <h5 class="card-title"><a href="#" class="text-dark text-decoration-none">Convocation 2023</a></h5>
                <p class="card-text text-muted small"><i class="far fa-clock"></i> 10:00 AM <br> <i class="fas fa-map-marker-alt"></i> University Auditorium</p>
                <p class="card-text">Awarding degrees to the graduating batch of 2023 with distinguished guests.</p>
            </div>
            <div class="card-footer bg-white border-top-0">
                <a href="#" class="btn btn-outline-primary btn-sm rounded-pill px-3">Read More</a>
            </div>
        </div>
    </div>

    <!-- Static Demo Event 3 -->
    <div class="col-md-6 col-lg-4">
        <div class="card h-100 shadow-sm border-0">
            <div class="bg-primary text-white text-center py-2" style="width: 60px; position: absolute; top: 10px; left: 10px; border-radius: 5px;">
                <div class="fw-bold h5 mb-0">10</div>
                <small>OCT</small>
            </div>
            <img src="https://via.placeholder.com/400x250?text=Seminar" class="card-img-top" alt="Event">
            <div class="card-body mt-2">
                <h5 class="card-title"><a href="#" class="text-dark text-decoration-none">Science Seminar</a></h5>
                <p class="card-text text-muted small"><i class="far fa-clock"></i> 11:00 AM <br> <i class="fas fa-map-marker-alt"></i> Seminar Hall</p>
                <p class="card-text">A seminar on recent advancements in Artificial Intelligence and Robotics.</p>
            </div>
            <div class="card-footer bg-white border-top-0">
                <a href="#" class="btn btn-outline-primary btn-sm rounded-pill px-3">Read More</a>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
