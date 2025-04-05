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

// Get and validate school year ID
$school_year_id = isset($_GET['school_year_id']) ? intval($_GET['school_year_id']) : 0;

if ($school_year_id <= 0) {
    echo json_encode(["status" => "error", "message" => "Invalid school year ID"]);
    exit();
}

// Prepare the query
$query = $conn->prepare("SELECT * FROM curriculums WHERE school_year_id = ? ORDER BY curriculum_name ASC");
$query->bind_param("i", $school_year_id);
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
