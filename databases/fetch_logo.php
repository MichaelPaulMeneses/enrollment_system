<?php
require 'db_connection.php';

$result = $conn->query("SELECT image_path FROM school_logo ORDER BY id DESC LIMIT 1");

if ($result) {
    if ($row = $result->fetch_assoc()) {
        $baseUrl = "http://localhost/enrollment_system/databases";

        echo json_encode(["status" => "success", "image" => $baseUrl . $row['image_path']]);
    } else {
        echo json_encode(["status" => "error", "message" => "No logo found"]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Database query failed"]);
}
?>
