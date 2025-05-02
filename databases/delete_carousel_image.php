<?php
header('Content-Type: application/json');

// Validate input from POST (not JSON anymore)
$imageId = $_POST['image_id'] ?? null;

if (!isset($imageId) || !is_numeric($imageId)) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Invalid or missing image ID.'
    ]);
    exit;
}

$imageId = (int)$imageId;

// Database connection
require 'db_connection.php';

// Prepare the DELETE statement
$stmt = $conn->prepare("DELETE FROM homepage_carousel WHERE id = ?");
if (!$stmt) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Failed to prepare SQL statement.'
    ]);
    $conn->close();
    exit;
}

$stmt->bind_param("i", $imageId);

// Execute the statement
if ($stmt->execute()) {
    echo json_encode([
        'status' => 'success',
        'message' => 'Image deleted successfully.'
    ]);
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Failed to delete image from the database.'
    ]);
}

$stmt->close();
$conn->close();
?>
