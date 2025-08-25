<?php
require_once 'auth_functions.php';
$is_logged_in = isLoggedIn();
$user_type = $is_logged_in ? $_SESSION['user_type'] : null;



$services = [
    [
        'blog' => 'fas fa-dumbbell',
        'title' => 'Strength Training',
        'description' => 'Build muscle and endurance with our state-of-the-art equipment and expert trainers.'
    ],
    [
        'icon' => 'fas fa-running',
        'title' => 'Cardio Workouts',
        'description' => 'Stay fit with high-intensity cardio sessions, including treadmill, cycling, and more.'
    ],
    [
        'icon' => 'fas fa-spa',
        'title' => 'Yoga & Meditation',
        'description' => 'Relax and rejuvenate with yoga sessions designed for flexibility and mindfulness.'
    ],
    [
        'icon' => 'fas fa-utensils',
        'title' => 'Nutrition Planning',
        'description' => 'Get expert diet plans tailored to your fitness goals for a balanced lifestyle.'
    ]
];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Our Services - FitZone</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
    <script src="js/script.js" defer></script>
</head>

<body>

    <header>
        <?php if ($is_logged_in) {

            switch ($user_type) {
                case 'admin':
                    require_once 'admin_navbar.php';
                    break;
                case 'manager':
                    require_once 'manager_navbar.php';
                    break;
                case 'member':
                    require_once 'member_navbar.php';
                    break;
            }
        } else {
            require_once 'public_navbar.php';
        } ?>
    </header>


    <section class="services-page">
        <h1 class="fade-in">Our <span>Services</span></h1>
        <p class="fade-in">Explore our wide range of fitness programs designed to keep you strong and healthy.</p>

        <div class="services-container">
            <div class="service-box fade-in">
                <i class="fas fa-dumbbell"></i>
                <h3>Strength Training</h3>
                <p>Build muscle and endurance with our state-of-the-art equipment and expert trainers.</p>
            </div>
            <div class="service-box fade-in">
                <i class="fas fa-running"></i>
                <h3>Cardio Workouts</h3>
                <p>Stay fit with high-intensity cardio sessions, including treadmill, cycling, and more.</p>
            </div>
            <div class="service-box fade-in">
                <i class="fas fa-spa"></i>
                <h3>Yoga & Meditation</h3>
                <p>Relax and rejuvenate with yoga sessions designed for flexibility and mindfulness.</p>
            </div>
            <div class="service-box fade-in">
                <i class="fas fa-utensils"></i>
                <h3>Nutrition Planning</h3>
                <p>Get expert diet plans tailored to your fitness goals for a balanced lifestyle.</p>
            </div>
        </div>
    </section>

    <footer>
        <div class="container">
            <div class="footer-content">
                <div class="footer-logo">
                    <span>Fit</span>Zone
                </div>
                <div class="footer-links">
                    <a href="about.php">About Us</a>
                    <a href="services.php">Services</a>
                    <a href="timetable.php">Timetable</a>
                    <a href="contact.php">Contact</a>
                </div>
                <div class="social-links">
                    <a href="#"><i class="fab fa-facebook-f"></i></a>
                    <a href="#"><i class="fab fa-instagram"></i></a>
                    <a href="#"><i class="fab fa-twitter"></i></a>
                    <a href="#"><i class="fab fa-youtube"></i></a>
                </div>
            </div>
            <div class="copyright">
                &copy; 2025 FitZone. All rights reserved.
            </div>
        </div>
    </footer>
</body>

</html>