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
    <title>FitZone Fitness Center</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<?php if (isset($_GET['logged_out'])): ?>
    <style>
        .popup-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
        }

        .popup-modal {
            background: #000;
            padding: 20px 30px;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }

        .popup-modal button {
            margin-top: 15px;
            padding: 8px 15px;
            border: none;
            background-color:rgb(227, 16, 20);
            color: white;
            border-radius: 5px;
            cursor: pointer;
        }

        .popup-modal button:hover {
            background-color:rgb(246, 115, 119);
        }
    </style>

    <div class="popup-overlay" id="logoutPopup">
        <div class="popup-modal">
            <p>You have been logged out successfully.</p>
            <button onclick="document.getElementById('logoutPopup').style.display='none'">OK</button>
        </div>
    </div>
<?php endif; ?>

  
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

   
    <section class="hero">
        <div class="hero-content">
            <h1>Welcome to <span>FitZone</span></h1>
            <p>Your Ultimate Destination for Fitness & Well-being</p>
            <a href="membership.php" class="btn">Join Now</a>
        </div>
    </section>

    
    <section class="about">
        <div class="container">
            <h2>Why Choose FitZone?</h2>
            <p>We provide the best fitness experience with expert trainers and top-class facilities.</p>
        </div>
    </section>

    
    <section class="bmi-calculator">
        <div class="container">
            <h2>CALCULATE YOUR BMI</h2>
            <div class="calculator">
                <div class="input-group">
                    <label for="height">YOUR HEIGHT (cm)</label>
                    <input type="number" id="height" placeholder="170">
                </div>
                <div class="input-group">
                    <label for="weight">YOUR WEIGHT (kg)</label>
                    <input type="number" id="weight" placeholder="70">
                </div>
                <button id="calculate" class="btn">Calculate BMI</button>
                <div class="result">
                    <h3>Your BMI: <span id="bmi-result">0</span></h3>
                    <p id="bmi-category">Enter your details</p>
                </div>
            </div>
        </div>
    </section>
    
    

    <script src="js/script.js"></script>

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