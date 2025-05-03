<?php
require 'db_connection.php';

$folder_id = isset($_GET['folder_id']) ? intval($_GET['folder_id']) : 0;

$sql = "SELECT image_path FROM images WHERE folder_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $folder_id);
$stmt->execute();
$result = $stmt->get_result();

$images = [];
while ($row = $result->fetch_assoc()) {
    $images[] = $row['image_path']; // e.g., "assets/uploads/gallery/filename.jpg"
}

if (count($images) > 0) {
    echo json_encode(["status" => "success", "images" => $images]);
} else {
    echo json_encode(["status" => "error", "message" => "No images found."]);
}
?>
