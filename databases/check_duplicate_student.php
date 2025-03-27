<?php
header("Content-Type: application/json");
error_reporting(E_ALL);
ini_set("display_errors", 1);

require_once "db_connection.php"; // Ensure this points to your correct database connection file

$response = ["exists" => false];

try {
    // Read JSON input
    $jsonInput = file_get_contents("php://input");
    file_put_contents("debug_log.txt", "Raw JSON Input: " . $jsonInput . "\n", FILE_APPEND); // Debugging

    $data = json_decode($jsonInput, true);

    // Check if required fields are present
    if (!$data || !isset($data["firstName"], $data["middleName"], $data["lastName"], $data["birthDate"])) {
        echo json_encode(["error" => "Missing required fields", "received" => $data]);
        exit;
    }

    // Sanitize inputs
    $firstName = trim($data["firstName"]);
    $middleName = trim($data["middleName"]);
    $lastName = trim($data["lastName"]);
    $birthDate = trim($data["birthDate"]);

    // Prepare and execute query
    $stmt = $conn->prepare("SELECT COUNT(*) FROM students WHERE first_name = ? AND middle_name = ? AND last_name = ? AND date_of_birth = ?");
    if (!$stmt) {
        throw new Exception("Prepared statement failed: " . $conn->error);
    }

    // Bind parameters and execute
    $stmt->bind_param("ssss", $firstName, $middleName, $lastName, $birthDate);
    if (!$stmt->execute()) {
        throw new Exception("Execute failed: " . $stmt->error);
    }

    // Get the result
    $stmt->bind_result($count);
    $stmt->fetch();

    // Check if the student already exists
    if ($count > 0) {
        $response["exists"] = true;
    }

    // Close statement and connection
    $stmt->close();
    $conn->close();

    // Return the response as JSON
    echo json_encode($response);
} catch (Exception $e) {
    error_log("Error in check_duplicate_student.php: " . $e->getMessage());
    echo json_encode(["error" => "Database error: " . $e->getMessage()]);
}

?>
