<?php
include "db_connection.php"; // Database connection

// Use POST instead of GET
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["user_id"])) {
    $user_id = $_POST["user_id"];

    $query = "SELECT * FROM users WHERE user_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        echo json_encode($row);
    } else {
        echo json_encode(["error" => "User not found."]);
    }

    $stmt->close();
} else {
    echo json_encode(["error" => "Invalid request."]);
}
?>
