<?php
include "db_connection.php"; // Include database connection

if ($_SERVER["REQUEST_METHOD"] === "GET" && isset($_GET["curriculum_id"])) {
    $curriculum_id = $_GET["curriculum_id"];

    // Query to fetch subjects based on curriculum_id
    $query = "SELECT sb.subject_id, sb.subject_code, sb.subject_name, cur.curriculum_name, gl.grade_name, sb.grade_level_id, sb.academic_track, sb.academic_semester 
                FROM subjects sb 
                JOIN curriculums cur ON sb.curriculum_id = cur.curriculum_id 
                JOIN grade_levels gl ON sb.grade_level_id = gl.grade_level_id 
                WHERE sb.curriculum_id = ?
                ORDER BY sb.grade_level_id ASC, sb.academic_track ASC, sb.academic_semester ASC
                ";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $curriculum_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $subjects = [];
    while ($row = $result->fetch_assoc()) {
        $subjects[] = $row;
    }

    $stmt->close();
    
    // Return the result as JSON
    echo json_encode($subjects);
} else {
    echo json_encode(["error" => "Invalid request."]);
}
?>


