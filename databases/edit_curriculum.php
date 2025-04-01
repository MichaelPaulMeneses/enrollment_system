<?php
require_once "db_connection.php"; // Ensure this file connects to your database

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $curriculumId = $_POST["editCurriculumId"];
    $curriculumName = $_POST["editCurriculumName"];
    $schoolYearId = $_POST["editSchoolYearModal"];  // This must match the name attribute in your form

    // Validate inputs
    if (empty($curriculumId) || empty($curriculumName) || empty($schoolYearId)) {
        echo json_encode(["status" => "error", "message" => "All fields are required."]);
        exit;
    }

    $stmt = $conn->prepare("UPDATE curriculums SET curriculum_name = ?, school_year_id = ? WHERE curriculum_id = ?");
    $stmt->bind_param("sii", $curriculumName, $schoolYearId, $curriculumId);
    

    // Execute the query and check for success
    if ($stmt->execute()) {
        echo json_encode(["status" => "success"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to update curriculum."]);
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
}
?>
