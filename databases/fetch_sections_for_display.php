<?php
header("Content-Type: application/json");
require 'db_connection.php'; // Ensure this file correctly connects to the database

// Check if both grade_level_id and school_year_id are provided
if (!isset($_GET['grade_level_id']) || empty($_GET['grade_level_id']) || 
    !isset($_GET['school_year_id']) || empty($_GET['school_year_id'])) {
    echo json_encode(["error" => "Missing grade_level_id or school_year_id"]);
    exit;
}

$grade_level_id = intval($_GET['grade_level_id']);
$school_year_id = intval($_GET['school_year_id']);

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

    echo json_encode($sections);
} catch (mysqli_sql_exception $e) {
    echo json_encode(["error" => "SQL Error: " . $e->getMessage()]);
}
?>
