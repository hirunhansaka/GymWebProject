<?php
require_once 'auth_functions.php';
$is_logged_in = isLoggedIn();
$user_type = $is_logged_in ? $_SESSION['user_type'] : null;

$is_logged_in = isLoggedIn();
$user_id = $is_logged_in ? $_SESSION['user_id'] : null;
$user = $is_logged_in ? getUserById($user_id) : null;


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['send_message'])) {
    if (!$is_logged_in) {
        header("Location: login.php");
        exit();
    }

    $subject = $_POST['subject'];
    $message = $_POST['message'];

    $stmt = $conn->prepare("INSERT INTO messages (user_id, subject, message) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $user_id, $subject, $message);

    if ($stmt->execute()) {
        $success = "Message sent successfully! We'll get back to you soon.";
    } else {
        $error = "Error sending message. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - FitZone</title>
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


    <section class="contact-page">
        <h1 class="fade-in">Get in <span>Touch</span></h1>
        <p class="fade-in">Have any questions? We'd love to hear from you.</p>

        <?php if (isset($success)): ?>
            <div class="alert success"><?= $success ?></div>
        <?php endif; ?>
        <?php if (isset($error)): ?>
            <div class="alert error"><?= $error ?></div>
        <?php endif; ?>

        <div class="contact-container">

            <div class="contact-info fade-in">
                <div class="info-box">
                    <i class="fas fa-map-marker-alt"></i>
                    <p>123 FitZone Street, Kurunegala, Sri Lanka</p>
                </div>
                <div class="info-box">
                    <i class="fas fa-phone-alt"></i>
                    <p>+94 71 234 5678</p>
                </div>
                <div class="info-box">
                    <i class="fas fa-envelope"></i>
                    <p>info@fitzone.com</p>
                </div>


                <div class="map-container fade-in">
                    <iframe
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3950.898516434943!2d80.3658594147742!3d7.956856994282148!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3afca4238a8b6729%3A0x3a4ce2c0b5a3a3a4!2sKurunegala%2C%20Sri%20Lanka!5e0!3m2!1sen!2sus!4v1620000000000!5m2!1sen!2sus"
                        width="100%"
                        height="300"
                        style="border:0;"
                        allowfullscreen=""
                        loading="lazy">
                    </iframe>
                </div>
            </div>


            <div class="contact-form fade-in">
                <?php if ($is_logged_in): ?>
                    <form method="POST">
                        <div class="form-group">
                            <input type="text" name="subject" placeholder="Subject" required>
                        </div>
                        <div class="form-group">
                            <textarea name="message" placeholder="Your message" required></textarea>
                        </div>
                        <button type="submit" name="send_message" class="btn">Send Message</button>
                    </form>
                <?php else: ?>
                    <div class="login-prompt">
                        <p>Please <a href="login.php">login</a> or <a href="register.php">register</a> to send us a message.</p>
                        <form>
                            <div class="form-group">
                                <input type="text" placeholder="Name" disabled>
                            </div>
                            <div class="form-group">
                                <input type="email" placeholder="Email" disabled>
                            </div>
                            <div class="form-group">
                                <textarea placeholder="Your message" disabled></textarea>
                            </div>
                            <button type="button" class="btn" disabled>Send Message</button>
                        </form>
                    </div>
                <?php endif; ?>
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

    <style>
        .alert {
            max-width: 800px;
            margin: 20px auto;
            padding: 15px;
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

        .login-prompt {
            text-align: center;
            margin-bottom: 20px;
        }

        .login-prompt a {
            color: #E63946;
            text-decoration: none;
            font-weight: bold;
        }

        .login-prompt a:hover {
            text-decoration: underline;
        }

        .form-group input:disabled,
        .form-group textarea:disabled {
            background: #333;
            color: #666;
        }

        button:disabled {
            background: #666;
            cursor: not-allowed;
        }
    </style>

    <script src="js/script.js"></script>
</body>

</html>