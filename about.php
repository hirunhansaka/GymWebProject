<?php
require_once 'auth_functions.php';
$is_logged_in = isLoggedIn();
$user_type = $is_logged_in ? $_SESSION['user_type'] : null;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - FitZone</title>
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


    <main class="page-transition">

        <section class="about-section">
            <div class="container">
                <div class="content-wrapper">
                    <div class="text-content">
                        <h1>About <span>FitZone</span></h1>
                        <p>At FitZone, we are committed to helping you achieve your fitness goals with expert trainers, world-class equipment, and a community that inspires you.</p>
                        <p>Join us and experience the difference. From cardio workouts to strength training, yoga, and personalized training plans, we have everything you need to stay fit and healthy.</p>
                        <a href="membership.php" class="btn">Join Now</a>
                    </div>
                    <div class="image-content">
                        <img src="assets/images/about-us.jpg" alt="FitZone Gym">
                    </div>
                </div>
            </div>
        </section>


        <section class="trainers-section">
            <div class="container">
                <div class="section-header">
                    <h2>Our <span>Trainers</span></h2>
                    <p>Meet the experts who will guide you on your fitness journey.</p>
                </div>
                <div class="card-container">
                    <div class="card">
                        <img src="assets/images/trainer1.jpg" alt="Trainer 1">
                        <h3>Akalanka Dias</h3>
                        <p>Strength & Conditioning</p>
                        <div class="social-icons">
                            <a href="#"><i class="fab fa-instagram"></i></a>
                            <a href="#"><i class="fab fa-linkedin"></i></a>
                        </div>
                    </div>
                    <div class="card">
                        <img src="assets/images/trainer2.jpg" alt="Trainer 2">
                        <h3>Stephani De Silva</h3>
                        <p>Yoga & Mobility</p>
                        <div class="social-icons">
                            <a href="#"><i class="fab fa-instagram"></i></a>
                            <a href="#"><i class="fab fa-linkedin"></i></a>
                        </div>
                    </div>
                    <div class="card">
                        <img src="assets/images/trainer3.jpg" alt="Trainer 3">
                        <h3>Mohan Perera</h3>
                        <p>Functional Training</p>
                        <div class="social-icons">
                            <a href="#"><i class="fab fa-instagram"></i></a>
                            <a href="#"><i class="fab fa-linkedin"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </section>


        <section class="facilities-section">
            <div class="container">
                <div class="section-header">
                    <h2>Our <span>Facilities</span></h2>
                    <p>State-of-the-art equipment and premium amenities.</p>
                </div>
                <div class="card-container">
                    <div class="card">
                        <img src="assets/images/about-us.jpg" alt="Gym Floor">
                        <h3>Premium Gym Floor</h3>
                        <p>Top-tier cardio and strength equipment.</p>
                    </div>
                    <div class="card">
                        <img src="assets/images/yoga-studio.jpg" alt="Yoga Studio">
                        <h3>Yoga Studio</h3>
                        <p>Peaceful space for mindfulness and flexibility.</p>
                    </div>
                    <div class="card">
                        <img src="assets/images/locker-room.jpg" alt="Locker Room">
                        <h3>Luxury Locker Rooms</h3>
                        <p>Spacious and clean with premium amenities.</p>
                    </div>
                </div>
            </div>
        </section>
    </main>
    <footer>
        <div class="container">
            <div class="footer-content">
                <div class="footer-logo">
                    <span>Fit</span>Zone
                </div>
                <div class="footer-links">
                    <a href="about.html">About Us</a>
                    <a href="services.html">Services</a>
                    <a href="timetable.html">Timetable</a>
                    <a href="contact.html">Contact</a>
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