<?php
// Define database configuration
$servername = "localhost"; // Typically 'localhost' for XAMPP
$username = "root"; // Default XAMPP username
$password = ""; // XAMPP default password is an empty string
$dbname = "ecommerce_db"; // Replace with your actual database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
