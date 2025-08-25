<?php
require_once 'auth_functions.php';

if (!isLoggedIn()) {
    header("Location: login.php");
    exit();
}


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
?>