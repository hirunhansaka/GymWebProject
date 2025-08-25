<?php
require_once 'auth_functions.php';

if (isLoggedIn()) {

    switch ($_SESSION['user_type']) {
        case 'admin':
            header("Location: admin_dashboard.php");
            break;
        case 'manager':
            header("Location: manager_dashboard.php");
            break;
        case 'member':
            header("Location: member_dashboard.php");
            break;
        default:
            header("Location: index.php");
    }
    exit();
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (loginUser($username, $password)) {

        switch ($_SESSION['user_type']) {
            case 'admin':
                header("Location: admin_dashboard.php");
                break;
            case 'manager':
                header("Location: manager_dashboard.php");
                break;
            case 'member':
                header("Location: member_dashboard.php");
                break;
            default:
                header("Location: index.php");
        }
        exit();
    } else {
        $error = "Invalid username or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - FitZone</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
    <script src="js/script.js" defer></script>
</head>

<body>
 
<?php if (isset($_GET['success'])): ?>
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
            <p>Registration successful! You can now login.</p>
            <button onclick="document.getElementById('logoutPopup').style.display='none'">OK</button>
        </div>
    </div>
<?php endif; ?>

   
    <header>
        <nav>
            <div class="logo">FitZone</div>
            <div class="menu-toggle">
                <i class="fas fa-bars"></i>
            </div>
            <ul class="nav-links">
                <li><a href="index.php" class="<?= basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : '' ?>">Home</a></li>
                <li><a href="about.php" class="<?= basename($_SERVER['PHP_SELF']) == 'about.php' ? 'active' : '' ?>">About</a></li>
                <li><a href="services.php" class="<?= basename($_SERVER['PHP_SELF']) == 'services.php' ? 'active' : '' ?>">Services</a></li>
                <li><a href="timetable.php" class="<?= basename($_SERVER['PHP_SELF']) == 'timetable.php' ? 'active' : '' ?>">Timetable</a></li>
                <li><a href="membership.php" class="<?= basename($_SERVER['PHP_SELF']) == 'membership.php' ? 'active' : '' ?>">Membership</a></li>
                <li><a href="contact.php" class="<?= basename($_SERVER['PHP_SELF']) == 'contact.php' ? 'active' : '' ?>">Contact</a></li>
                <li><a href="blog.php" class="<?= basename($_SERVER['PHP_SELF']) == 'blog.php' ? 'active' : '' ?>">Blog</a></li>
                <li>
                    <form action="search.php" method="GET" class="nav-search">
                        <input type="text" name="q" placeholder="Search...">
                        <button type="submit"><i class="fas fa-search"></i></button>
                    </form>
                </li>
                <li><a href="login.php" class="btn">Login</a></li>
            </ul>
        </nav>
    </header>

    <section class="login-page">
        <div class="login-container fade-in">
            <h1>Login to <span>FitZone</span></h1>
            <?php if ($error): ?>
                <div class="alert error"><?= $error ?></div>
            <?php endif; ?>
            <form action="login.php" method="POST">
                <div class="input-box">
                    <input type="text" name="username" placeholder="Enter your username" required>
                    <i class="fas fa-user"></i>
                </div>
                <div class="input-box">
                    <input type="password" id="password" name="password" placeholder="Enter your password" required>
                    <i class="fas fa-lock"></i>
                    <span class="toggle-password"><i class="fas fa-eye"></i></span>
                </div>
                <button type="submit" class="btn">Login</button>
                <p>Don't have an account? <a href="register.php">Sign Up</a></p>
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


</body>

</html>