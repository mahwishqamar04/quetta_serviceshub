<?php
/**
 * Database configuration for the Quetta Services Hub application.
 * Update these values if your local MySQL setup differs.
 */

// ==================== Database Connection ====================
// This section connects the website to the MySQL database used by the app.
$host = 'localhost';
$port = 3307;
$dbname = 'quettaserviceshub_db';
$username = 'root';
$password = '';

$conn = new mysqli($host, $username, $password, $dbname, $port);

if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}
?>
