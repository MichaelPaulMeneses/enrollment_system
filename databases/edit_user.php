<?php
include "db_connection.php"; // Include your database connection file

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the form data from POST request
    $user_id = isset($_POST['user_id']) ? $_POST['user_id'] : '';
    $username = isset($_POST['username']) ? $_POST['username'] : '';
    $email = isset($_POST['email']) ? $_POST['email'] : '';
    $first_name = isset($_POST['first_name']) ? $_POST['first_name'] : '';
    $last_name = isset($_POST['last_name']) ? $_POST['last_name'] : '';
    $user_type = isset($_POST['user_type']) ? $_POST['user_type'] : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';

    // Validate the data (basic validation)
    if (empty($user_id) || empty($username) || empty($email) || empty($first_name) || empty($last_name) || empty($user_type)) {
        echo json_encode(["status" => "error", "message" => "All fields are required."]);
        exit;
    }


    // If password is provided, hash it before saving to the database
    if (!empty($password)) {
        $password = password_hash($password, PASSWORD_DEFAULT);
        $query = "UPDATE users SET username = ?, email = ?, first_name = ?, last_name = ?, user_type = ?, password = ? WHERE user_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssssssi", $username, $email, $first_name, $last_name, $user_type, $password, $user_id);
    } else {
        // If no new password, update without changing the password
        $query = "UPDATE users SET username = ?, email = ?, first_name = ?, last_name = ?, user_type = ? WHERE user_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sssssi", $username, $email, $first_name, $last_name, $user_type, $user_id);
    }

    // Execute the query
    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "User updated successfully."]);
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to update user details."]);
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request method."]);
}
?>
