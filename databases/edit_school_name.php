<?php
require_once 'db_connection.php'; // Adjust the path to your database connection file

header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $school_name = trim($_POST["schoolName"]);

    if (empty($school_name)) {
        echo json_encode(["status" => "error", "message" => "School name cannot be empty."]);
        exit;
    }

    // Delete existing school name entries to keep only one record
    $deleteQuery = "DELETE FROM school_names";
    mysqli_query($conn, $deleteQuery);

    // Insert new school name
    $insertQuery = "INSERT INTO school_names (school_name) VALUES (?)";
    $stmt = mysqli_prepare($conn, $insertQuery);
    mysqli_stmt_bind_param($stmt, "s", $school_name);
    $success = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    if ($success) {
        echo json_encode(["status" => "success", "message" => "School name updated successfully."]);
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to update school name."]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request."]);
}
?>
