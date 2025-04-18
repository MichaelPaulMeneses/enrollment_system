<?php
require 'db_connection.php'; // Include database connection

$response = ["status" => "error", "message" => "Something went wrong."];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $uploadDir = __DIR__ . "/../assets/homepage_images/carousel/"; // Folder for images
    $imagePaths = [];

    // Ensure the folder exists
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    // Process uploaded files
    for ($i = 1; $i <= 3; $i++) {
        $fileKey = "carouselFile$i";
        if (!empty($_FILES[$fileKey]["name"])) {
            $fileName = time() . "_" . basename($_FILES[$fileKey]["name"]);
            $targetFilePath = $uploadDir . $fileName;
            $dbFilePath = "assets/homepage_images/carousel/" . $fileName; // Relative path for database
            
            // Validate file type (only images)
            $fileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));
            $allowedTypes = ["jpg", "jpeg", "png", "gif"];

            if (in_array($fileType, $allowedTypes)) {
                if (move_uploaded_file($_FILES[$fileKey]["tmp_name"], $targetFilePath)) {
                    $imagePaths[$i] = $dbFilePath;
                } else {
                    $response["message"] = "Failed to upload $fileName.";
                    echo json_encode($response);
                    exit;
                }
            } else {
                $response["message"] = "Invalid file type for $fileName.";
                echo json_encode($response);
                exit;
            }
        }
    }

    // Clear previous carousel images
    $conn->query("DELETE FROM homepage_carousel");

    // Insert new images
    foreach ($imagePaths as $path) {
        $stmt = $conn->prepare("INSERT INTO homepage_carousel (image_path) VALUES (?)");
        $stmt->bind_param("s", $path);
        $stmt->execute();
    }

    $response = ["status" => "success", "message" => "Carousel updated successfully!"];
}

echo json_encode($response);
?>
