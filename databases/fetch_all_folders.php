<?php
// Include the database connection file
include('db_connection.php');

// Set the response type to JSON
header('Content-Type: application/json');

if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(['error' => 'Database connection failed.']);
    exit;
}

// Query to fetch folder IDs and names
$sql = "SELECT folder_id, folder_name FROM folders ORDER BY folder_name ASC";
$result = $conn->query($sql);

$folders = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $folders[] = [
            'folder_id' => $row['folder_id'],
            'folder_name' => $row['folder_name']
        ];
    }
}

// Return folders as JSON
echo json_encode($folders);
$conn->close();
?>
