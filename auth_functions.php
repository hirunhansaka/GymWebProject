<?php
require_once 'config.php';

function registerUser($username, $email, $password, $first_name, $last_name, $phone, $user_type = 'member')
{
    global $conn;

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $conn->prepare("INSERT INTO users (username, email, password, first_name, last_name, phone, user_type) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssss", $username, $email, $hashed_password, $first_name, $last_name, $phone, $user_type);

    return $stmt->execute();
}

function loginUser($username, $password)
{
    global $conn;

    $stmt = $conn->prepare("SELECT id, username, password, user_type FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['user_type'] = $user['user_type'];
            return true;
        }
    }
    return false;
}

function isLoggedIn()
{
    return isset($_SESSION['user_id']);
}

function isAdmin()
{
    return isLoggedIn() && $_SESSION['user_type'] === 'admin';
}

function isManager()
{
    return isLoggedIn() && $_SESSION['user_type'] === 'manager';
}

function isMember()
{
    return isLoggedIn() && $_SESSION['user_type'] === 'member';
}

function logout()
{
    session_unset();
    session_destroy();
}

function getUserById($id)
{
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}
