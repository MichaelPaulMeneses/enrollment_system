<?php
// Include database connection
require_once('db_connection.php');

header('Content-Type: application/json');

if (!isset($_POST['folder_id']) || !isset($_POST['folder_name'])) {
    echo json_encode(["status" => "error", "message" => "Folder ID or Name missing"]);
    exit;
}

$folderId = intval($_POST['folder_id']);
$folderName = $_POST['folder_name'];

$sql = "UPDATE folders SET folder_name = ? WHERE folder_id = ?";

$stmt = $conn->prepare($sql);

if (!$stmt) {
    echo json_encode(['status' => 'error', 'message' => 'Database error: failed to prepare statement.']);
    exit;
}

$stmt->bind_param("si", $folderName, $folderId);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    echo json_encode(['status' => 'success', 'message' => 'Folder updated successfully.']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Folder not found or no changes made.']);
}

$stmt->close();
$conn->close();
?>
