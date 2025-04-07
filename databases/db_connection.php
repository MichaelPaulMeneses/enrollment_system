<?php
$host = "localhost"; // Change if using a remote database
$username = "root"; // Default for XAMPP
$password = ""; // Default is empty for XAMPP
$database = "enrollment_database_test"; // Your database name

// Create a connection
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Optional: Uncomment for debugging
// echo "Database connected successfully!";
?>
