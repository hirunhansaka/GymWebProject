<?php
require_once 'auth_functions.php';

if (!isManager()) {
    header("Location: login.php");
    exit();
}


$messages = [];
$result = $conn->query("SELECT m.*, u.username, u.email FROM messages m JOIN users u ON m.user_id = u.id ORDER BY m.created_at DESC");
if ($result) {
    $messages = $result->fetch_all(MYSQLI_ASSOC);
}


$members = [];
$result = $conn->query("SELECT * FROM users WHERE user_type = 'member' ORDER BY created_at DESC");
if ($result) {
    $members = $result->fetch_all(MYSQLI_ASSOC);
}


$appointments = [];
$result = $conn->query("SELECT a.*, u.username, u.email FROM appointments a JOIN users u ON a.user_id = u.id ORDER BY a.appointment_date DESC, a.appointment_time DESC");
if ($result) {
    $appointments = $result->fetch_all(MYSQLI_ASSOC);
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reply_message'])) {
    $message_id = $_POST['message_id'];
    $reply = $_POST['reply'];
    $manager_id = $_SESSION['user_id'];

    $stmt = $conn->prepare("UPDATE messages SET reply = ?, replied_by = ?, replied_at = NOW() WHERE id = ?");
    $stmt->bind_param("sii", $reply, $manager_id, $message_id);

    if ($stmt->execute()) {
        $_SESSION['success'] = "Reply sent successfully!";
        header("Location: manager_dashboard.php");
        exit();
    } else {
        $error = "Error sending reply. Please try again.";
    }
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_appointment'])) {
    $appointment_id = $_POST['appointment_id'];
    $status = $_POST['status'];
    $manager_notes = $_POST['manager_notes'];

    $stmt = $conn->prepare("UPDATE appointments SET status = ?, manager_notes = ? WHERE id = ?");
    $stmt->bind_param("ssi", $status, $manager_notes, $appointment_id);

    if ($stmt->execute()) {
        $_SESSION['success'] = "Appointment updated successfully!";
        header("Location: manager_dashboard.php");
        exit();
    } else {
        $error = "Error updating appointment. Please try again.";
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
    <title>Manager Dashboard - FitZone</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="./css/style.css">
    <style>
        :root {
            --primary: #E63946;
            --dark: #1D1E22;
            --light: #F5F5F5;
            --gray: #2D2E33;
            --success: #4CAF50;
            --error: #F44336;
            --warning: #FF9800;
        }

        .manager-container {
            max-width: 1400px;
            margin: 100px auto 50px;
            padding: 20px;
            color: var(--light);
        }

        .manager-section {
            background: var(--gray);
            padding: 30px;
            border-radius: 8px;
            margin-bottom: 30px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        h1 {
            color: var(--primary);
            margin-bottom: 30px;
            font-size: 2.5rem;
            display: flex;
            align-items: center;
            gap: 15px;
        }

        h2 {
            color: var(--light);
            margin-bottom: 20px;
            font-size: 1.8rem;
            border-bottom: 2px solid var(--primary);
            padding-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .messages-list,
        .appointments-list {
            display: flex;
            flex-direction: column;
            gap: 25px;
        }

        .message-card,
        .appointment-card {
            background: var(--dark);
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }

        .message-card:hover,
        .appointment-card:hover {
            transform: translateY(-3px);
        }

        .message-header,
        .appointment-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
            font-size: 14px;
            color: #aaa;
        }

        .message-subject,
        .appointment-service {
            font-weight: 600;
            margin-bottom: 15px;
            font-size: 1.1rem;
            color: var(--primary);
        }

        .message-content,
        .appointment-notes {
            margin-bottom: 20px;
            white-space: pre-line;
            line-height: 1.6;
            padding: 15px;
            background: rgba(255, 255, 255, 0.03);
            border-radius: 6px;
        }

        .message-reply,
        .appointment-response {
            background: rgba(75, 192, 192, 0.1);
            border-left: 3px solid var(--primary);
            padding: 15px;
            margin-top: 20px;
            border-radius: 0 6px 6px 0;
        }

        .message-reply strong,
        .appointment-response strong {
            color: var(--primary);
        }

        .reply-date,
        .appointment-date {
            font-size: 13px;
            color: #aaa;
            text-align: right;
            margin-top: 10px;
            font-style: italic;
        }

        .reply-form textarea,
        .appointment-form textarea {
            width: 100%;
            padding: 15px;
            border-radius: 6px;
            border: 1px solid #444;
            background: var(--gray);
            color: white;
            margin-top: 15px;
            min-height: 120px;
            font-family: 'Montserrat', sans-serif;
            resize: vertical;
            transition: all 0.3s;
        }

        .reply-form textarea:focus,
        .appointment-form textarea:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 2px rgba(75, 192, 192, 0.2);
        }

        .btn {
            background: var(--primary);
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 600;
            font-size: 1rem;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-top: 15px;
        }

        .btn:hover {
            background: #3aa8a8;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .members-table,
        .appointments-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .members-table th,
        .members-table td,
        .appointments-table th,
        .appointments-table td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #444;
        }

        .members-table th,
        .appointments-table th {
            background: var(--primary);
            color: white;
            font-weight: 600;
        }

        .members-table tr:hover,
        .appointments-table tr:hover {
            background: rgba(255, 255, 255, 0.05);
        }

        .members-table tr:nth-child(even),
        .appointments-table tr:nth-child(even) {
            background: rgba(255, 255, 255, 0.02);
        }

        .members-table tr:nth-child(even):hover,
        .appointments-table tr:nth-child(even):hover {
            background: rgba(255, 255, 255, 0.07);
        }

        .alert {
            padding: 15px 20px;
            margin-bottom: 25px;
            border-radius: 6px;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .alert.success {
            background: rgba(76, 175, 80, 0.2);
            color: #A5D6A7;
            border-left: 4px solid var(--success);
        }

        .alert.error {
            background: rgba(244, 67, 54, 0.2);
            color: #EF9A9A;
            border-left: 4px solid var(--error);
        }

        .card-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: var(--gray);
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s;
        }

        .stat-card:hover {
            transform: translateY(-5px);
        }

        .stat-card h3 {
            color: var(--primary);
            margin-top: 0;
            font-size: 1.2rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .stat-card p {
            font-size: 2rem;
            margin: 10px 0 0;
            font-weight: 600;
        }

        .status-badge {
            padding: 5px 10px;
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

        .appointment-details {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 15px;
        }

        .appointment-detail {
            background: rgba(255, 255, 255, 0.05);
            padding: 10px;
            border-radius: 6px;
        }

        .appointment-detail-label {
            font-size: 0.8rem;
            opacity: 0.7;
            display: block;
        }

        .appointment-detail-value {
            font-weight: 500;
            display: block;
        }

        select {
            padding: 10px;
            border-radius: 6px;
            background: var(--gray);
            color: white;
            border: 1px solid #444;
            width: 100%;
            margin-top: 10px;
        }
    </style>
</head>

<body>

    <header>
        <?php require_once 'manager_navbar.php'; ?>
    </header>

    <main class="manager-container">
        <h1><i class="fas fa-tachometer-alt"></i> Manager Dashboard</h1>


        <div class="card-container">
            <div class="stat-card">
                <h3><i class="fas fa-users"></i> Total Members</h3>
                <p><?= count($members) ?></p>
            </div>
            <div class="stat-card">
                <h3><i class="fas fa-envelope"></i> Unread Messages</h3>
                <p><?= count(array_filter($messages, fn($msg) => empty($msg['reply']))) ?></p>
            </div>
            <div class="stat-card">
                <h3><i class="fas fa-calendar-check"></i> Pending Appointments</h3>
                <p><?= count(array_filter($appointments, fn($app) => $app['status'] == 'pending')) ?></p>
            </div>
        </div>


        <section class="manager-section">
            <h2><i class="fas fa-calendar-alt"></i> Member Appointments</h2>
            <?php if (isset($success)): ?>
                <div class="alert success">
                    <i class="fas fa-check-circle"></i> <?= $success ?>
                </div>
            <?php endif; ?>
            <?php if (isset($error)): ?>
                <div class="alert error">
                    <i class="fas fa-exclamation-circle"></i> <?= $error ?>
                </div>
            <?php endif; ?>

            <div class="appointments-list">
                <?php foreach ($appointments as $appointment): ?>
                    <div class="appointment-card">
                        <div class="appointment-header">
                            <span><i class="fas fa-user"></i> <strong>From:</strong> <?= htmlspecialchars($appointment['username']) ?> (<?= htmlspecialchars($appointment['email']) ?>)</span>
                            <span class="status-badge status-<?= $appointment['status'] ?>"><?= ucfirst($appointment['status']) ?></span>
                        </div>

                        <div class="appointment-details">
                            <div class="appointment-detail">
                                <span class="appointment-detail-label">Date</span>
                                <span class="appointment-detail-value"><?= date('M j, Y', strtotime($appointment['appointment_date'])) ?></span>
                            </div>
                            <div class="appointment-detail">
                                <span class="appointment-detail-label">Time</span>
                                <span class="appointment-detail-value"><?= date('h:i A', strtotime($appointment['appointment_time'])) ?></span>
                            </div>
                            <div class="appointment-detail">
                                <span class="appointment-detail-label">Service</span>
                                <span class="appointment-detail-value"><?= htmlspecialchars($appointment['service_type']) ?></span>
                            </div>
                        </div>

                        <?php if ($appointment['notes']): ?>
                            <div class="appointment-notes">
                                <strong>Member Notes:</strong><br>
                                <?= nl2br(htmlspecialchars($appointment['notes'])) ?>
                            </div>
                        <?php endif; ?>

                        <?php if ($appointment['manager_notes'] || $appointment['status'] != 'pending'): ?>
                            <div class="appointment-response">
                                <strong>Your Response:</strong>
                                <div class="response-content">
                                    <?= $appointment['manager_notes'] ? nl2br(htmlspecialchars($appointment['manager_notes'])) : 'No additional notes provided.' ?>
                                </div>
                                <div class="appointment-date">
                                    <i class="far fa-clock"></i> Last updated on <?= date('M j, Y H:i', strtotime($appointment['updated_at'])) ?>
                                </div>
                            </div>
                        <?php endif; ?>

                        <form method="POST" class="appointment-form">
                            <input type="hidden" name="appointment_id" value="<?= $appointment['id'] ?>">

                            <label for="status">Update Status:</label>
                            <select name="status" id="status" required>
                                <option value="pending" <?= $appointment['status'] == 'pending' ? 'selected' : '' ?>>Pending</option>
                                <option value="confirmed" <?= $appointment['status'] == 'confirmed' ? 'selected' : '' ?>>Confirmed</option>
                                <option value="cancelled" <?= $appointment['status'] == 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
                            </select>

                            <textarea name="manager_notes" placeholder="Add any notes for the member..."><?= htmlspecialchars($appointment['manager_notes'] ?? '') ?></textarea>

                            <button type="submit" name="update_appointment" class="btn">
                                <i class="fas fa-save"></i> Update Appointment
                            </button>
                        </form>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>


        <section class="manager-section">
            <h2><i class="fas fa-envelope-open-text"></i> Member Messages</h2>

            <div class="messages-list">
                <?php foreach ($messages as $message): ?>
                    <div class="message-card">
                        <div class="message-header">
                            <span><i class="fas fa-user"></i> <strong>From:</strong> <?= htmlspecialchars($message['username']) ?> (<?= htmlspecialchars($message['email']) ?>)</span>
                            <span><i class="far fa-clock"></i> <?= date('M j, Y H:i', strtotime($message['created_at'])) ?></span>
                        </div>
                        <div class="message-subject">
                            <i class="fas fa-tag"></i> <?= htmlspecialchars($message['subject']) ?>
                        </div>
                        <div class="message-content">
                            <?= nl2br(htmlspecialchars($message['message'])) ?>
                        </div>

                        <?php if ($message['reply']): ?>
                            <div class="message-reply">
                                <strong><i class="fas fa-reply"></i> Your Reply:</strong>
                                <div class="reply-content">
                                    <?= nl2br(htmlspecialchars($message['reply'])) ?>
                                </div>
                                <div class="reply-date">
                                    <i class="far fa-clock"></i> Replied on <?= date('M j, Y H:i', strtotime($message['replied_at'])) ?>
                                </div>
                            </div>
                        <?php else: ?>
                            <form method="POST" class="reply-form">
                                <input type="hidden" name="message_id" value="<?= $message['id'] ?>">
                                <textarea name="reply" placeholder="Write your reply..." required></textarea>
                                <button type="submit" name="reply_message" class="btn">
                                    <i class="fas fa-paper-plane"></i> Send Reply
                                </button>
                            </form>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>


        <section class="manager-section">
            <h2><i class="fas fa-users"></i> All Members</h2>
            <table class="members-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Name</th>
                        <th>Phone</th>
                        <th>Joined</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($members as $member): ?>
                        <tr>
                            <td><?= $member['id'] ?></td>
                            <td><?= htmlspecialchars($member['username']) ?></td>
                            <td><?= htmlspecialchars($member['email']) ?></td>
                            <td><?= htmlspecialchars($member['first_name'] . ' ' . $member['last_name']) ?></td>
                            <td><?= htmlspecialchars($member['phone']) ?></td>
                            <td><?= date('M j, Y', strtotime($member['created_at'])) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </section>
    </main>

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