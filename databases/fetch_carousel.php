<?php
header('Content-Type: application/json');

// Database connection
require 'db_connection.php';

$mysqli = new mysqli($host, $username, $password, $database);

if ($mysqli->connect_errno) {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to connect to MySQL: ' . $mysqli->connect_error]);
    exit;
}

// Query to get carousel images
$sql = "SELECT id, image_path FROM homepage_carousel ORDER BY id ASC";
$result = $mysqli->query($sql);

if (!$result) {
    http_response_code(500);
    echo json_encode(['error' => 'Query failed: ' . $mysqli->error]);
    $mysqli->close();
    exit;
}

$images = [];
while ($row = $result->fetch_assoc()) {
    $images[] = [
        'id' => (int)$row['id'],
        'image_path' => htmlspecialchars($row['image_path'], ENT_QUOTES, 'UTF-8')
    ];
}

$result->free();
$mysqli->close();

echo json_encode($images);
?>
