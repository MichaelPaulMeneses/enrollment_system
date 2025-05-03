<?php
// Include database connection
require_once('db_connection.php');

header('Content-Type: application/json');

if (!isset($_POST['folder_id'])) {
    echo json_encode(["status" => "error", "message" => "Folder ID missing"]);
    exit;
}

$folderId = intval($_POST['folder_id']);  // Using $folderId consistently
$sql = "DELETE FROM folders WHERE folder_id = ?";  // Use 'folder_id' in SQL query

$stmt = $conn->prepare($sql);

if (!$stmt) {
    echo json_encode(['status' => 'error', 'message' => 'Database error: failed to prepare statement.']);
    exit;
}

$stmt->bind_param("i", $folderId);  // Bind correctly with $folderId
$stmt->execute();

if ($stmt->affected_rows > 0) {
    echo json_encode(['status' => 'success', 'message' => 'Folder deleted successfully.']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Folder not found or already deleted.']);
}

$stmt->close();
$conn->close();
?>
