<?php

include 'db_connection.php'; // Database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $subject_code = $_POST['subjectCode'];
    $subject_name = $_POST['subjectName'];
    $curriculum_id = $_POST['curriculumId'];
    $grade_level_id = intval($_POST['gradeLevelId']); 
    $academic_track = $_POST['academicTrackId'] ?? null;
    $academic_semester = $_POST['academicSemesterId'] ?? null;

    // Validate input (basic)
    if (empty($subject_code) || empty($subject_name) || empty($curriculum_id) || empty($grade_level_id)) {
        echo json_encode(['status' => 'error', 'message' => 'All fields are required.']);
        exit();
    }

    // Only keep academic_track and academic_semester if Grade 11 or 12
    if (!in_array($grade_level_id, [14, 15])) {
        $academic_track = null;
        $academic_semester = null;
    }

    // Prepare SQL query
    $query = "INSERT INTO subjects (subject_code, subject_name, curriculum_id, grade_level_id, academic_track, academic_semester) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query); // This line was missing
    $stmt->bind_param('ssiisi', $subject_code, $subject_name, $curriculum_id, $grade_level_id, $academic_track, $academic_semester);
    
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
