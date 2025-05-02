<?php
require_once "db_connection.php"; // Ensure this file connects to your database

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $curriculumId = isset($_POST["editCurriculumId"]) ? intval($_POST["editCurriculumId"]) : 0;
    $curriculumName = isset($_POST["editCurriculumName"]) ? trim($_POST["editCurriculumName"]) : '';
    $curriculumStatus = isset($_POST["editCurriculumIsActive"]) ? intval($_POST["editCurriculumIsActive"]) : 0;

    if (empty($curriculumName)) {
        echo json_encode(["status" => "error", "message" => "Curriculum name is required."]);
        exit;
    }

    if ($curriculumStatus !== 0 && $curriculumStatus !== 1) {
        echo json_encode(["status" => "error", "message" => "Invalid status value."]);
        exit;
    }

    // Step 1: Deactivate other curriculums if setting this one as active
    if ($curriculumStatus === 1) {
        $deactivateStmt = $conn->prepare("UPDATE curriculums SET curriculum_is_active = 0 WHERE curriculum_is_active = 1 AND curriculum_id != ?");
        $deactivateStmt->bind_param("i", $curriculumId);
        $deactivateStmt->execute();
        $deactivateStmt->close();
    }

    // Step 2: Update the selected curriculum
    $stmt = $conn->prepare("UPDATE curriculums SET curriculum_name = ?, curriculum_is_active = ? WHERE curriculum_id = ?");
    if ($stmt === false) {
        echo json_encode(["status" => "error", "message" => "Failed to prepare the SQL query."]);
        exit;
    }

    $stmt->bind_param("sii", $curriculumName, $curriculumStatus, $curriculumId);
    if ($stmt->execute()) {
        echo json_encode(["status" => "success"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to update curriculum."]);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request method."]);
}
?>
