<?php
include 'db_connection.php'; // Include database connection
header('Content-Type: application/json');

$response = ['status' => 'error', 'message' => 'Unknown error occurred.'];

if ($conn->connect_error) {
    $response['message'] = "Connection failed: " . $conn->connect_error;
    echo json_encode($response);
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = $_POST['username'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $password_repeat = $_POST['password_repeat'] ?? '';
    $first_name = $_POST['first_name'] ?? '';
    $last_name = $_POST['last_name'] ?? '';
    $user_type = $_POST['user_type'] ?? '';

    if ($password !== $password_repeat) {
        $response['message'] = "Passwords do not match.";
    } else {
        // Check if username or email already exists
        $sql_check = "SELECT * FROM users WHERE username = ? OR email = ?";
        $stmt_check = $conn->prepare($sql_check);
        $stmt_check->bind_param("ss", $username, $email);
        $stmt_check->execute();
        $result_check = $stmt_check->get_result();

        if ($result_check->num_rows > 0) {
            $response['message'] = "Username or email already exists.";
        } else {
            // Insert user
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $sql = "INSERT INTO users (username, password, email, first_name, last_name, user_type)
                    VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssssss", $username, $hashed_password, $email, $first_name, $last_name, $user_type);

            if ($stmt->execute()) {
                $response['status'] = 'success';
                $response['message'] = 'User added successfully.';
            } else {
                $response['message'] = "There was an error while inserting the user.";
            }
        }
    }
}

echo json_encode($response);
