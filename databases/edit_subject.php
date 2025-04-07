<?php
include 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $subjectId = $_POST['editSubjectId'];
    $subject_code = $_POST['editSubjectCode'];
    $subject_name = $_POST['editSubjectName'];
    $grade_level_id = intval($_POST['editGradeLevelId']); 
    $academic_track = $_POST['editAcademicTrackId'] ?? null;
    $academic_semester = $_POST['editAcademicSemesterId'] ?? null;

    if (empty($subjectId) || empty($subject_code) || empty($subject_name) || empty($grade_level_id)) {
        echo json_encode(['status' => 'error', 'message' => 'All fields are required.']);
        exit();
    }

    // Only keep academic_track and academic_semester if Grade 11 or 12
    if (!in_array($grade_level_id, [14, 15])) {
        $academic_track = null;
        $academic_semester = null;
    }

    $query = "UPDATE subjects 
                SET subject_code = ?, subject_name = ?, grade_level_id = ?, academic_track = ?, academic_semester = ? 
                WHERE subject_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('ssisii', $subject_code, $subject_name, $grade_level_id, $academic_track, $academic_semester, $subjectId);

    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "subject_id" => $subjectId, "subject_name" => $subject_name]);
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to update subject."]);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request.']);
}
?>
