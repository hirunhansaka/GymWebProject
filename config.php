<?php

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'fitzone_db');


$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


$conn->set_charset("utf8");


session_start();


$conn->query("
    CREATE TABLE IF NOT EXISTS search_index (
        id INT AUTO_INCREMENT PRIMARY KEY,
        page_url VARCHAR(255) NOT NULL,
        page_title VARCHAR(255) NOT NULL,
        content TEXT NOT NULL,
        FULLTEXT (content)
    ) ENGINE=InnoDB;
");
