<?php
require 'db_connection.php'; // Include DB connection

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['logoFile'])) {
    $targetDir = __DIR__ . "/../assets/homepage_images/logo/"; // Absolute path

    // Create folder if it doesn't exist
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0777, true);
    }

    $fileName = time() . "_" . basename($_FILES["logoFile"]["name"]); // Prevent overwriting
    $targetFilePath = $targetDir . $fileName;
    $fileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));

    // Allowed file types
    $allowedTypes = ["jpg", "jpeg", "png", "gif"];
    if (in_array($fileType, $allowedTypes)) {
        // Move uploaded file to the correct location
        if (move_uploaded_file($_FILES["logoFile"]["tmp_name"], $targetFilePath)) {
            // Delete old logo from database and filesystem
            $result = $conn->query("SELECT image_path FROM school_logo");
            if ($row = $result->fetch_assoc()) {
                $oldFile = __DIR__ . "/" . $row['image_path'];
                if (file_exists($oldFile)) {
                    unlink($oldFile); // Delete old file
                }
            }

            // Clear old record and insert new logo
            $conn->query("DELETE FROM school_logo");
            $imagePath = "/../assets/homepage_images/logo/" . $fileName; // Relative path for DB

            $stmt = $conn->prepare("INSERT INTO school_logo (image_path) VALUES (?)");
            $stmt->bind_param("s", $imagePath);
            if ($stmt->execute()) {
                echo json_encode(["status" => "success", "image" => $imagePath]);
            } else {
                echo json_encode(["status" => "error", "message" => "Database error"]);
            }
        } else {
            echo json_encode(["status" => "error", "message" => "File upload failed"]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "Invalid file type"]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "No file uploaded"]);
}
?>
