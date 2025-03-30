<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require 'db_connection.php'; // Same folder

header("Content-Type: application/json");

$response = ["status" => "error", "message" => "Failed to update enrollment information"];

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["enrollmentInfoText"])) {
    $enrollmentInfoText = mysqli_real_escape_string($conn, $_POST["enrollmentInfoText"]);

    // Update the most recent entry
    $query = "UPDATE homepage_enrollment_important_info 
                SET content = '$enrollmentInfoText' 
                WHERE id = (SELECT id FROM homepage_enrollment_important_info ORDER BY id DESC LIMIT 1)";

    if (mysqli_query($conn, $query)) {
        $response["status"] = "success";
        $response["message"] = "Enrollment information updated successfully";
    } else {
        $response["message"] = "Database error: " . mysqli_error($conn);
    }
}

echo json_encode($response);
exit;
?>
