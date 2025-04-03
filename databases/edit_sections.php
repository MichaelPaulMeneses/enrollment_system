<?php
require 'db_connection.php'; // Include your database connection file

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $section_id = $_POST['sectionId']; // Fixed key
    $section_name = trim($_POST['editSectionName']);
    $grade_level_id = $_POST['editGradeLevel'];
    $school_year_id = $_POST['editschoolYearId'];

    if (empty($section_id) || empty($section_name) || empty($grade_level_id) || empty($school_year_id)) {
        echo json_encode(["status" => "error", "message" => "All fields are required."]);
        exit;
    }

    try {
        $stmt = $conn->prepare("UPDATE sections SET section_name = ?, grade_level_id = ?, school_year_id = ? WHERE section_id = ?");
        $stmt->bind_param("siii", $section_name, $grade_level_id, $school_year_id, $section_id);

        if ($stmt->execute()) {
            echo json_encode(["status" => "success", "message" => "Section updated successfully!"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Failed to update section."]);
        }

        $stmt->close();
        $conn->close();
    } catch (Exception $e) {
        echo json_encode(["status" => "error", "message" => "Error: " . $e->getMessage()]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request method."]);
}
