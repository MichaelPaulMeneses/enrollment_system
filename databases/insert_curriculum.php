<?php
include 'db_connection.php'; // Make sure this includes your database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $curriculumName = $_POST["curriculumName"];
    $curriculumStatus = $_POST["curriculum_is_active"];

    if (!empty($curriculumName)) {
        $stmt = $conn->prepare("INSERT INTO curriculums (curriculum_name, curriculum_is_active) VALUES (?, ?)");
        $stmt->bind_param("si", $curriculumName, $curriculumStatus);

        if ($stmt->execute()) {
            echo json_encode(["status" => "success"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Database error."]);
        }

        $stmt->close();
    } else {
        echo json_encode(["status" => "error", "message" => "All fields are required."]);
    }
}

$conn->close();
?>
