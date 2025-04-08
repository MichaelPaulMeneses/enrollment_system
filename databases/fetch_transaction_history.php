<?php
require 'db_connection.php'; // Ensure this file connects to your 'enrollment_system' database

$school_year_id = $_POST['school_year_id'] ?? null;

if ($school_year_id) {
    // Query to fetch payment history for the specific school year
    $query = "SELECT
                p.payment_id,
                CONCAT(s.last_name, ', ', s.first_name, ' ', IFNULL(s.middle_name, ''), ' ', IFNULL(s.suffix, '')) AS student_name,
                CONCAT('₱', FORMAT(p.payment_amount, 2)) AS payment_amount,
                p.payment_date,
                CONCAT(u.user_type, ' ', u.first_name, ' ', u.last_name) AS facilitator_name
            FROM payment_history p
            JOIN students s ON p.student_id = s.student_id
            JOIN users u ON p.payment_facilitated_by = u.user_id
            WHERE s.school_year_id = ?
            ORDER BY p.payment_date ASC";

    // Prepare the statement
    if ($stmt = $conn->prepare($query)) {
        // Bind the school year id parameter
        $stmt->bind_param("i", $school_year_id);

        // Execute the statement
        $stmt->execute();

        // Get the result
        $result = $stmt->get_result();
        $students = [];

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $students[] = $row;
            }
        }

        // Close the statement
        $stmt->close();

        // Return the result as JSON
        echo json_encode($students);
    } else {
        echo json_encode(['error' => 'Failed to prepare the SQL statement']);
    }
} else {
    echo json_encode(['error' => 'School year ID is required']);
}
?>
