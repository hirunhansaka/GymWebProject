<?php
require_once 'config.php';


$pages = [
    'index.php' => 'Home - FitZone',
    'about.php' => 'About Us - FitZone',
    'services.php' => 'Our Services - FitZone',
    'timetable.php' => 'Class Timetable - FitZone',
    'membership.php' => 'Membership Plans - FitZone',
    'contact.php' => 'Contact Us - FitZone',
    'blog.php' => 'FitZone Blog',
    'login.php' => 'Login - FitZone',
    'register.php' => 'Register - FitZone'
];

foreach ($pages as $page => $title) {
   
    $content = file_get_contents($page);
    
    
    $content = strip_tags($content);
    $content = preg_replace('/\s+/', ' ', $content);
    $content = trim($content);
    
    
    $stmt = $conn->prepare("
        INSERT INTO search_index (page_url, page_title, content) 
        VALUES (?, ?, ?)
        ON DUPLICATE KEY UPDATE content = VALUES(content)
    ");
    $stmt->bind_param("sss", $page, $title, $content);
    $stmt->execute();
    
    echo "Indexed: $page\n";
}

echo "Content indexing complete!\n";
?>