<?php
require_once 'db_connection.php';

header('Content-Type: application/json');

$response = ['status' => 'error', 'message' => 'Something went wrong'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_POST['user_id'] ?? '';

    if (!empty($user_id) && is_numeric($user_id)) {
        // Optional: You can check if the user exists before deleting
        $check = $conn->prepare("SELECT * FROM users WHERE user_id = ?");
        $check->bind_param("i", $user_id);
        $check->execute();
        $result = $check->get_result();

        if ($result && $result->num_rows > 0) {
            $stmt = $conn->prepare("DELETE FROM users WHERE user_id = ?");
            $stmt->bind_param("i", $user_id);

            if ($stmt->execute()) {
                $response['status'] = 'success';
                $response['message'] = 'User deleted successfully.';
            } else {
                $response['message'] = 'Failed to delete user.';
            }

            $stmt->close();
        } else {
            $response['message'] = 'User not found.';
        }

        $check->close();
    } else {
        $response['message'] = 'Invalid or missing user ID.';
    }
}

echo json_encode($response);
?>
