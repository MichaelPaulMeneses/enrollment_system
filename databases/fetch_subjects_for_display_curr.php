<?php
header('Content-Type: application/json');
include "db_connection.php"; // Ensure no output in this file

if ($_SERVER["REQUEST_METHOD"] === "GET" && isset($_GET["curriculum_id"])) {
    $curriculum_id = $_GET["curriculum_id"];

    $query = "SELECT cs.id, sb.subject_id, sb.subject_code, sb.subject_name, 
                     cur.curriculum_name, gl.grade_name, 
                     sb.grade_level_id, sb.academic_track, sb.academic_semester 
              FROM curriculum_subjects cs
              JOIN subjects sb ON cs.subject_id = sb.subject_id
              JOIN curriculums cur ON cs.curriculum_id = cur.curriculum_id
              JOIN grade_levels gl ON sb.grade_level_id = gl.grade_level_id
              WHERE cs.curriculum_id = ?
              ORDER BY sb.grade_level_id ASC, sb.academic_track ASC, sb.academic_semester ASC";
    
    $stmt = $conn->prepare($query);
    
    if ($stmt) {
        $stmt->bind_param("i", $curriculum_id);
        $stmt->execute();
        $result = $stmt->get_result();

        $subjects = [];
        while ($row = $result->fetch_assoc()) {
            $subjects[] = $row;
        }

        $stmt->close();
        echo json_encode($subjects);
    } else {
        echo json_encode(["error" => "Database query failed."]);
    }
} else {
    echo json_encode(["error" => "Invalid request."]);
}
