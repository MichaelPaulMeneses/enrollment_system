<?php
header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include database connection
include 'db_connection.php';

// Validate database connection
if (!$conn) {
    echo json_encode(["status" => "error", "message" => "Database connection failed"]);
    exit();
}

// Prepare the query
$query = $conn->prepare("SELECT * FROM curriculums ORDER BY curriculum_name ASC");
$query->execute();
$result = $query->get_result();

// Fetch results
$curriculums = $result->fetch_all(MYSQLI_ASSOC);

// Return response
echo json_encode(["status" => "success", "curriculums" => $curriculums]);

// Close resources
$query->close();
$conn->close();
?>
