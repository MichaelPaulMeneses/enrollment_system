<?php
header('Content-Type: application/json');

$uploadDir = '../assets/school_gallery/';
$folder_id = $_POST['folder_id'] ?? null;

if (empty($folder_id)) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'No folder selected.']);
    exit;
}

require_once 'db_connection.php';

// Get folder name from folders table
$stmt = $conn->prepare("SELECT folder_name FROM folders WHERE folder_id = ?");
$stmt->bind_param("i", $folder_id);
$stmt->execute();
$result = $stmt->get_result();
$folderRow = $result->fetch_assoc();

if (!$folderRow) {
    http_response_code(404);
    echo json_encode(['status' => 'error', 'message' => 'Folder not found.']);
    exit;
}

$folderName = $folderRow['folder_name'];
$targetFolder = $uploadDir . $folderName . '/';

// Ensure folder exists
if (!is_dir($targetFolder)) {
    if (!mkdir($targetFolder, 0755, true)) {
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => 'Failed to create folder on server.']);
        exit;
    }
}

$uploadSuccess = false;

foreach ($_FILES as $file) {
    if ($file['error'] === UPLOAD_ERR_OK && is_uploaded_file($file['tmp_name'])) {
        $fileName = basename($file['name']);
        $targetPath = $targetFolder . $fileName;

        if (move_uploaded_file($file['tmp_name'], $targetPath)) {
            $imagePathDB = 'assets/school_gallery/' . $folderName . '/' . $fileName;

            $stmt = $conn->prepare("INSERT INTO images (folder_id, image_path) VALUES (?, ?)");
            $stmt->bind_param("is", $folder_id, $imagePathDB);
            if ($stmt->execute()) {
                $uploadSuccess = true;
            }
        }
    }
}

if ($uploadSuccess) {
    echo json_encode(['Upload successful.']);
} else {
    http_response_code(500);
    echo json_encode(['No files were uploaded.']);
}
