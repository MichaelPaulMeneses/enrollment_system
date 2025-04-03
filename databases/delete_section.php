<?php
require 'db_connection.php'; // Include database connection

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['section_id']) || empty($_POST['section_id'])) {
        echo json_encode(["status" => "error", "message" => "Section ID is required."]);
        exit;
    }
    
    $section_id = $_POST['section_id'];
    
    try {
        $stmt = $conn->prepare("DELETE FROM sections WHERE section_id = ?");
        $stmt->bind_param("i", $section_id);

        if ($stmt->execute()) {
            echo json_encode(["status" => "success", "message" => "Section deleted successfully!"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Failed to delete section."]);
        }
        
        $stmt->close();
        $conn->close();
    } catch (Exception $e) {
        echo json_encode(["status" => "error", "message" => "Error: " . $e->getMessage()]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request method."]);
}
