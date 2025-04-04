<?php

include 'db_connection.php'; // Include your database connection

header("Content-Type: application/json");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Decode the JSON data from the request
    $data = json_decode(file_get_contents("php://input"), true);

    // Debugging: Check received data
    error_log(print_r($data, true)); // Log the received data

    if (isset($data['student_id']) && isset($data['admin_user_id']) && isset($data['status_remarks'])) {
        $student_id = intval($data['student_id']);
        $admin_user_id = intval($data['admin_user_id']);
        $status_remarks = $data['status_remarks']; // Get the decline reason from the request

        // Update the enrollment_status to 'Application Declined' and set the status_remarks
        $sql = "UPDATE students 
                SET enrollment_status = 'Application Declined', 
                    status_updated_by = ?, 
                    status_updated_at = NOW(), 
                    status_remarks = ?
                WHERE student_id = ?";
        $stmt = $conn->prepare($sql);

        // Check for SQL error
        if ($stmt === false) {
            echo json_encode(["success" => false, "message" => "SQL Error: " . $conn->error]);
            exit;
        }

        // Bind the parameters correctly: 
        // "i" for admin_user_id (integer)
        // "s" for status_remarks (string)
        // "i" for student_id (integer)
        $stmt->bind_param("isi", $admin_user_id, $status_remarks, $student_id);

        // Execute the statement and check for errors
        if ($stmt->execute()) {
            echo json_encode(["success" => true, "message" => "Enrollment declined successfully."]);
        } else {
            echo json_encode(["success" => false, "message" => "Failed to execute update."]);
        }

        $stmt->close();
    } else {
        echo json_encode(["success" => false, "message" => "Invalid student ID or missing remarks."]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Invalid request method."]);
}

$conn->close();
?>
