<?php
include 'db_connection.php'; // Make sure this includes your database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $curriculumName = $_POST["curriculumName"];

    if (!empty($curriculumName)) {
        $stmt = $conn->prepare("INSERT INTO curriculums (curriculum_name) VALUES (?)");
        $stmt->bind_param("s", $curriculumName);

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
