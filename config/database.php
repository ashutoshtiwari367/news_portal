<?php

/**
 * NEWS PORTAL - Database Configuration
 * Automatically detects Local (XAMPP) vs Live Server (Hostinger/cPanel)
 */

// Detect Environment
$httpHost   = $_SERVER['HTTP_HOST'] ?? 'localhost';
$serverName = $_SERVER['SERVER_NAME'] ?? 'localhost';

$isLocal = (
    strpos($httpHost, 'localhost') !== false ||
    strpos($httpHost, '127.0.0.1') !== false ||
    $serverName === 'localhost' ||
    $serverName === '127.0.0.1'
);

if ($isLocal) {
    // ----------------------------------------------------
    // LOCAL ENVIRONMENT (XAMPP)
    // ----------------------------------------------------
    $host     = "localhost";
    $username = "root";
    $password = "";
    $database = "news_portal";

    // Try port 3306 first, fallback to 3307 for custom XAMPP port
    $conn = @new mysqli($host, $username, $password, $database);
    if ($conn->connect_error) {
        $conn = @new mysqli($host, $username, $password, $database, 3307);
    }
} else {
    // ----------------------------------------------------
    // LIVE SERVER ENVIRONMENT (Hostinger / cPanel)
    // ----------------------------------------------------
    $host     = "localhost";
    $username = "u447123054_news_portal";
    $password = "News_portal1";
    $database = "u447123054_news_portal";

    $conn = @new mysqli($host, $username, $password, $database);
}

// ----------------------------------------------------
// SMART FALLBACK (If auto-detection fails to connect)
// ----------------------------------------------------
if ($conn->connect_error) {
    if ($isLocal) {
        // Fallback to Live Server credentials
        $conn = @new mysqli("localhost", "u447123054_news_portal", "news_portal", "u447123054_news_portal");
    } else {
        // Fallback to Local credentials
        $conn = @new mysqli("localhost", "root", "", "news_portal");
        if ($conn->connect_error) {
            $conn = @new mysqli("localhost", "root", "", "news_portal", 3307);
        }
    }
}

// Final Error Check
if ($conn->connect_error) {
    die("Database Connection Failed: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");

?>