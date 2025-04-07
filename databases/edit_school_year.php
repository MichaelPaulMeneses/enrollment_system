<?php
require_once "db_connection.php"; // Ensure this file connects to your database

// Check if the request is a POST request
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Sanitize and validate inputs
    $shoolYearId = isset($_POST["editSchoolYearId"]) ? intval($_POST["editSchoolYearId"]) : 0;
    $schoolYearName = isset($_POST["editSchoolYearName"]) ? trim($_POST["editSchoolYearName"]) : '';
    $schoolYearStatus = isset($_POST["editSchoolYearIsActive"]) ? intval($_POST["editSchoolYearIsActive"]) : 0;

    // Validate inputs
    if (empty($schoolYearName)) {
        echo json_encode(["status" => "error", "message" => "School Year name is required."]);
        exit;
    }

    if ($schoolYearStatus !== 0 && $schoolYearStatus !== 1) {
        echo json_encode(["status" => "error", "message" => "Invalid status value."]);
        exit;
    }

    // Prepare the update query
    $stmt = $conn->prepare("UPDATE school_year SET school_year = ?, is_active = ? WHERE school_year_id = ?");
    if ($stmt === false) {
        echo json_encode(["status" => "error", "message" => "Failed to prepare the SQL query."]);
        exit;
    }

    // Bind parameters and execute the query
    $stmt->bind_param("sii", $schoolYearName, $schoolYearStatus, $shoolYearId);
    if ($stmt->execute()) {
        echo json_encode(["status" => "success"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to update school year."]);
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request method."]);
}
?>
