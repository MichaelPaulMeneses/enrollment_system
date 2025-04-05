<?php
include('db_connection.php'); // Ensure database connection is included

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get submitted form data with correct field names
    $subjectId = $_POST['editSubjectId'];  
    $subjectCode = $_POST['editSubjectCode'];  
    $subjectName = $_POST['editSubjectName'];  
    $gradeLevelId = $_POST['editGradeLevelId'];

    // Validate inputs
    if (empty($subjectId) || empty($subjectCode) || empty($subjectName) || empty($gradeLevelId)) {
        echo json_encode(["status" => "error", "message" => "All fields are required."]);
        exit;
    }

    // Prepare SQL query to update subject details
    $sql = "UPDATE subjects SET subject_code = ?, subject_name = ?, grade_level_id = ? WHERE subject_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssii", $subjectCode, $subjectName, $gradeLevelId, $subjectId);

    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "subject_id" => $subjectId, "subject_name" => $subjectName]);
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to update subject."]);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request."]);
}
?>
