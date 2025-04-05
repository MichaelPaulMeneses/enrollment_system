<?php
include "db_connection.php";
header('Content-Type: application/json');

// Read the JSON data from the POST request body
$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['student_id'])) {
    $student_id = $data['student_id'];
    $user_id = $data['user_id'];

    // Prepare the query
    $query = "SELECT 
                s.student_id,
                CONCAT(s.last_name, ', ', s.first_name, ' ', s.middle_name, ' ', COALESCE(s.suffix, '')) AS student_name,
                g.grade_name AS grade_applying,
                sy.school_year
            FROM students s
            JOIN grade_levels g ON s.grade_applying_for = g.grade_level_id
            JOIN school_year sy ON s.school_year_id = sy.school_year_id
            WHERE s.student_id = ?";

    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $student_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $student = $result->fetch_assoc();
        echo json_encode($student);
    } else {
        echo json_encode(["error" => "Student not found."]);
    }

    $stmt->close();
} else {
    echo json_encode(["error" => "Invalid request."]);
}
?>
