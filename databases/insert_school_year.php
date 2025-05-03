<?php
include 'db_connection.php'; // Make sure this includes your database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $schoolYearName = $_POST["schoolYearName"];
    $schoolYearStatus = $_POST["school_year_is_active"];

    if (!empty($schoolYearName)) {
        // Step 1: Deactivate any currently active school year
        if ($schoolYearStatus == 1) {
            $deactivateStmt = $conn->prepare("UPDATE school_year SET is_active = 0 WHERE is_active = 1");
            $deactivateStmt->execute();
            $deactivateStmt->close();
        }

        // Step 2: Insert the new school year
        $stmt = $conn->prepare("INSERT INTO school_year (school_year, is_active, created_at) VALUES (?, ?, NOW())");
        $stmt->bind_param("si", $schoolYearName, $schoolYearStatus);

        if ($stmt->execute()) {
            echo json_encode(["status" => "success"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Database error."]);
        }

        $stmt->close();
    } else {
        echo json_encode(["status" => "error", "message" => "All fields are required."]);
    }
}

$conn->close();
?>

