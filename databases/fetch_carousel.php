<?php
require 'db_connection.php';

$result = $conn->query("SELECT image_path FROM homepage_carousel");
$images = [];
while ($row = $result->fetch_assoc()) {
    $images[] = $row;
}

echo json_encode($images);
?>
