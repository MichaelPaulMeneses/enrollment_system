<?php
include "db_connection.php"; // Database connection

if (isset($_GET["subject_id"])) {
    $subject_id = $_GET["subject_id"];

    $query = "SELECT subject_id, subject_code, subject_name FROM subjects WHERE subject_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $subject_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        echo json_encode($row);
    } else {
        echo json_encode(["error" => "Subject not found."]);
    }

    $stmt->close();
} else {
    echo json_encode(["error" => "Invalid request."]);
}
?>
