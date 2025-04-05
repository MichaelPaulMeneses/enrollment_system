<?php
header("Content-Type: application/json");
error_reporting(E_ALL);
ini_set("display_errors", 0); // Disable direct error output
ini_set("log_errors", 1);
ini_set("error_log", "php_errors.log"); // Log errors

require_once "db_connection.php";

$response = ["exists" => false];

// Get JSON input
$jsonInput = file_get_contents("php://input");
$data = json_decode($jsonInput, true);

// Validate JSON format
if (!is_array($data)) {
    echo json_encode(["error" => "Invalid JSON format"]);
    exit;
}

// Check for missing fields
if (!isset($data["firstName"], $data["middleName"], $data["lastName"], $data["birthDate"], $data["gradeApplyingFor"], $data["schoolYearId"])) {
    echo json_encode(["error" => "Missing required fields"]);
    exit;
}

// Sanitize and format inputs
$firstName = trim($data["firstName"]);
$middleName = trim($data["middleName"]);
$lastName = trim($data["lastName"]);
$birthDate = trim($data["birthDate"]);
$gradeApplyingFor = intval($data["gradeApplyingFor"]);
$schoolYearId = intval($data["schoolYearId"]); // Fix field name

// Prepare SQL statement
$stmt = $conn->prepare("SELECT COUNT(*) FROM students WHERE first_name = ? AND middle_name = ? AND last_name = ? AND date_of_birth = ? AND grade_applying_for = ? AND school_year_id = ?");
if (!$stmt) {
    error_log("Prepared statement failed: " . $conn->error);
    echo json_encode(["error" => "Database error. Please try again later."]);
    exit;
}

// Bind parameters
$stmt->bind_param("ssssii", $firstName, $middleName, $lastName, $birthDate, $gradeApplyingFor, $schoolYearId);
if (!$stmt->execute()) {
    error_log("Execute failed: " . $stmt->error);
    echo json_encode(["error" => "Database error. Please try again later."]);
    exit;
}

// Fetch result properly
$stmt->bind_result($count);
$stmt->fetch();

// Set response
if ($count > 0) {
    $response["exists"] = true;
}

// Close resources
$stmt->close();
$conn->close();

// Return JSON response
echo json_encode($response);
?>
