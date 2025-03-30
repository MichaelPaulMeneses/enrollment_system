<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require 'db_connection.php'; // Ensure this path is correct

header('Content-Type: application/json');

$query = "SELECT content FROM homepage_vision ORDER BY id DESC LIMIT 1";
$result = $conn->query($query);

if ($result && $row = $result->fetch_assoc()) {
    echo json_encode(["status" => "success", "vision" => $row['content']]);
} else {
    echo json_encode(["status" => "error", "message" => "No vision statement found."]);
}

$conn->close();
?>
