<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require 'db_connection.php';

header("Content-Type: application/json");

$response = ["status" => "success", "mission" => ""];

$query = "SELECT content FROM homepage_mission LIMIT 1";  // Correct table & column name
$result = mysqli_query($conn, $query);

if ($result && $row = mysqli_fetch_assoc($result)) {
    $response["mission"] = htmlspecialchars($row["content"], ENT_QUOTES, 'UTF-8');
} else {
    $response["status"] = "error";
    $response["message"] = "Mission not found";
}

echo json_encode($response);
exit;
?>
