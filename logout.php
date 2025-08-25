<?php
require_once 'auth_functions.php';
logout();
header("Location: index.php?logged_out=1");
exit();
?>