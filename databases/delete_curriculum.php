<?php
// Include the database connection file
include('db_connection.php'); // Make sure to adjust the path to your actual DB connection file

// Check if the curriculum_id is set in the GET request
if (isset($_GET['curriculum_id'])) {
    $curriculum_id = $_GET['curriculum_id'];

    // Create the SQL DELETE statement
    $sql = "DELETE FROM curriculums WHERE curriculum_id = ?";

    // Prepare the statement
    if ($stmt = $conn->prepare($sql)) {
        // Bind the curriculum_id to the statement
        $stmt->bind_param("i", $curriculum_id);

        // Execute the statement
        if ($stmt->execute()) {
            // If successful, send a JSON response with success status
            echo json_encode(['status' => 'success', 'message' => 'Curriculum deleted successfully!']);
        } else {
            // If execution fails, send an error message
            echo json_encode(['status' => 'error', 'message' => 'Failed to delete the curriculum.']);
        }

        // Close the statement
        $stmt->close();
    } else {
        // If statement preparation fails, return an error
        echo json_encode(['status' => 'error', 'message' => 'Error preparing the SQL statement.']);
    }
} else {
    // If no curriculum_id is provided, return an error message
    echo json_encode(['status' => 'error', 'message' => 'Curriculum ID is required.']);
}

// Close the database connection
$conn->close();
?>
