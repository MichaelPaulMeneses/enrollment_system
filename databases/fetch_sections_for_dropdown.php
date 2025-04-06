<?php
header("Content-Type: application/json");
require 'db_connection.php'; // Ensure this file correctly connects to the database

// Get the raw POST data
$inputData = json_decode(file_get_contents('php://input'), true);

// Check if both grade_level_id and school_year_id are provided
if (!isset($inputData['grade_level_id']) || empty($inputData['grade_level_id']) || 
    !isset($inputData['school_year_id']) || empty($inputData['school_year_id'])) {
    echo json_encode(["error" => "Missing grade_level_id or school_year_id"]);
    exit;
}

$grade_level_id = intval($inputData['grade_level_id']);
$school_year_id = intval($inputData['school_year_id']);

try {
    // Both values are required
    $stmt = $conn->prepare("SELECT s.section_id, s.section_name, g.grade_name, sy.school_year 
                            FROM sections s
                            JOIN grade_levels g ON s.grade_level_id = g.grade_level_id
                            JOIN school_year sy ON s.school_year_id = sy.school_year_id
                            WHERE s.grade_level_id = ? AND s.school_year_id = ?");

    if ($stmt === false) {
        echo json_encode(["error" => "SQL Prepare Error: " . $conn->error]);
        exit;
    }

    $stmt->bind_param("ii", $grade_level_id, $school_year_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $sections = [];
    while ($row = $result->fetch_assoc()) {
        $sections[] = $row;
    }

    // Check if sections are found
    if (count($sections) > 0) {
        echo json_encode(["status" => "success", "sections" => $sections]);
    } else {
        echo json_encode(["status" => "error", "message" => "No sections found"]);
    }
} catch (mysqli_sql_exception $e) {
    echo json_encode(["error" => "SQL Error: " . $e->getMessage()]);
}
?>
