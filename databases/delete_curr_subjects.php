<?php
header("Content-Type: application/json");
include 'db_connection.php';

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["id"])) {
    $currSubjectId = intval($_POST["id"]);

    // Proceed with deletion
    $stmt = $conn->prepare("DELETE FROM curriculum_subjects WHERE id = ?");
    $stmt->bind_param("i", $currSubjectId);

    if ($stmt->execute()) {
        echo json_encode(["status" => "success"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to delete subject."]);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request."]);
}
?>
