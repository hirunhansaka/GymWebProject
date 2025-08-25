<?php
require_once 'auth_functions.php';

if (isLoggedIn()) {
    header("Location: member_dashboard.php");
    exit();
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $phone = $_POST['phone'];


    $stmt = $conn->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
    $stmt->bind_param("ss", $username, $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $error = "Username or email already exists.";
        header("Location: register.php");
        exit();
    } else {
        if (registerUser($username, $email, $password, $first_name, $last_name, $phone)) {
            $_SESSION['success'] = "Registration successful! You can now login.";
            header("Location: login.php?success");
            exit();
        } else {
            $_SESSION['error'] = "Registration failed. Please try again.";
            header("Location: register.php");
            exit();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - FitZone</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <header>
        <nav>
            <div class="logo">FitZone</div>
            <div class="menu-toggle">
                <i class="fas fa-bars"></i>
            </div>
            <ul class="nav-links">
                <li><a href="index.php">Home</a></li>
                <li><a href="about.php">About</a></li>
                <li><a href="services.php">Services</a></li>
                <li><a href="timetable.php">Timetable</a></li>
                <li><a href="membership.php">Membership</a></li>
                <li><a href="contact.php">Contact</a></li>
                <li><a href="blog.php">Blog</a></li>
                <li><a href="login.php" class="btn">Login</a></li>
            </ul>
        </nav>
    </header>

    <section class="register-page">
        <div class="register-container">
            <h1>Register at <span>FitZone</span></h1>
            <?php if ($error): ?>
                <div class="alert error"><?= $error ?></div>
            <?php endif; ?>
            <?php if ($success): ?>
                <div class="alert success"><?= $success ?></div>
            <?php endif; ?>

            <form action="register.php" method="POST">
                <div class="input-group">
                    <input type="text" name="username" placeholder="Username" required>
                    <i class="fas fa-user"></i>
                </div>

                <div class="input-group">
                    <input type="email" name="email" placeholder="Email" required>
                    <i class="fas fa-envelope"></i>
                </div>

                <div class="input-group">
                    <input type="password" name="password" placeholder="Password" required>
                    <i class="fas fa-lock"></i>
                </div>

                <div class="input-group">
                    <input type="text" name="first_name" placeholder="First Name" required>
                    <i class="fas fa-id-card"></i>
                </div>

                <div class="input-group">
                    <input type="text" name="last_name" placeholder="Last Name" required>
                    <i class="fas fa-id-card"></i>
                </div>

                <div class="input-group">
                    <input type="tel" name="phone" placeholder="Phone (optional)">
                    <i class="fas fa-phone"></i>
                </div>

                <button type="submit" class="btn">Register</button>
                <p>Already have an account? <a href="login.php">Login here</a></p>
            </form>
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

    <script src="js/script.js"></script>
</body>

</html>