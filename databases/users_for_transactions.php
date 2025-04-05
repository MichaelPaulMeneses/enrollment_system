<?php
include 'db_connection.php'; // Include database connection

$sql = "SELECT user_id, 
                CONCAT(user_type, ' ', first_name, ' ', last_name) AS full_name 
        FROM users 
        ORDER BY user_type ASC";
$result = $conn->query($sql);

$users = array();
while ($row = $result->fetch_assoc()) {
    $users[] = [
        'user_id' => $row['user_id'],
        'full_name' => $row['full_name']
    ];
}

// Return JSON response
echo json_encode([
    "status" => "success",
    "users" => $users
]);

$conn->close();
?>
