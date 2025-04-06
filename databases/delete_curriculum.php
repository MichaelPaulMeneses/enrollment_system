<?php
require 'db_connection.php'; // Include database connection

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['curriculum_id']) || empty($_POST['curriculum_id'])) {
        echo json_encode(["status" => "error", "message" => "Curriculum ID is required."]);
        exit;
    }

    $curriculum_id = $_POST['curriculum_id'];

    try {
        $stmt = $conn->prepare("DELETE FROM curriculums WHERE curriculum_id = ?");
        $stmt->bind_param("i", $curriculum_id);

        if ($stmt->execute()) {
            echo json_encode(["status" => "success", "message" => "Curriculum deleted successfully!"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Failed to delete curriculum."]);
        }

        $stmt->close();
        $conn->close();
    } catch (Exception $e) {
        echo json_encode(["status" => "error", "message" => "Error: " . $e->getMessage()]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request method."]);
}
?>
