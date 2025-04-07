<?php
require 'db_connection.php'; // Include database connection

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['school_year_id']) || empty($_POST['school_year_id'])) {
        echo json_encode(["status" => "error", "message" => "School Year ID is required."]);
        exit;
    }

    $school_year_id = $_POST['school_year_id'];

    try {
        $stmt = $conn->prepare("DELETE FROM school_year WHERE school_year_id = ?");
        $stmt->bind_param("i", $school_year_id);

        if ($stmt->execute()) {
            echo json_encode(["status" => "success", "message" => "School Year deleted successfully!"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Failed to delete school year."]);
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
