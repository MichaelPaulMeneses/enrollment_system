<?php

include 'db_connection.php'; // Include your database connection

header("Content-Type: application/json");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Decode the JSON data from the request
    $data = json_decode(file_get_contents("php://input"), true);

    // Debugging: Check received data
    error_log(print_r($data, true)); // Log the received data

    if (isset($data['student_id']) && isset($data['admin_user_id'])) {
        $student_id = intval($data['student_id']);
        $admin_user_id = intval($data['admin_user_id']);

        // Update the enrollment_status to 'For Payment'
        $sql = "UPDATE students 
            SET enrollment_status = 'For Payment', 
                status_updated_by = ?, 
                status_updated_at = NOW() 
            WHERE student_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $admin_user_id, $student_id);

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
