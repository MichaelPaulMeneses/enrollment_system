<?php
// Include the database connection
include('db_connection.php');

// Check if folder names are received
if (isset($_POST['folderNames']) && is_array($_POST['folderNames'])) {
    $folderNames = $_POST['folderNames'];
    $successCount = 0;
    $errorMessages = [];

    foreach ($folderNames as $name) {
        $trimmedName = trim($name);
        if (!empty($trimmedName)) {
            // Prepare and bind
            $stmt = $conn->prepare("INSERT INTO folders (folder_name) VALUES (?)");
            $stmt->bind_param("s", $trimmedName);

            if ($stmt->execute()) {
                $successCount++;
            } else {
                $errorMessages[] = "Failed to add folder: $trimmedName";
            }

            $stmt->close();
        }
    }

    if ($successCount > 0) {
        echo json_encode([
            "status" => "success",
            "message" => "$successCount folder(s) added successfully."
        ]);
    } else {
        echo json_encode([
            "status" => "error",
            "message" => implode(", ", $errorMessages)
        ]);
    }
} else {
    echo json_encode([
        "status" => "error",
        "message" => "No folder names received."
    ]);
}

$conn->close();
?>
