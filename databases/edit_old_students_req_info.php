<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require 'db_connection.php'; // Same folder

header("Content-Type: application/json");

$response = ["status" => "error", "message" => "Failed to update information"];

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["oldStudentsText"])) {
    $oldStudentsText = mysqli_real_escape_string($conn, $_POST["oldStudentsText"]);

    // Update the most recent entry
    $query = "UPDATE homepage_old_students 
                SET old_desc = '$oldStudentsText' 
                WHERE id = (SELECT id FROM homepage_old_students ORDER BY id DESC LIMIT 1)";

    if (mysqli_query($conn, $query)) {
        $response["status"] = "success";
        $response["message"] = "Information updated successfully";
    } else {
        $response["message"] = "Database error: " . mysqli_error($conn);
    }
}

echo json_encode($response);
exit;
?>
