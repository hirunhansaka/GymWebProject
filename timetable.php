<?php
require_once 'auth_functions.php';
$is_logged_in = isLoggedIn();
$user_type = $is_logged_in ? $_SESSION['user_type'] : null;



$classes = [];
$result = $conn->query("SELECT * FROM classes ORDER BY day, start_time");
if ($result) {
    $classes = $result->fetch_all(MYSQLI_ASSOC);
}


$groupedClasses = [];
foreach ($classes as $class) {
    $groupedClasses[$class['day']][] = $class;
}


$days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Class Timetable | FitZone</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;600;700&display=swap" rel="stylesheet">
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

        <section class="timetable-hero">
            <div class="hero-content">
                <h1>Class <span>Timetable</span></h1>
                <p>Plan your fitness journey with our expert-led classes</p>
            </div>
        </section>


        <section class="timetable-section">
            <div class="container">
                <div class="section-intro">
                    <h2>Find Your Perfect <span>Workout</span></h2>
                    <p>Filter by class type to find your preferred sessions</p>
                </div>

                <div class="timetable-wrapper">

                    <div class="class-filters">
                        <div class="filter-group">
                            <button class="filter-btn active" data-filter="all">
                                <i class="fas fa-calendar-alt"></i> All Classes
                            </button>
                            <button class="filter-btn" data-filter="yoga">
                                <i class="fas fa-spa"></i> Yoga
                            </button>
                            <button class="filter-btn" data-filter="hiit">
                                <i class="fas fa-bolt"></i> HIIT
                            </button>
                            <button class="filter-btn" data-filter="strength">
                                <i class="fas fa-dumbbell"></i> Strength
                            </button>
                            <button class="filter-btn" data-filter="cardio">
                                <i class="fas fa-heartbeat"></i> Cardio
                            </button>
                        </div>
                    </div>


                    <div class="timetable-container">
                        <div class="timetable-grid">

                            <div class="time-column">
                                <div class="time-header">Time</div>
                                <div class="time-slot">6:00 AM</div>
                                <div class="time-slot">8:00 AM</div>
                                <div class="time-slot">12:00 PM</div>
                                <div class="time-slot">5:00 PM</div>
                                <div class="time-slot">7:00 PM</div>
                            </div>


                            <div class="day-column">
                                <div class="day-header">Monday</div>
                                <div class="class-slot yoga" data-category="yoga">
                                    <div class="class-card">
                                        <h3>Morning Yoga</h3>
                                        <div class="class-meta">
                                            <span><i class="far fa-clock"></i> 60 min</span>
                                            <span><i class="fas fa-user"></i> Stephani</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="class-slot strength" data-category="strength">
                                    <div class="class-card">
                                        <h3>Power Lifting</h3>
                                        <div class="class-meta">
                                            <span><i class="far fa-clock"></i> 45 min</span>
                                            <span><i class="fas fa-user"></i> Akalanka</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="class-slot cardio" data-category="cardio">
                                    <div class="class-card">
                                        <h3>Spin Class</h3>
                                        <div class="class-meta">
                                            <span><i class="far fa-clock"></i> 60 min</span>
                                            <span><i class="fas fa-user"></i> Mohan</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="class-slot hiit" data-category="hiit">
                                    <div class="class-card">
                                        <h3>HIIT Blast</h3>
                                        <div class="class-meta">
                                            <span><i class="far fa-clock"></i> 30 min</span>
                                            <span><i class="fas fa-user"></i> Mohan</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="class-slot yoga" data-category="yoga">
                                    <div class="class-card">
                                        <h3>Evening Flow</h3>
                                        <div class="class-meta">
                                            <span><i class="far fa-clock"></i> 45 min</span>
                                            <span><i class="fas fa-user"></i> Stephani</span>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="day-column">
                                <div class="day-header">Tuesday</div>
                                <div class="class-slot hiit" data-category="hiit">
                                    <div class="class-card">
                                        <h3>HIIT Burn</h3>
                                        <div class="class-meta">
                                            <span><i class="far fa-clock"></i> 45 min</span>
                                            <span><i class="fas fa-user"></i> Mohan</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="class-slot cardio" data-category="cardio">
                                    <div class="class-card">
                                        <h3>Spin Class</h3>
                                        <div class="class-meta">
                                            <span><i class="far fa-clock"></i> 60 min</span>
                                            <span><i class="fas fa-user"></i> Mohan</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="class-slot strength" data-category="strength">
                                    <div class="class-card">
                                        <h3>Body Sculpt</h3>
                                        <div class="class-meta">
                                            <span><i class="far fa-clock"></i> 45 min</span>
                                            <span><i class="fas fa-user"></i> Akalanka</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="class-slot yoga" data-category="yoga">
                                    <div class="class-card">
                                        <h3>Yoga Basics</h3>
                                        <div class="class-meta">
                                            <span><i class="far fa-clock"></i> 60 min</span>
                                            <span><i class="fas fa-user"></i> Stephani</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="empty-cell"></div>
                            </div>


                            <div class="day-column">
                                <div class="day-header">Wednesday</div>
                                <div class="class-slot yoga" data-category="yoga">
                                    <div class="class-card">
                                        <h3>Sunrise Yoga</h3>
                                        <div class="class-meta">
                                            <span><i class="far fa-clock"></i> 60 min</span>
                                            <span><i class="fas fa-user"></i> Stephani</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="class-slot strength" data-category="strength">
                                    <div class="class-card">
                                        <h3>Core Power</h3>
                                        <div class="class-meta">
                                            <span><i class="far fa-clock"></i> 45 min</span>
                                            <span><i class="fas fa-user"></i> Akalanka</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="class-slot cardio" data-category="cardio">
                                    <div class="class-card">
                                        <h3>Cardio Kickboxing</h3>
                                        <div class="class-meta">
                                            <span><i class="far fa-clock"></i> 45 min</span>
                                            <span><i class="fas fa-user"></i> Mohan</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="class-slot hiit" data-category="hiit">
                                    <div class="class-card">
                                        <h3>Tabata</h3>
                                        <div class="class-meta">
                                            <span><i class="far fa-clock"></i> 30 min</span>
                                            <span><i class="fas fa-user"></i> Mohan</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="class-slot yoga" data-category="yoga">
                                    <div class="class-card">
                                        <h3>Restorative Yoga</h3>
                                        <div class="class-meta">
                                            <span><i class="far fa-clock"></i> 60 min</span>
                                            <span><i class="fas fa-user"></i> Stephani</span>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="day-column">
                                <div class="day-header">Thursday</div>
                                <div class="class-slot hiit" data-category="hiit">
                                    <div class="class-card">
                                        <h3>HIIT Blast</h3>
                                        <div class="class-meta">
                                            <span><i class="far fa-clock"></i> 45 min</span>
                                            <span><i class="fas fa-user"></i> Mohan</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="class-slot cardio" data-category="cardio">
                                    <div class="class-card">
                                        <h3>Spin Class</h3>
                                        <div class="class-meta">
                                            <span><i class="far fa-clock"></i> 60 min</span>
                                            <span><i class="fas fa-user"></i> Mohan</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="class-slot strength" data-category="strength">
                                    <div class="class-card">
                                        <h3>Functional Training</h3>
                                        <div class="class-meta">
                                            <span><i class="far fa-clock"></i> 45 min</span>
                                            <span><i class="fas fa-user"></i> Akalanka</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="class-slot yoga" data-category="yoga">
                                    <div class="class-card">
                                        <h3>Vinyasa Flow</h3>
                                        <div class="class-meta">
                                            <span><i class="far fa-clock"></i> 60 min</span>
                                            <span><i class="fas fa-user"></i> Stephani</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="empty-cell"></div>
                            </div>


                            <div class="day-column">
                                <div class="day-header">Friday</div>
                                <div class="class-slot yoga" data-category="yoga">
                                    <div class="class-card">
                                        <h3>Morning Flow</h3>
                                        <div class="class-meta">
                                            <span><i class="far fa-clock"></i> 60 min</span>
                                            <span><i class="fas fa-user"></i> Stephani</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="class-slot strength" data-category="strength">
                                    <div class="class-card">
                                        <h3>Total Body</h3>
                                        <div class="class-meta">
                                            <span><i class="far fa-clock"></i> 45 min</span>
                                            <span><i class="fas fa-user"></i> Akalanka</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="class-slot cardio" data-category="cardio">
                                    <div class="class-card">
                                        <h3>Zumba</h3>
                                        <div class="class-meta">
                                            <span><i class="far fa-clock"></i> 45 min</span>
                                            <span><i class="fas fa-user"></i> Mohan</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="class-slot hiit" data-category="hiit">
                                    <div class="class-card">
                                        <h3>HIIT Circuit</h3>
                                        <div class="class-meta">
                                            <span><i class="far fa-clock"></i> 30 min</span>
                                            <span><i class="fas fa-user"></i> Mohan</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="class-slot yoga" data-category="yoga">
                                    <div class="class-card">
                                        <h3>Yin Yoga</h3>
                                        <div class="class-meta">
                                            <span><i class="far fa-clock"></i> 60 min</span>
                                            <span><i class="fas fa-user"></i> Stephani</span>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="day-column">
                                <div class="day-header">Saturday</div>
                                <div class="class-slot cardio" data-category="cardio">
                                    <div class="class-card">
                                        <h3>Cardio Kickstart</h3>
                                        <div class="class-meta">
                                            <span><i class="far fa-clock"></i> 60 min</span>
                                            <span><i class="fas fa-user"></i> Akalanka</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="class-slot hiit" data-category="hiit">
                                    <div class="class-card">
                                        <h3>Weekend HIIT</h3>
                                        <div class="class-meta">
                                            <span><i class="far fa-clock"></i> 45 min</span>
                                            <span><i class="fas fa-user"></i> Mohan</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="class-slot yoga" data-category="yoga">
                                    <div class="class-card">
                                        <h3>Weekend Yoga</h3>
                                        <div class="class-meta">
                                            <span><i class="far fa-clock"></i> 60 min</span>
                                            <span><i class="fas fa-user"></i> Stephani</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="empty-cell"></div>
                                <div class="empty-cell"></div>
                            </div>


                            <div class="day-column">
                                <div class="day-header">Sunday</div>
                                <div class="class-slot yoga" data-category="yoga">
                                    <div class="class-card">
                                        <h3>Sunday Stretch</h3>
                                        <div class="class-meta">
                                            <span><i class="far fa-clock"></i> 60 min</span>
                                            <span><i class="fas fa-user"></i> Stephani</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="class-slot strength" data-category="strength">
                                    <div class="class-card">
                                        <h3>Sunday Strength</h3>
                                        <div class="class-meta">
                                            <span><i class="far fa-clock"></i> 45 min</span>
                                            <span><i class="fas fa-user"></i> Akalanka</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="empty-cell"></div>
                                <div class="empty-cell"></div>
                                <div class="empty-cell"></div>
                            </div>
                        </div>
                    </div>


                    <div class="timetable-legend">
                        <div class="legend-item">
                            <span class="legend-color yoga"></span>
                            <span>Yoga</span>
                        </div>
                        <div class="legend-item">
                            <span class="legend-color hiit"></span>
                            <span>HIIT</span>
                        </div>
                        <div class="legend-item">
                            <span class="legend-color strength"></span>
                            <span>Strength</span>
                        </div>
                        <div class="legend-item">
                            <span class="legend-color cardio"></span>
                            <span>Cardio</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>


        <section class="cta-section">
            <div class="container">
                <h2>Ready to Transform Your Body?</h2>
                <p>Join FitZone today and start your fitness journey</p>
                <div class="cta-buttons">
                    <a href="membership.php" class="btn btn-primary">Join Now</a>
                    <a href="contact.php" class="btn btn-secondary">Contact Us</a>
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