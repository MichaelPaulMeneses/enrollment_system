<?php
require_once "db_connection.php"; // Ensure this file connects to your database

// Check if the request is a POST request
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Sanitize and validate inputs
    $curriculumId = isset($_POST["editCurriculumId"]) ? intval($_POST["editCurriculumId"]) : 0;
    $curriculumName = isset($_POST["editCurriculumName"]) ? trim($_POST["editCurriculumName"]) : '';
    $curriculumStatus = isset($_POST["editCurriculumIsActive"]) ? intval($_POST["editCurriculumIsActive"]) : 0;

    // Validate inputs
    if (empty($curriculumName)) {
        echo json_encode(["status" => "error", "message" => "Curriculum name is required."]);
        exit;
    }

    if ($curriculumStatus !== 0 && $curriculumStatus !== 1) {
        echo json_encode(["status" => "error", "message" => "Invalid status value."]);
        exit;
    }

    // Prepare the update query
    $stmt = $conn->prepare("UPDATE curriculums SET curriculum_name = ?, curriculum_is_active = ? WHERE curriculum_id = ?");
    if ($stmt === false) {
        echo json_encode(["status" => "error", "message" => "Failed to prepare the SQL query."]);
        exit;
    }

    // Bind parameters and execute the query
    $stmt->bind_param("sii", $curriculumName, $curriculumStatus, $curriculumId);
    if ($stmt->execute()) {
        echo json_encode(["status" => "success"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to update curriculum."]);
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request method."]);
}
?>
