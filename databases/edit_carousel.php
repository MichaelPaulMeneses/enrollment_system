<?php
require 'db_connection.php';

$response = ["status" => "error", "message" => "Something went wrong."];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $uploadDir = __DIR__ . "/../assets/homepage_images/carousel/";
    $imagePaths = [];

    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    foreach ($_FILES as $key => $file) {
        if (!empty($file["name"])) {
            $fileName = time() . "_" . basename($file["name"]);
            $targetFilePath = $uploadDir . $fileName;
            $dbFilePath = "assets/homepage_images/carousel/" . $fileName;
            $fileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));
            $allowedTypes = ["jpg", "jpeg", "png", "gif"];

            if (in_array($fileType, $allowedTypes)) {
                if (move_uploaded_file($file["tmp_name"], $targetFilePath)) {
                    $imagePaths[] = $dbFilePath;
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

    // Insert only new image records without deleting old ones
    foreach ($imagePaths as $path) {
        $stmt = $conn->prepare("INSERT INTO homepage_carousel (image_path) VALUES (?)");
        $stmt->bind_param("s", $path);
        $stmt->execute();
    }

    $response = ["status" => "success", "message" => "Carousel updated successfully!"];
}

echo json_encode($response);
?>
