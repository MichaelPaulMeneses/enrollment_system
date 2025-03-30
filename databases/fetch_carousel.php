<?php
require 'db_connection.php';

header('Content-Type: application/json');

$baseUrl = "http://localhost/enrollment_system/";

$response = ["status" => "error", "message" => "Failed to fetch images"];

$sql = "SELECT id, image_path FROM carousel_images ORDER BY id DESC";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $images = [];
    while ($row = $result->fetch_assoc()) {
        $images[] = [
            "id" => $row["id"],
            "url" => $baseUrl . basename($row["image_path"])
        ];
    }
    $response = ["status" => "success", "images" => $images];
} else {
    $response = ["status" => "success", "images" => []]; // No images found
}

echo json_encode($response);
$conn->close();
?>
