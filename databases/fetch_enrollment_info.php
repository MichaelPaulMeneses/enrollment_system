<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require 'db_connection.php'; // Same folder

header("Content-Type: application/json");

$response = ["status" => "error", "message" => "Failed to fetch enrollment information"];

$query = "SELECT content FROM homepage_enrollment_important_info ORDER BY id DESC LIMIT 1";
$result = mysqli_query($conn, $query);

if ($result) {
    $row = mysqli_fetch_assoc($result);
    if ($row) {
        $response["status"] = "success";
        $response["info"] = $row["content"];
    } else {
        $response["message"] = "No enrollment information found.";
    }
} else {
    $response["message"] = "Database error: " . mysqli_error($conn);
}

echo json_encode($response);
exit;
?>
