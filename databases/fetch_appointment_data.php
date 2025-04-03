<?php
// Include database connection
include('db_connection.php');

// SQL Query to fetch student data, including the school year
$sql = "
    SELECT 
        s.student_id,
        CONCAT(s.first_name, ' ', s.middle_name, ' ', s.last_name) AS student_name,
        s.type_of_student,
        s.grade_applying_for,
        s.appointment_date,
        s.appointment_time,
        sy.school_year
    FROM students s
    JOIN school_year sy ON s.school_year_id = sy.school_year_id
    WHERE s.enrollment_status = 'For Appointment'
    ORDER BY sy.school_year DESC, s.appointment_date ASC, s.appointment_time ASC
";
$result = $conn->query($sql);

$applications = array();

// Check if there are any results and fetch them
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $applications[] = $row;
    }
}

// Close the database connection
$conn->close();

// Return the data as a JSON response
echo json_encode($applications);
?>
