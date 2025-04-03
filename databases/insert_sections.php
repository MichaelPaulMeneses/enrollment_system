<?php
require 'db_connection.php'; // Include your database connection file

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $section_name = trim($_POST['sectionName']);
    $grade_level_id = $_POST['gradeLevelId'];
    $school_year_id = $_POST['addSchoolYearId'];

    // Validate input
    if (empty($section_name) || empty($grade_level_id) || empty($school_year_id)) {
        echo json_encode(["status" => "error", "message" => "All fields are required."]);
        exit;
    }

    // Insert into database
    $sql = "INSERT INTO sections (section_name, grade_level_id, school_year_id) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("sii", $section_name, $grade_level_id, $school_year_id);
        if ($stmt->execute()) {
            echo json_encode(["status" => "success", "message" => "Section added successfully."]);
        } else {
            echo json_encode(["status" => "error", "message" => "Database error: " . $stmt->error]);
        }
        $stmt->close();
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to prepare statement."]);
    }

    $conn->close();
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request method."]);
}
?>
