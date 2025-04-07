<?php
include 'db_connection.php';
header('Content-Type: application/json');

$response = ['admin_exists' => false];

$sql = "SELECT COUNT(*) as count FROM users WHERE user_type = 'admin'";
$result = $conn->query($sql);
if ($result) {
    $row = $result->fetch_assoc();
    if ($row['count'] > 0) {
        $response['admin_exists'] = true;
    }
}

echo json_encode($response);
