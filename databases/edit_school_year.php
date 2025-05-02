<?php
require_once "db_connection.php"; // Ensure this file connects to your database

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $shoolYearId = isset($_POST["editSchoolYearId"]) ? intval($_POST["editSchoolYearId"]) : 0;
    $schoolYearName = isset($_POST["editSchoolYearName"]) ? trim($_POST["editSchoolYearName"]) : '';
    $schoolYearStatus = isset($_POST["editSchoolYearIsActive"]) ? intval($_POST["editSchoolYearIsActive"]) : 0;

    if (empty($schoolYearName)) {
        echo json_encode(["status" => "error", "message" => "School Year name is required."]);
        exit;
    }

    if ($schoolYearStatus !== 0 && $schoolYearStatus !== 1) {
        echo json_encode(["status" => "error", "message" => "Invalid status value."]);
        exit;
    }

    // Step 1: Deactivate currently active school year if setting this one as active
    if ($schoolYearStatus === 1) {
        $deactivateStmt = $conn->prepare("UPDATE school_year SET is_active = 0 WHERE is_active = 1 AND school_year_id != ?");
        $deactivateStmt->bind_param("i", $shoolYearId);
        $deactivateStmt->execute();
        $deactivateStmt->close();
    }

    // Step 2: Update the selected school year
    $stmt = $conn->prepare("UPDATE school_year SET school_year = ?, is_active = ? WHERE school_year_id = ?");
    if ($stmt === false) {
        echo json_encode(["status" => "error", "message" => "Failed to prepare the SQL query."]);
        exit;
    }

    $stmt->bind_param("sii", $schoolYearName, $schoolYearStatus, $shoolYearId);
    if ($stmt->execute()) {
        echo json_encode(["status" => "success"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to update school year."]);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request method."]);
}
?>
