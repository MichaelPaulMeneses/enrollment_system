<?php
include 'db_connection.php';

ini_set('display_errors', 1);
error_reporting(E_ALL);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize input
    $subject_id = $_POST['subjectId'];  // Ensure the key matches the form field
    $curriculum_id = $_POST['curriculumId'];

    if (empty($subject_id) || empty($curriculum_id)) {
        echo json_encode(['status' => 'error', 'message' => 'Both Subject ID and Curriculum ID are required.']);
        exit();
    }

    // Prepare SQL query to insert into curriculum_subjects
    $query = "INSERT INTO curriculum_subjects (subject_id, curriculum_id) VALUES (?, ?)";
    $stmt = $conn->prepare($query);

    if ($stmt === false) {
        echo json_encode(['status' => 'error', 'message' => 'Failed to prepare SQL query.']);
        exit();
    }

    $stmt->bind_param('ii', $subject_id, $curriculum_id);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to add curriculum-subject. Error: ' . $stmt->error]);
    }

    $stmt->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request.']);
}

?>
