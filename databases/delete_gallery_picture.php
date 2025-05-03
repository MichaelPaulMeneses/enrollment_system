<?php
include 'db_connection.php';

header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $imageId = intval($_POST['image_id'] ?? 0);

    if ($imageId > 0) {
        // Get image path from 'images' table
        $stmt = $conn->prepare("SELECT image_path FROM images WHERE image_id = ?");
        $stmt->bind_param("i", $imageId);
        $stmt->execute();
        $result = $stmt->get_result();
        $image = $result->fetch_assoc();
        $stmt->close();

        if ($image) {
            $imagePath = $image['image_path'];

            // Delete the file from server if it exists
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }

            // Delete the record from the 'images' table
            $stmt = $conn->prepare("DELETE FROM images WHERE image_id = ?");
            $stmt->bind_param("i", $imageId);
            $stmt->execute();
            $stmt->close();

            echo json_encode(['status' => 'success']);
            exit;
        }
    }
}

echo json_encode(['status' => 'error']);
