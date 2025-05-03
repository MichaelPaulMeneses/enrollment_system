<?php
include 'db_connection.php'; // Database connection

// Enable error reporting for debugging (remove in production)
ini_set('display_errors', 1);
error_reporting(E_ALL);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize input
    $subject_code = $_POST['subjectCode'] ?? '';
    $subject_name = $_POST['subjectName'] ?? '';
    $grade_level_id = intval($_POST['gradeLevelId'] ?? 0); 
    $academic_track = $_POST['academicTrackId'] ?? null;
    $academic_semester = $_POST['academicSemesterId'] ?? null;

    // Validate input (basic)
    if (empty($subject_code) || empty($subject_name) || empty($grade_level_id)) {
        echo json_encode(['status' => 'error', 'message' => 'All fields are required.']);
        exit();
    }

    // Only keep academic_track and academic_semester if Grade 11 or 12
    if (!in_array($grade_level_id, [14, 15])) {
        $academic_track = null;
        $academic_semester = null;
    }

    // Prepare SQL query
    $query = "INSERT INTO subjects (subject_code, subject_name, grade_level_id, academic_track, academic_semester) 
              VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    
    // Check for query preparation failure
    if ($stmt === false) {
        echo json_encode(['status' => 'error', 'message' => 'Failed to prepare SQL query.']);
        exit();
    }

    // Bind parameters
    $stmt->bind_param('ssiss', $subject_code, $subject_name, $grade_level_id, $academic_track, $academic_semester);
    
    // Execute query and check for success
    if ($stmt->execute()) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to add subject. Error: ' . $stmt->error]);
    }

    // Close statement
    $stmt->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request.']);
}
?>
