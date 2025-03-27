<?php
header("Content-Type: application/json");
require_once "db_connection.php"; // Ensure this connects to your database

$response = ["exists" => false];

try {
    // Get JSON request body
    $data = json_decode(file_get_contents("php://input"), true);
    
    if (!isset($data["email"])) {
        echo json_encode(["error" => "Email not provided"]);
        exit;
    }

    $email = trim($data["email"]);

    // Database query to check if email exists
    $stmt = $conn->prepare("SELECT 1 FROM students WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $response["exists"] = true;
    }

    echo json_encode($response);
} catch (Exception $e) {
    error_log("Error in check_email.php: " . $e->getMessage());
    echo json_encode(["error" => "Database error"]);
}
?>
