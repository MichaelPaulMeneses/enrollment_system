<?php
include('db_connection.php');

// Get school year ID from POST request
$school_year_id = $_POST['school_year_id'] ?? null;

$query = "
    SELECT 
        g.grade_name, 
        SUM(ph.payment_amount) AS total_revenue
    FROM 
        payment_history ph
    JOIN 
        students s ON ph.student_id = s.student_id
    JOIN 
        grade_levels g ON s.grade_applying_for = g.grade_level_id
    WHERE 
        s.school_year_id = ?
    GROUP BY 
        g.grade_name
    ORDER BY 
        FIELD(g.grade_name, 'Prekindergarten', 'Kindergarten', 'Grade 1', 'Grade 2', 'Grade 3', 
            'Grade 4', 'Grade 5', 'Grade 6', 'Grade 7', 'Grade 8', 
            'Grade 9', 'Grade 10', 'Grade 11', 'Grade 12')
";

$stmt = mysqli_prepare($conn, $query);

// Corrected binding parameter: use $school_year_id instead of $schoolYearId
mysqli_stmt_bind_param($stmt, 'i', $school_year_id);

mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$grades = [];
$revenues = [];

while ($row = mysqli_fetch_assoc($result)) {
    $grades[] = $row['grade_name'];
    $revenues[] = (float)$row['total_revenue'];
}

echo json_encode(['grades' => $grades, 'revenues' => $revenues]);
?>
