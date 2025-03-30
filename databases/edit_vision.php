<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require 'db_connection.php'; // Ensure this path is correct

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $visionText = trim($_POST['visionText']);

    if (empty($visionText)) {
        echo json_encode(["status" => "error", "message" => "Vision statement cannot be empty."]);
        exit;
    }

    // Check if a vision statement exists
    $checkQuery = "SELECT id FROM homepage_vision ORDER BY id DESC LIMIT 1";
    $checkResult = $conn->query($checkQuery);

    if ($checkResult && $row = $checkResult->fetch_assoc()) {
        // Update existing record
        $stmt = $conn->prepare("UPDATE homepage_vision SET content = ? WHERE id = ?");
        $stmt->bind_param("si", $visionText, $row['id']);
    } else {
        // Insert new vision
        $stmt = $conn->prepare("INSERT INTO homepage_vision (content) VALUES (?)");
        $stmt->bind_param("s", $visionText);
    }

    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Vision updated successfully."]);
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to update vision."]);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request method."]);
}
?>
