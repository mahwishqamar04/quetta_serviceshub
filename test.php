<?php
// This is a simple test file used to confirm the database connection works.
// It connects to MySQL using the settings from config.php.
include 'config.php';

if ($conn) {
    echo "Database Connected Successfully";
} else {
    echo "Connection Failed";
}
?>