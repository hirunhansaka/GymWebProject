<?php
require_once 'auth_functions.php';
$is_logged_in = isLoggedIn();
$user_type = $is_logged_in ? $_SESSION['user_type'] : null;



$blog_posts = [];
$result = $conn->query("SELECT * FROM blog_posts ORDER BY created_at DESC LIMIT 3");
if ($result) {
    $blog_posts = $result->fetch_all(MYSQLI_ASSOC);
}


$category_icons = [
    'workout' => '🏋️‍♂️',
    'nutrition' => '🍎',
    'success' => '🎉'
];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog - FitZone</title>
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


    <section class="blog">
        <h1 class="section-title">FitZone Blog</h1>


        <div class="blog-section fade-in">
            <h2>🏋️‍♂️ Workout Tips</h2>
            <div class="blog-content">
                <img src="assets/images/workout.jpg.jpg" alt="Workout">
                <p>Discover the best workouts to stay in shape. From cardio routines to strength training, we have you covered.</p>
                <div class="blog-details">
                    <h3>Effective Workout Strategies</h3>
                    <p>1. Start with a proper warm-up to prevent injuries and prepare your muscles.</p>
                    <p>2. Incorporate both cardio and strength training for balanced fitness.</p>
                    <p>3. Focus on proper form rather than lifting heavy weights.</p>
                    <p>4. Allow adequate rest between workout sessions for muscle recovery.</p>

                </div>
            </div>
        </div>


        <div class="blog-section fade-in">
            <h2>🍎 Healthy Recipes</h2>
            <div class="blog-content">
                <img src="assets/images/blog-recipes.jpg" alt="Healthy Food">
                <p>Explore our collection of nutritious and delicious meals to keep your diet on track.</p>
                <div class="blog-details">
                    <h3>Nutrition Tips for Fitness</h3>
                    <p>1. Meal prep in advance to maintain healthy eating habits throughout the week.</p>
                    <p>2. Include lean proteins, complex carbs, and healthy fats in every meal.</p>
                    <p>3. Stay hydrated - drink at least 2 liters of water daily.</p>
                    <p>4. Try our signature protein smoothie: banana, spinach, almond milk, and protein powder.</p>

                </div>
            </div>
        </div>


        <div class="blog-section fade-in">
            <h2>🎉 Success Stories</h2>
            <div class="blog-content">
                <img src="assets/images/blog-success.jpg" alt="Success Stories">
                <p>Be inspired by real-life transformations and success stories from our FitZone community.</p>
                <div class="blog-details">
                    <h3>Member Transformations</h3>
                    <p>1. John D. lost 30lbs in 3 months with our personalized training program.</p>
                    <p>2. Sarah M. gained 10lbs of muscle while reducing body fat percentage.</p>
                    <p>3. Mike T. completed his first marathon after 6 months of training.</p>
                    <p>4. Lisa K. overcame her back pain through our specialized mobility classes.</p>

                </div>
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