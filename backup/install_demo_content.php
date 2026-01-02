<?php
require_once 'config.php';

// List of pages to ensure exist
$pages_to_create = [
    'vision-mission' => ['title' => 'Vision & Mission', 'content' => '<p><strong>Our Vision:</strong> To be a leading institution of learning...</p><p><strong>Our Mission:</strong> To empower students...</p>'],
    'history' => ['title' => 'Our History', 'content' => '<p>Established in 1990, our college has a rich history...</p>'],
    'principal-message' => ['title' => 'Principal\'s Message', 'content' => '<p>Welcome to our college. We strive for excellence...</p>'],
    'core-team' => ['title' => 'Core Team', 'content' => '<p>Meet our dedicated leadership team...</p>'],
    'principal-office' => ['title' => 'Principal Office', 'content' => '<p>Information about the Principal Office...</p>'],
    'vice-principal-office' => ['title' => 'Vice Principal Office', 'content' => '<p>Information about the Vice Principal Office...</p>'],
    'controller-office' => ['title' => 'Controller of Examinations', 'content' => '<p>Details about examination schedules and results...</p>'],
    'student-affairs' => ['title' => 'Student Affairs', 'content' => '<p>We are here to support student life...</p>'],
    'program-intermediate' => ['title' => 'Intermediate Programs', 'content' => '<p>We offer FA, FSc, ICS, and I.Com programs...</p>'],
    'program-bs-4ydp' => ['title' => 'BS (4-Year) Programs', 'content' => '<p>Our BS programs include Computer Science, English, and more...</p>'],
    'facilities' => ['title' => 'Campus Facilities', 'content' => '<p>We provide state-of-the-art labs, library, and sports grounds...</p>'],
    'college-societies' => ['title' => 'College Societies', 'content' => '<p>Join our Debating, Dramatic, and Science societies...</p>'],
    'girls-guide' => ['title' => 'Girls Guide Association', 'content' => '<p>Information about the Girls Guide activities...</p>'],
    'co-curricular' => ['title' => 'Co-curricular Activities', 'content' => '<p>Sports, debates, and competitions...</p>'],
    'hostel' => ['title' => 'Hostel Facilities', 'content' => '<p>Secure and comfortable accommodation for students...</p>'],
    'library' => ['title' => 'Library', 'content' => '<p>Our library houses over 50,000 books...</p>'],
    'career-counselling' => ['title' => 'Career Counselling', 'content' => '<p>Guidance for your future career paths...</p>'],
    'success-stories' => ['title' => 'Success Stories', 'content' => '<p>Read about our alumni achievements...</p>'],
    'donate' => ['title' => 'Donate to Alumni Fund', 'content' => '<p>Support the next generation of students...</p>']
];

foreach ($pages_to_create as $slug => $data) {
    $slug_esc = $conn->real_escape_string($slug);
    $check = $conn->query("SELECT id FROM pages WHERE slug = '$slug_esc'");

    if ($check->num_rows == 0) {
        $title = $conn->real_escape_string($data['title']);
        $content = $conn->real_escape_string($data['content']);
        $sql = "INSERT INTO pages (slug, title, content) VALUES ('$slug_esc', '$title', '$content')";
        if ($conn->query($sql)) {
            echo "Created page: $slug<br>";
        } else {
            echo "Error creating $slug: " . $conn->error . "<br>";
        }
    } else {
        echo "Page exists: $slug<br>";
    }
}
?>
