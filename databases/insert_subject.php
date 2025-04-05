<?php

include 'db_connection.php'; // Database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $subject_code = $_POST['subjectCode'];
    $subject_name = $_POST['subjectName'];
    $curriculum_id = $_POST['curriculumId'];
    $grade_level_id = $_POST['gradeLevelId']; 

    // Validate input (basic)
    if (empty($subject_code) || empty($subject_name) || empty($curriculum_id) || empty($grade_level_id)) {
        echo json_encode(['status' => 'error', 'message' => 'All fields are required.']);
        exit();
    }

    // Prepare SQL query
    $query = "INSERT INTO subjects (subject_code, subject_name, curriculum_id, grade_level_id) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($query); // This line was missing
    $stmt->bind_param('ssii', $subject_code, $subject_name, $curriculum_id, $grade_level_id);
    
    if ($stmt->execute()) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to add subject.']);
    }

    $stmt->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request.']);
}
?>
