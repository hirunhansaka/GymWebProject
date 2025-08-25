<?php
require_once 'auth_functions.php';

if (!isMember()) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$user = getUserById($user_id);


$membership = null;
$result = $conn->query("SELECT * FROM memberships WHERE user_id = $user_id");
if ($result && $result->num_rows > 0) {
    $membership = $result->fetch_assoc();
}


$appointments = [];
$result = $conn->query("SELECT * FROM appointments WHERE user_id = $user_id ORDER BY appointment_date DESC, appointment_time DESC");
if ($result) {
    $appointments = $result->fetch_all(MYSQLI_ASSOC);
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['send_message'])) {
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


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['book_appointment'])) {
    $appointment_date = $_POST['appointment_date'];
    $appointment_time = $_POST['appointment_time'];
    $service_type = $_POST['service_type'];
    $notes = $_POST['notes'];

    $stmt = $conn->prepare("INSERT INTO appointments (user_id, appointment_date, appointment_time, service_type, notes) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("issss", $user_id, $appointment_date, $appointment_time, $service_type, $notes);

    if ($stmt->execute()) {
        $_SESSION['success'] = "Appointment booked successfully!";
        header("Location: member_dashboard.php");
        exit();
    } else {
        $error = "Error booking appointment. Please try again.";
    }
}


if (isset($_SESSION['success'])) {
    $success = $_SESSION['success'];
    unset($_SESSION['success']);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Member Dashboard - FitZone</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/chart.js@3.7.1/dist/chart.min.css">
    <link rel="stylesheet" href="./css/style.css">
</head>

<body>

    <header>
        <?php require_once 'member_navbar.php'; ?>
    </header>

    <main class="member-container">

        <section class="welcome-banner">
            <div class="welcome-content">
                <h1>Welcome Back, <span><?= htmlspecialchars($user['first_name']) ?></span>!</h1>
                <p class="welcome-message">Track your fitness journey and manage your membership</p>
                <div class="quick-stats">
                    <div class="stat-card">
                        <i class="fas fa-calendar-check"></i>
                        <div>
                            <span class="stat-value"><?= $membership ? date('M j', strtotime($membership['start_date'])) : 'N/A' ?></span>
                            <span class="stat-label">Member Since</span>
                        </div>
                    </div>
                    <div class="stat-card">
                        <i class="fas fa-dumbbell"></i>
                        <div>
                            <span class="stat-value"><?= $membership ? ucfirst($membership['plan_type']) : 'None' ?></span>
                            <span class="stat-label">Current Plan</span>
                        </div>
                    </div>
                    <div class="stat-card">
                        <i class="fas fa-bolt"></i>
                        <div>
                            <span class="stat-value"><?= $membership ? ucfirst($membership['status']) : 'Inactive' ?></span>
                            <span class="stat-label">Status</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="welcome-image">
                <img src="assets/images/member-dashboard.jpg" alt="Fitness Illustration">
            </div>
        </section>

        <div class="dashboard-grid">

            <section class="member-section membership-section">
                <div class="section-header">
                    <h2><i class="fas fa-id-card"></i> Your Membership</h2>
                    <?php if ($membership): ?>
                        <span class="badge <?= strtolower($membership['status']) ?>"><?= ucfirst($membership['status']) ?></span>
                    <?php endif; ?>
                </div>

                <?php if ($membership): ?>
                    <div class="membership-card">
                        <div class="plan-header">
                            <h3><?= ucfirst($membership['plan_type']) ?> Plan</h3>
                            <div class="plan-icon">
                                <?php if ($membership['plan_type'] == 'premium'): ?>
                                    <i class="fas fa-crown"></i>
                                <?php elseif ($membership['plan_type'] == 'standard'): ?>
                                    <i class="fas fa-star"></i>
                                <?php else: ?>
                                    <i class="fas fa-heart"></i>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="membership-details">
                            <div class="detail-item">
                                <i class="far fa-calendar-alt"></i>
                                <div>
                                    <span class="detail-label">Start Date</span>
                                    <span class="detail-value"><?= date('M j, Y', strtotime($membership['start_date'])) ?></span>
                                </div>
                            </div>
                            <div class="detail-item">
                                <i class="far fa-calendar-times"></i>
                                <div>
                                    <span class="detail-label">End Date</span>
                                    <span class="detail-value"><?= date('M j, Y', strtotime($membership['end_date'])) ?></span>
                                </div>
                            </div>
                            <div class="detail-item">
                                <i class="fas fa-clock"></i>
                                <div>
                                    <span class="detail-label">Remaining</span>
                                    <span class="detail-value">
                                        <?php
                                        $end = new DateTime($membership['end_date']);
                                        $now = new DateTime();
                                        $remaining = $now->diff($end);
                                        echo $remaining->format('%a days');
                                        ?>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="progress-container">
                            <div class="progress-labels">
                                <span>Start: <?= date('M j', strtotime($membership['start_date'])) ?></span>
                                <span>End: <?= date('M j', strtotime($membership['end_date'])) ?></span>
                            </div>
                            <div class="progress-bar">
                                <?php
                                $start = strtotime($membership['start_date']);
                                $end = strtotime($membership['end_date']);
                                $now = time();
                                $total = $end - $start;
                                $elapsed = $now - $start;
                                $percentage = ($elapsed / $total) * 100;
                                if ($percentage > 100) $percentage = 100;
                                if ($percentage < 0) $percentage = 0;
                                ?>
                                <div class="progress" style="width: <?= $percentage ?>%"></div>
                            </div>
                        </div>

                        <div class="membership-actions">
                            <a href="./membership.php" class="btn btn-outline">Upgrade Plan</a>
                            <a href="#" class="btn btn-primary">Renew Membership</a>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="no-membership">
                        <div class="no-membership-content">
                            <i class="fas fa-exclamation-circle"></i>
                            <h3>No Active Membership</h3>
                            <p>Join FitZone today to unlock all our premium features and facilities</p>
                            <a href="./membership.php" class="btn btn-primary">View Membership Plans</a>
                        </div>
                    </div>
                <?php endif; ?>
            </section>


            <section class="member-section">
                <h2><i class="fas fa-calendar-alt"></i> Your Appointments</h2>
                <?php if (isset($success)): ?>
                    <div class="alert success"><?= $success ?></div>
                <?php endif; ?>
                <?php if (isset($error)): ?>
                    <div class="alert error"><?= $error ?></div>
                <?php endif; ?>


                <div class="appointment-form-container">
                    <h3>Book New Appointment</h3>
                    <form method="POST">
                        <div class="form-row">
                            <div class="form-group">
                                <label for="appointment_date">Date</label>
                                <input type="date" id="appointment_date" name="appointment_date" required min="<?= date('Y-m-d') ?>">
                            </div>
                            <div class="form-group">
                                <label for="appointment_time">Time</label>
                                <input type="time" id="appointment_time" name="appointment_time" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="service_type">Service Type</label>
                            <select id="service_type" name="service_type" required>
                                <option value="">Select a service</option>
                                <option value="Personal Training">Personal Training</option>
                                <option value="Nutrition Consultation">Nutrition Consultation</option>
                                <option value="Fitness Assessment">Fitness Assessment</option>
                                <option value="Group Class">Group Class</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="notes">Notes (Optional)</label>
                            <textarea id="notes" name="notes" placeholder="Any special requests or information..."></textarea>
                        </div>
                        <button type="submit" name="book_appointment" class="btn btn-primary">
                            <i class="fas fa-calendar-plus"></i> Book Appointment
                        </button>
                    </form>
                </div>


                <div class="appointments-list">
                    <h3>Your Upcoming Appointments</h3>
                    <?php if (empty($appointments)): ?>
                        <p>You have no upcoming appointments.</p>
                    <?php else: ?>
                        <div class="appointment-items">
                            <?php foreach ($appointments as $appointment): ?>
                                <div class="appointment-item">
                                    <div class="appointment-header">
                                        <span class="appointment-date"><?= date('M j, Y', strtotime($appointment['appointment_date'])) ?></span>
                                        <span class="appointment-time"><?= date('h:i A', strtotime($appointment['appointment_time'])) ?></span>
                                        <span class="appointment-status status-<?= $appointment['status'] ?>"><?= ucfirst($appointment['status']) ?></span>
                                    </div>
                                    <div class="appointment-details">
                                        <h4><?= htmlspecialchars($appointment['service_type']) ?></h4>
                                        <?php if ($appointment['notes']): ?>
                                            <p><?= htmlspecialchars($appointment['notes']) ?></p>
                                        <?php endif; ?>
                                        <?php if ($appointment['manager_notes']): ?>
                                            <div class="manager-response">
                                                <strong>Manager Response:</strong>
                                                <p><?= htmlspecialchars($appointment['manager_notes']) ?></p>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </section>


            <section class="member-section quick-actions">
                <h2><i class="fas fa-bolt"></i> Quick Actions</h2>
                <div class="action-grid">
                    <a href="./timetable.php" class="action-card">
                        <i class="fas fa-calendar-alt"></i>
                        <span>Class Schedule</span>
                    </a>
                    <a href="./services.php" class="action-card">
                        <i class="fas fa-dumbbell"></i>
                        <span>Our Services</span>
                    </a>
                    <a href="./about.php" class="action-card">
                        <i class="fas fa-users"></i>
                        <span>Our Trainers</span>
                    </a>
                    <a href="./contact.php" class="action-card">
                        <i class="fas fa-envelope"></i>
                        <span>Contact Us</span>
                    </a>
                </div>
            </section>


            <section class="member-section">
                <h2>Contact Us</h2>
                <?php if (isset($success)): ?>
                    <div class="alert success"><?= $success ?></div>
                <?php endif; ?>
                <?php if (isset($error)): ?>
                    <div class="alert error"><?= $error ?></div>
                <?php endif; ?>

                <form method="POST">
                    <div class="form-group">
                        <input type="text" name="subject" placeholder="Subject" required>
                    </div>
                    <div class="form-group">
                        <textarea name="message" placeholder="Your message" required></textarea>
                    </div>
                    <button type="submit" name="send_message" class="btn">Send Message</button>
                </form>
            </section>


            <section class="member-section announcements">
                <h2><i class="fas fa-bullhorn"></i> Gym Announcements</h2>
                <div class="announcement-list">
                    <div class="announcement-item">
                        <div class="announcement-date">Jun 15</div>
                        <div class="announcement-content">
                            <h3>New Yoga Classes Starting Next Week</h3>
                            <p>We're excited to announce new morning yoga sessions with instructor Sarah.</p>
                        </div>
                    </div>
                    <div class="announcement-item">
                        <div class="announcement-date">Jun 10</div>
                        <div class="announcement-content">
                            <h3>Summer Pool Schedule</h3>
                            <p>The outdoor pool will be open from 6AM to 8PM daily starting June 20th.</p>
                        </div>
                    </div>
                    <div class="announcement-item">
                        <div class="announcement-date">Jun 5</div>
                        <div class="announcement-content">
                            <h3>Equipment Maintenance</h3>
                            <p>Treadmills will be unavailable this Friday from 2-4PM for scheduled maintenance.</p>
                        </div>
                    </div>
                </div>
                <a href="#" class="view-all">View All Announcements <i class="fas fa-arrow-right"></i></a>
            </section>
        </div>
    </main>

    <style>
        :root {
            --primary: #E63946;
            --primary-dark: #2a7cb9;
            --secondary-color: #ff6384;
            --accent-color: #4bc0c0;
            --dark-bg: #1a1a1a;
            --darker-bg: #121212;
            --card-bg: #252525;
            --text-light: #f5f5f5;
            --text-muted: #aaaaaa;
            --success-color: #4CAF50;
            --warning-color: #FFC107;
            --error-color: #f44336;
            --border-radius: 10px;
            --box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        }

        .member-container {
            max-width: 1200px;
            margin: 100px auto 50px;
            padding: 20px;
            color: var(--text-light);
        }

        .welcome-banner {
            display: flex;
            background: linear-gradient(135deg, var(--primary-dark), var(--primary));
            border-radius: var(--border-radius);
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: var(--box-shadow);
            position: relative;
            overflow: hidden;
        }

        .welcome-content {
            flex: 1;
            z-index: 2;
        }

        .welcome-banner h1 {
            font-size: 2.5rem;
            margin-bottom: 10px;
            font-weight: 700;
        }

        .welcome-banner h1 span {
            color: var(--accent-color);
        }

        .welcome-message {
            font-size: 1.1rem;
            margin-bottom: 25px;
            opacity: 0.9;
        }

        .welcome-image {
            width: 300px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .welcome-image img {
            max-width: 100%;
            height: auto;
        }

        .quick-stats {
            display: flex;
            gap: 15px;
            margin-top: 20px;
        }

        .stat-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(5px);
            border-radius: 8px;
            padding: 15px;
            display: flex;
            align-items: center;
            gap: 15px;
            flex: 1;
            min-width: 0;
            transition: transform 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
        }

        .stat-card i {
            font-size: 1.8rem;
            color: var(--accent-color);
        }

        .stat-value {
            display: block;
            font-size: 1.2rem;
            font-weight: 600;
        }

        .stat-label {
            display: block;
            font-size: 0.9rem;
            opacity: 0.8;
        }

        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 25px;
        }

        .member-section {
            background: var(--card-bg);
            padding: 25px;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
        }

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .section-header h2 {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 1.4rem;
        }

        .badge {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
        }

        .badge.active {
            background: var(--success-color);
            color: white;
        }

        .badge.expired {
            background: var(--error-color);
            color: white;
        }

        .badge.pending {
            background: var(--warning-color);
            color: #333;
        }

        .membership-card {
            background: var(--darker-bg);
            padding: 20px;
            border-radius: 8px;
            border-left: 4px solid var(--primary);
        }

        .plan-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .plan-header h3 {
            font-size: 1.3rem;
            margin: 0;
        }

        .plan-icon {
            width: 40px;
            height: 40px;
            background: rgba(54, 162, 235, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary);
            font-size: 1.2rem;
        }

        .membership-details {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 15px;
            margin-bottom: 25px;
        }

        .detail-item {
            display: flex;
            gap: 10px;
            align-items: center;
        }

        .detail-item i {
            color: var(--primary);
            font-size: 1.2rem;
        }

        .detail-label {
            display: block;
            font-size: 0.8rem;
            opacity: 0.7;
        }

        .detail-value {
            display: block;
            font-size: 1rem;
            font-weight: 500;
        }

        .progress-container {
            margin: 25px 0;
        }

        .progress-labels {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
            font-size: 0.8rem;
            opacity: 0.8;
        }

        .progress-bar {
            height: 8px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 4px;
            overflow: hidden;
        }

        .progress {
            height: 100%;
            background: linear-gradient(90deg, var(--primary), var(--accent-color));
            border-radius: 4px;
            transition: width 0.5s ease;
        }

        .membership-actions {
            display: flex;
            gap: 10px;
            margin-top: 20px;
        }

        .no-membership {
            text-align: center;
            padding: 30px 20px;
            background: var(--darker-bg);
            border-radius: 8px;
            border: 1px dashed rgba(255, 255, 255, 0.1);
        }

        .no-membership-content {
            max-width: 300px;
            margin: 0 auto;
        }

        .no-membership i {
            font-size: 2.5rem;
            color: var(--secondary-color);
            margin-bottom: 15px;
        }

        .no-membership h3 {
            margin: 10px 0;
        }

        .no-membership p {
            margin-bottom: 20px;
            opacity: 0.8;
        }


        .quick-actions .action-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
        }

        .action-card {
            background: var(--darker-bg);
            border-radius: 8px;
            padding: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            transition: all 0.3s ease;
            color: var(--text-light);
            text-decoration: none;
            min-height: 100px;
        }

        .action-card:hover {
            transform: translateY(-5px);
            background: var(--primary-dark);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
        }

        .action-card i {
            font-size: 1.8rem;
            margin-bottom: 10px;
            color: var(--accent-color);
        }

        .action-card span {
            font-size: 0.95rem;
            font-weight: 500;
        }


        .contact-form {
            margin-top: 20px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-size: 0.9rem;
            font-weight: 500;
        }

        .form-group input,
        .form-group textarea,
        .form-group select {
            width: 100%;
            padding: 12px 15px;
            border-radius: 6px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            background: var(--darker-bg);
            color: var(--text-light);
            font-family: 'Montserrat', sans-serif;
            transition: border 0.3s ease;
        }

        .form-group input:focus,
        .form-group textarea:focus,
        .form-group select:focus {
            outline: none;
            border-color: var(--primary);
        }

        .form-group textarea {
            min-height: 150px;
            resize: vertical;
        }

        .form-row {
            display: flex;
            gap: 15px;
        }

        .form-row .form-group {
            flex: 1;
        }


        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 12px 24px;
            border-radius: 6px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            border: none;
            font-family: 'Montserrat', sans-serif;
            gap: 8px;
        }

        .btn-primary {
            background: var(--primary);
            color: white;
        }

        .btn-primary:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
        }

        .btn-outline {
            background: transparent;
            color: var(--primary);
            border: 1px solid var(--primary);
        }

        .btn-outline:hover {
            background: rgba(54, 162, 235, 0.1);
        }

        .btn-block {
            width: 100%;
        }


        .alert {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 6px;
            font-size: 0.95rem;
        }

        .alert i {
            font-size: 1.3rem;
        }

        .alert.success {
            background: rgba(76, 175, 80, 0.2);
            border-left: 4px solid var(--success-color);
            color: #a5d6a7;
        }

        .alert.error {
            background: rgba(244, 67, 54, 0.2);
            border-left: 4px solid var(--error-color);
            color: #ef9a9a;
        }


        .announcement-list {
            margin-top: 15px;
        }

        .announcement-item {
            display: flex;
            gap: 15px;
            padding: 15px 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }

        .announcement-item:last-child {
            border-bottom: none;
        }

        .announcement-date {
            width: 50px;
            height: 50px;
            background: var(--primary-dark);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            flex-shrink: 0;
        }

        .announcement-content h3 {
            margin: 0 0 5px 0;
            font-size: 1rem;
        }

        .announcement-content p {
            margin: 0;
            font-size: 0.9rem;
            opacity: 0.8;
        }

        .view-all {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            margin-top: 15px;
            color: var(--primary);
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 500;
        }

        .view-all:hover {
            text-decoration: underline;
        }


        .appointment-form-container {
            background: var(--darker-bg);
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 30px;
        }

        .appointment-form-container h3 {
            margin-top: 0;
            margin-bottom: 20px;
            color: var(--accent-color);
        }

        .appointments-list h3 {
            margin-bottom: 15px;
        }

        .appointment-items {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .appointment-item {
            background: var(--darker-bg);
            padding: 15px;
            border-radius: 8px;
            border-left: 3px solid var(--primary);
        }

        .appointment-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }

        .appointment-date,
        .appointment-time {
            font-weight: 500;
        }

        .appointment-status {
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .status-pending {
            background: rgba(255, 152, 0, 0.2);
            color: #FFC107;
        }

        .status-confirmed {
            background: rgba(76, 175, 80, 0.2);
            color: #A5D6A7;
        }

        .status-cancelled {
            background: rgba(244, 67, 54, 0.2);
            color: #EF9A9A;
        }

        .appointment-details h4 {
            margin: 0 0 5px 0;
            color: var(--accent-color);
        }

        .appointment-details p {
            margin: 0;
            font-size: 0.9rem;
            opacity: 0.9;
        }

        .manager-response {
            margin-top: 10px;
            padding-top: 10px;
            border-top: 1px dashed rgba(255, 255, 255, 0.1);
        }

        .manager-response strong {
            color: var(--primary);
        }


        @media (max-width: 992px) {
            .welcome-banner {
                flex-direction: column;
            }

            .welcome-image {
                width: 100%;
                margin-top: 20px;
            }

            .quick-stats {
                flex-wrap: wrap;
            }

            .form-row {
                flex-direction: column;
                gap: 0;
            }
        }

        @media (max-width: 768px) {
            .dashboard-grid {
                grid-template-columns: 1fr;
            }

            .quick-actions .action-grid {
                grid-template-columns: 1fr;
            }

            .membership-details {
                grid-template-columns: 1fr;
            }
        }
    </style>

    <script src="./js/script.js"></script>
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