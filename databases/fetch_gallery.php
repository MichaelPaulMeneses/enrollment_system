<?php
require 'db_connection.php'; // Include database connection

header('Content-Type: application/json');

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

$response = ["status" => "error", "message" => "No images found", "images" => []];

$result = $conn->query("SELECT image_path FROM homepage_gallery");

if ($result && $result->num_rows > 0) {
    $images = [];
    while ($row = $result->fetch_assoc()) {
        $images[] = $row["image_path"]; // Ensure this contains the correct image URL or path
    }
    $response = ["status" => "success", "images" => $images];
}

echo json_encode($response);
?>
