<?php
include "db_connection.php"; // Include database connection

// Fetch all subjects without filtering by curriculum
$query = "SELECT sb.subject_id, sb.subject_code, sb.subject_name, gl.grade_name, sb.grade_level_id, sb.academic_track, sb.academic_semester 
            FROM subjects sb 
            JOIN grade_levels gl ON sb.grade_level_id = gl.grade_level_id 
            ORDER BY sb.grade_level_id ASC, sb.academic_track ASC, sb.academic_semester ASC";

$result = $conn->query($query);

$subjects = [];
while ($row = $result->fetch_assoc()) {
    $subjects[] = $row;
}

// Return the result as JSON
echo json_encode($subjects);
?>


