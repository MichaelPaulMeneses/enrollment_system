<?php
// Include database connection
include 'db_connection.php';

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["subject_id"])) {
    $subjectId = $_POST["subject_id"];  // Get the subject_id from POST data

    // Prepare SQL to delete the subject based on subject_id
    $stmt = $conn->prepare("DELETE FROM subjects WHERE subject_id = ?");
    $stmt->bind_param("i", $subjectId);  // Bind the subject_id as an integer

    // Execute the query and return a JSON response
    if ($stmt->execute()) {
        echo json_encode(["status" => "success"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to delete subject."]);
    }

    // Close the prepared statement and connection
    $stmt->close();
    $conn->close();
}
?>
