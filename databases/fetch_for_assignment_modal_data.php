<?php
// Include your database connection here
include('db_connection.php');

// Set the response header to indicate JSON format
header('Content-Type: application/json');

// Read the JSON input data
$inputData = json_decode(file_get_contents('php://input'));

// Check if student_id is provided
if (isset($inputData->student_id)) {
    $student_id = $inputData->student_id;

    // Query to fetch student details
    $query = "SELECT CONCAT(s.last_name, ', ', s.first_name, ' ', s.middle_name, ' ', s.suffix) AS full_name, 
                        s.grade_applying_for, gl.grade_name, s.academic_track, s.academic_semester , s.school_year_id
                        FROM students s
                        JOIN grade_levels gl ON s.grade_applying_for = gl.grade_level_id
                        WHERE s.student_id = ?";

    // Prepare the query
    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param("i", $student_id);
        $stmt->execute();
        $result = $stmt->get_result();

        // Check if student data is found
        if ($result->num_rows > 0) {
            $student = $result->fetch_assoc();
            echo json_encode($student); // Return student data as JSON
        } else {
            // Student not found
            echo json_encode(['error' => 'Student not found']);
        }

        // Close the statement
        $stmt->close();
    } else {
        // Query preparation error
        echo json_encode(['error' => 'Failed to prepare the query']);
    }
} else {
    // No student ID provided
    echo json_encode(['error' => 'No student ID provided']);
}

// Close the database connection
$conn->close();
?>
