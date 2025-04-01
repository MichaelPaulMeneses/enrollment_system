<?php
include "db_connection.php"; // Include database connection

if ($_SERVER["REQUEST_METHOD"] === "GET" && isset($_GET["curriculum_id"])) {
    $curriculum_id = $_GET["curriculum_id"];

    // Query to fetch subjects based on curriculum_id
    $query = "SELECT sb.subject_id, sb.subject_code, sb.subject_name, cur.curriculum_name 
                FROM subjects sb 
                JOIN curriculums cur ON sb.curriculum_id = cur.curriculum_id 
                WHERE cur.curriculum_id = ?
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


