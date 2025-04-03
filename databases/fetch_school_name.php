<?php
require_once 'db_connection.php'; // Adjust the path to your database connection file

header('Content-Type: application/json');

$query = "SELECT school_name FROM school_names ORDER BY id DESC LIMIT 1";
$result = mysqli_query($conn, $query);

if ($row = mysqli_fetch_assoc($result)) {
    echo json_encode(["status" => "success", "school_name" => $row['school_name']]);
} else {
    echo json_encode(["status" => "error", "message" => "No school name found."]);
}
?>
