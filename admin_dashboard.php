<?php
require_once 'auth_functions.php';

if (!isAdmin()) {
    header("Location: login.php");
    exit();
}


$users = [];
$result = $conn->query("SELECT * FROM users ORDER BY user_type, created_at DESC");
if ($result) {
    $users = $result->fetch_all(MYSQLI_ASSOC);
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_manager'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $phone = $_POST['phone'];


    $check = $conn->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
    $check->bind_param("ss", $username, $email);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        $error = "Username or email already exists!";
    } else {
        if (registerUser($username, $email, $password, $first_name, $last_name, $phone, 'manager')) {
            $_SESSION['success'] = "Manager added successfully!";
            header("Location: admin_dashboard.php");
            exit();
        } else {
            $error = "Error adding manager. Please try again.";
        }
    }
    $check->close();
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_user'])) {
    $user_id = $_POST['user_id'];

    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);

    if ($stmt->execute()) {
        $_SESSION['success'] = "User deleted successfully!";
        header("Location: admin_dashboard.php");
        exit();
    } else {
        $error = "Error deleting user. Please try again.";
    }
    $stmt->close();
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
    <title>Admin Dashboard - FitZone</title>
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

        .admin-container {
            max-width: 1400px;
            margin: 100px auto 50px;
            padding: 20px;
            color: var(--light);
        }

        .admin-section {
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
        }

        h2 {
            color: var(--light);
            margin-bottom: 20px;
            font-size: 1.8rem;
            border-bottom: 2px solid var(--primary);
            padding-bottom: 10px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group input {
            width: 100%;
            padding: 12px 15px;
            border-radius: 6px;
            border: 1px solid #444;
            background: var(--dark);
            color: white;
            font-size: 1rem;
            transition: all 0.3s;
        }

        .form-group input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 2px rgba(230, 57, 70, 0.2);
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
            display: inline-block;
        }

        .btn:hover {
            background: #C1121F;
            transform: translateY(-2px);
        }

        .btn-danger {
            background: var(--error);
        }

        .btn-danger:hover {
            background: #C62828;
        }

        .users-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .users-table th,
        .users-table td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #444;
        }

        .users-table th {
            background: var(--primary);
            color: white;
            font-weight: 600;
        }

        .users-table tr:hover {
            background: rgba(255, 255, 255, 0.05);
        }

        .users-table tr:nth-child(even) {
            background: rgba(255, 255, 255, 0.02);
        }

        .users-table tr:nth-child(even):hover {
            background: rgba(255, 255, 255, 0.07);
        }

        .alert {
            padding: 15px 20px;
            margin-bottom: 25px;
            border-radius: 6px;
            font-weight: 500;
            display: flex;
            align-items: center;
        }

        .alert i {
            margin-right: 10px;
            font-size: 1.2rem;
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

        .action-btns {
            display: flex;
            gap: 10px;
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
        }

        .stat-card h3 {
            color: var(--primary);
            margin-top: 0;
            font-size: 1.2rem;
        }

        .stat-card p {
            font-size: 2rem;
            margin: 10px 0 0;
            font-weight: 600;
        }
    </style>
</head>

<body>

    <header>
        <?php require_once 'admin_navbar.php'; ?>
    </header>
    <main class="admin-container">
        <h1><i class="fas fa-tachometer-alt"></i> Admin Dashboard</h1>


        <div class="card-container">
            <div class="stat-card">
                <h3>Total Users</h3>
                <p><?= count($users) ?></p>
            </div>
            <div class="stat-card">
                <h3>Managers</h3>
                <p><?= count(array_filter($users, fn($user) => $user['user_type'] === 'manager')) ?></p>
            </div>
            <div class="stat-card">
                <h3>Members</h3>
                <p><?= count(array_filter($users, fn($user) => $user['user_type'] === 'member')) ?></p>
            </div>
        </div>


        <section class="admin-section">
            <h2><i class="fas fa-user-plus"></i> Add New Manager</h2>
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

            <form method="POST">
                <div class="form-group">
                    <input type="text" name="username" placeholder="Username" required>
                </div>
                <div class="form-group">
                    <input type="email" name="email" placeholder="Email" required>
                </div>
                <div class="form-group">
                    <input type="password" name="password" placeholder="Password" required>
                </div>
                <div class="form-group">
                    <input type="text" name="first_name" placeholder="First Name" required>
                </div>
                <div class="form-group">
                    <input type="text" name="last_name" placeholder="Last Name" required>
                </div>
                <div class="form-group">
                    <input type="text" name="phone" placeholder="Phone">
                </div>
                <button type="submit" name="add_manager" class="btn">
                    <i class="fas fa-save"></i> Add Manager
                </button>
            </form>
        </section>


        <section class="admin-section">
            <h2><i class="fas fa-users"></i> All Users</h2>
            <table class="users-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Name</th>
                        <th>Phone</th>
                        <th>Type</th>
                        <th>Joined</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                        <?php if ($user['user_type'] != 'admin'): ?>
                            <tr>
                                <td><?= $user['id'] ?></td>
                                <td><?= htmlspecialchars($user['username']) ?></td>
                                <td><?= htmlspecialchars($user['email']) ?></td>
                                <td><?= htmlspecialchars($user['first_name'] . ' ' . $user['last_name']) ?></td>
                                <td><?= htmlspecialchars($user['phone']) ?></td>
                                <td><?= ucfirst($user['user_type']) ?></td>
                                <td><?= date('M j, Y', strtotime($user['created_at'])) ?></td>
                                <td class="action-btns">
                                    <form method="POST" onsubmit="return confirm('Are you sure you want to delete this user?');">
                                        <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                                        <button type="submit" name="delete_user" class="btn btn-danger">
                                            <i class="fas fa-trash-alt"></i> Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endif; ?>
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