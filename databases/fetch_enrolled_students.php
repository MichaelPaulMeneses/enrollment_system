<?php
require 'db_connection.php'; // Ensure this file connects to your 'enrollment_system' database

$query = "SELECT
                ast.assigned_id,
                CONCAT(s.last_name, ', ', s.first_name, ' ', IFNULL(s.middle_name, ''), ' ', IFNULL(s.suffix, '')) AS student_name,
                gl.grade_name,
                sc.section_name,
                s.academic_track,
                s.academic_semester,
                sy.school_year,
                s.enrollment_status
            FROM assigned_students ast
            JOIN students s ON ast.student_id = s.student_id
            JOIN grade_levels gl ON s.grade_applying_for = gl.grade_level_id
            JOIN sections sc ON ast.section_id = sc.section_id
            JOIN school_year sy ON s.school_year_id = sy.school_year_id
            ORDER BY s.status_updated_at DESC";

$result = $conn->query($query);
$students = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $students[] = $row;
    }
}

echo json_encode($students);
?>
