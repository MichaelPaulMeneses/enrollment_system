<?php
session_start();
include 'db_connection.php'; // Include your database connection

header("Content-Type: application/json");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Decode the JSON data from the request
    $data = json_decode(file_get_contents("php://input"), true);

    if (isset($data["student_id"])) {
        $student_id = intval($data["student_id"]);

        // Update the enrollment_status to 'For Payment'
        $sql = "UPDATE students SET enrollment_status = 'For Payment' WHERE student_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $student_id);

        if ($stmt->execute()) {
            echo json_encode(["success" => true, "message" => "Enrollment approved successfully."]);
        } else {
            echo json_encode(["success" => false, "message" => "Database error."]);
        }

        $stmt->close();
    } else {
        echo json_encode(["success" => false, "message" => "Invalid student ID."]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Invalid request method."]);
}

$conn->close();
?>
