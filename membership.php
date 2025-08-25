<?php
require_once 'auth_functions.php';
$is_logged_in = isLoggedIn();
$user_type = $is_logged_in ? $_SESSION['user_type'] : null;


$is_logged_in = isLoggedIn();
$user_id = $is_logged_in ? $_SESSION['user_id'] : null;


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['select_membership'])) {
    if (!$is_logged_in) {
        header("Location: register.php");
        exit();
    }

    $plan_type = $_POST['plan_type'];
    $start_date = date('Y-m-d');
    $end_date = date('Y-m-d', strtotime('+1 month'));


    $result = $conn->query("SELECT id FROM memberships WHERE user_id = $user_id");
    if ($result && $result->num_rows > 0) {

        $stmt = $conn->prepare("UPDATE memberships SET plan_type = ?, start_date = ?, end_date = ?, status = 'active' WHERE user_id = ?");
        $stmt->bind_param("sssi", $plan_type, $start_date, $end_date, $user_id);
    } else {

        $stmt = $conn->prepare("INSERT INTO memberships (user_id, plan_type, start_date, end_date) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("isss", $user_id, $plan_type, $start_date, $end_date);
    }

    if ($stmt->execute()) {
        $success = "Membership activated successfully!";
    } else {
        $error = "Error processing membership. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Membership - FitZone</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
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


    <section class="membership-page">
        <h1 class="fade-in">Choose Your <span>Membership</span></h1>
        <p class="fade-in">Select the best plan that fits your fitness journey.</p>

        <?php if (isset($success)): ?>
            <div class="alert success"><?= $success ?></div>
        <?php endif; ?>
        <?php if (isset($error)): ?>
            <div class="alert error"><?= $error ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="membership-container">
                <div class="membership-box fade-in">
                    <h3>Basic</h3>
                    <div class="price">Rs.5000<span>/month</span></div>
                    <ul>
                        <li><i class="fas fa-check"></i> Access to Gym Equipment</li>
                        <li><i class="fas fa-check"></i> Locker Room Access</li>
                        <li><i class="fas fa-times"></i> Group Classes</li>
                        <li><i class="fas fa-times"></i> Personal Trainer</li>
                    </ul>
                    <a href="register.php" class="btn">Join Now</a>

                </div>

                <div class="membership-box popular fade-in">
                    <h3>Standard</h3>
                    <div class="price">Rs.10,000<span>/month</span></div>
                    <ul>
                        <li><i class="fas fa-check"></i> Access to Gym Equipment</li>
                        <li><i class="fas fa-check"></i> Locker Room Access</li>
                        <li><i class="fas fa-check"></i> Group Classes</li>
                        <li><i class="fas fa-times"></i> Personal Trainer</li>
                    </ul>
                    <a href="register.php" class="btn">Join Now</a>
                </div>

                <div class="membership-box fade-in">
                    <h3>Premium</h3>
                    <div class="price">Rs.15,000<span>/month</span></div>
                    <ul>
                        <li><i class="fas fa-check"></i> Access to Gym Equipment</li>
                        <li><i class="fas fa-check"></i> Locker Room Access</li>
                        <li><i class="fas fa-check"></i> Group Classes</li>
                        <li><i class="fas fa-check"></i> Personal Trainer</li>
                    </ul>
                    <a href="register.php" class="btn">Join Now</a>
                </div>
            </div>
        </form>
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

    <style>
        .alert {
            padding: 15px;
            margin: 20px auto;
            max-width: 800px;
            border-radius: 5px;
            text-align: center;
        }

        .alert.success {
            background: #4CAF50;
            color: white;
        }

        .alert.error {
            background: #f44336;
            color: white;
        }
    </style>

    <script src="js/script.js"></script>
</body>

</html>