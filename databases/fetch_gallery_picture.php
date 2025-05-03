<?php
// Set headers
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


// Include DB connection
include 'db_connection.php';

// Validate and sanitize input
$folder_id = isset($_GET['folder_id']) ? intval($_GET['folder_id']) : 0;

if ($folder_id <= 0) {
    echo json_encode([]);
    exit;
}

// Prepare and execute query
$sql = "SELECT image_id, image_path FROM images WHERE folder_id = ?";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    echo json_encode(["error" => "Query prepare failed"]);
    exit;
}

$stmt->bind_param("i", $folder_id);
$stmt->execute();
$result = $stmt->get_result();

// Build response
$pictures = [];
while ($row = $result->fetch_assoc()) {
    $pictures[] = [
        "image_id" => $row["image_id"],
        "image_path" => $row["image_path"]
    ];
}

// Output as JSON
echo json_encode($pictures);
exit;
?>
