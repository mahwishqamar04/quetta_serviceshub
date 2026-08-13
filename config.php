<?php
/**
 * Database configuration for the Quetta Services Hub application.
 * Update these values if your local MySQL setup differs.
 */

// ==================== Database Connection ====================
// This file stores the database settings used by all pages.
// The website uses these values to connect to MySQL and fetch or save data.

// Database server name. Usually localhost for a local XAMPP setup.
$host = 'localhost';

// MySQL port number. XAMPP often uses port 3307 on local machines.
$port = 3307;

// Name of the database that contains the project tables.
$dbname = 'quettaserviceshub_db';

// Database username used to connect to MySQL.
$username = 'root';

// Password for the database user. In local development, this is often empty.
$password = '';

// Create a new MySQL connection object using the values above.
$conn = new mysqli($host, $username, $password, $dbname, $port);

// If the connection fails, stop the page and show the error message.
// This helps developers know the database is not reachable.
if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}
?>
