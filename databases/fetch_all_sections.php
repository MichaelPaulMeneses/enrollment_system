<?php
header("Content-Type: application/json");
require 'db_connection.php'; // Make sure this connects correctly

$stmt = $conn->prepare("
    SELECT 
        MIN(s.section_id) AS section_id,
        CONCAT(g.grade_name, ', ', s.section_name) AS section_info,
        s.section_name
    FROM sections s
    JOIN grade_levels g ON s.grade_level_id = g.grade_level_id
    JOIN school_year sy ON s.school_year_id = sy.school_year_id
    GROUP BY s.section_name
    ORDER BY g.grade_level_id ASC

");


if ($stmt === false) {
    echo json_encode(["status" => "error", "message" => "SQL Prepare Error: " . $conn->error]);
    exit;
}

$stmt->execute();
$result = $stmt->get_result();

$sections = [];
while ($row = $result->fetch_assoc()) {
    $sections[] = $row;
}

echo json_encode([
    "status" => "success",
    "sections" => $sections
]);
?>
