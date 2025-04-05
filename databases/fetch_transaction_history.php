<?php
require 'db_connection.php'; // Ensure this file connects to your 'enrollment_system' database

$query = "SELECT
                p.payment_id,
                CONCAT(s.last_name, ', ', s.first_name, ' ', IFNULL(s.middle_name, ''), ' ', IFNULL(s.suffix, '')) AS student_name,
                CONCAT('₱', FORMAT(p.payment_amount, 2)) AS payment_amount,
                p.payment_date,
                CONCAT(u.user_type, ' ', u.first_name, ' ', u.last_name) AS facilitator_name
            FROM payment_history p
            JOIN students s ON p.student_id = s.student_id
            JOIN users u ON p.payment_facilitated_by = u.user_id
            ORDER BY p.payment_date ASC";

$result = $conn->query($query);
$students = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $students[] = $row;
    }
}

echo json_encode($students);
?>
