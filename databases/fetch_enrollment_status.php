<?php
include('db_connection.php');

$school_year_id = $_POST['school_year_id'] ?? null;

if ($school_year_id) {
    $query = "
        SELECT 
            enrollment_status, COUNT(*) AS count
        FROM 
            students
        WHERE 
            school_year_id = ?
        GROUP BY 
            enrollment_status
    ";

    if ($stmt = mysqli_prepare($conn, $query)) {
        mysqli_stmt_bind_param($stmt, 'i', $school_year_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        $statuses = [];
        $counts = [];

        while ($row = mysqli_fetch_assoc($result)) {
            $statuses[] = $row['enrollment_status'];
            $counts[] = (int)$row['count'];
        }

        echo json_encode(['statuses' => $statuses, 'counts' => $counts]);
        mysqli_stmt_close($stmt);
    } else {
        echo json_encode(['error' => 'Query preparation failed.']);
    }
} else {
    echo json_encode(['error' => 'Missing school_year_id.']);
}

mysqli_close($conn);
?>
