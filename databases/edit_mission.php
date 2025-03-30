<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require 'db_connection.php';

header("Content-Type: application/json");

$response = ["status" => "error", "message" => "Failed to update mission"];

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["missionText"])) {
    $missionText = mysqli_real_escape_string($conn, $_POST["missionText"]);

    $query = "UPDATE homepage_mission SET content = '$missionText' WHERE id = 1";
    if (mysqli_query($conn, $query)) {
        $response["status"] = "success";
        $response["message"] = "Mission updated successfully";
    } else {
        $response["message"] = "Database error: " . mysqli_error($conn);
    }
}

echo json_encode($response);
exit;
?>
