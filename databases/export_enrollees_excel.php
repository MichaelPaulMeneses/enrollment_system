<?php
session_start();

// Check user authentication
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin') {
    header('HTTP/1.0 403 Forbidden');
    echo "You are not authorized to access this page.";
    exit;
}

// Include database connection
require_once 'db_connection.php';

// Set headers for Excel download
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment; filename="SJBPS_All_Enrollees_' . date('Y-m-d') . '.xls"');
header('Pragma: no-cache');
header('Expires: 0');

// Get filter parameters
$gradeFilter = isset($_GET['grade']) ? $_GET['grade'] : '';
$sectionFilter = isset($_GET['section']) ? $_GET['section'] : '';
$trackFilter = isset($_GET['track']) ? $_GET['track'] : '';
$semesterFilter = isset($_GET['semester']) ? $_GET['semester'] : '';
$schoolYearFilter = isset($_GET['school_year']) ? $_GET['school_year'] : '';

// Based on your actual database schema
$query = "SELECT 
            a.assigned_id,
            CONCAT(s.last_name, ', ', s.first_name, ' ', s.middle_name) AS student_name,
            gl.grade_name,
            sec.section_name,
            s.academic_track,
            s.academic_semester,
            sy.school_year,
            s.enrollment_status
          FROM 
            assigned_students a
            JOIN students s ON a.student_id = s.student_id
            JOIN sections sec ON a.section_id = sec.section_id
            JOIN grade_levels gl ON sec.grade_level_id = gl.grade_level_id
            JOIN school_year sy ON sec.school_year_id = sy.school_year_id
          WHERE 
            s.enrollment_status = 'Fully Enrolled'";

// Add filters if provided
if (!empty($gradeFilter)) {
    $query .= " AND gl.grade_name = '" . mysqli_real_escape_string($conn, $gradeFilter) . "'";
}
if (!empty($sectionFilter)) {
    $query .= " AND sec.section_name = '" . mysqli_real_escape_string($conn, $sectionFilter) . "'";
}
if (!empty($trackFilter)) {
    $query .= " AND s.academic_track = '" . mysqli_real_escape_string($conn, $trackFilter) . "'";
}
if (!empty($semesterFilter)) {
    $query .= " AND s.academic_semester = '" . mysqli_real_escape_string($conn, $semesterFilter) . "'";
}
if (!empty($schoolYearFilter)) {
    $query .= " AND sy.school_year = '" . mysqli_real_escape_string($conn, $schoolYearFilter) . "'";
}

$query .= " ORDER BY gl.grade_level_id ASC, sec.section_name ASC, s.last_name ASC";

$result = mysqli_query($conn, $query);

if (!$result) {
    // If there's an error with the query, log it and provide a useful message
    error_log("Query Error: " . mysqli_error($conn));
    echo "Error fetching data: " . mysqli_error($conn);
    exit;
}

// Start the Excel file content
echo '<!DOCTYPE html>';
echo '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">';
echo '<head>';
echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8">';
echo '<meta name="ProgId" content="Excel.Sheet">';
echo '<meta name="Generator" content="Microsoft Excel 11">';
echo '<style>
        table {
            border-collapse: collapse;
            width: 100%;
        }
        th, td {
            border: 1px solid #000000;
            padding: 5px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        .header {
            font-size: 16pt;
            font-weight: bold;
            text-align: center;
            margin-bottom: 10px;
        }
        .subheader {
            font-size: 12pt;
            text-align: center;
            margin-bottom: 20px;
        }
      </style>';
echo '</head>';
echo '<body>';

// Header information
echo '<div class="header">St. John\'s Baptist Parochial School</div>';
echo '<div class="subheader">Enrollees Report - Generated on ' . date('F d, Y') . '</div>';

// Filter information
echo '<table style="margin-bottom: 15px; width: 50%;">';
echo '<tr><th colspan="2">Filter Criteria</th></tr>';
echo '<tr><td>Grade Level</td><td>' . (!empty($gradeFilter) ? htmlspecialchars($gradeFilter) : 'All') . '</td></tr>';
echo '<tr><td>Section</td><td>' . (!empty($sectionFilter) ? htmlspecialchars($sectionFilter) : 'All') . '</td></tr>';
echo '<tr><td>Academic Track</td><td>' . (!empty($trackFilter) ? htmlspecialchars($trackFilter) : 'All') . '</td></tr>';
echo '<tr><td>Academic Semester</td><td>' . (!empty($semesterFilter) ? htmlspecialchars($semesterFilter) : 'All') . '</td></tr>';
echo '<tr><td>School Year</td><td>' . (!empty($schoolYearFilter) ? htmlspecialchars($schoolYearFilter) : 'All') . '</td></tr>';
echo '</table>';

// Start the main table
echo '<table>';
echo '<thead>';
echo '<tr>';
echo '<th>#</th>';
echo '<th>Student Name</th>';
echo '<th>Grade Level</th>';
echo '<th>Section</th>';
echo '<th>Track</th>';
echo '<th>Semester</th>';
echo '<th>School Year</th>';
echo '<th>Status</th>';
echo '</tr>';
echo '</thead>';
echo '<tbody>';

if (mysqli_num_rows($result) > 0) {
    $count = 1;
    while ($row = mysqli_fetch_assoc($result)) {
        echo '<tr>';
        echo '<td>' . $count . '</td>';
        echo '<td>' . htmlspecialchars($row['student_name']) . '</td>';
        echo '<td>' . htmlspecialchars($row['grade_name']) . '</td>';
        echo '<td>' . htmlspecialchars($row['section_name']) . '</td>';
        echo '<td>' . (isset($row['academic_track']) && $row['academic_track'] ? htmlspecialchars($row['academic_track']) : 'N/A') . '</td>';
        echo '<td>' . (isset($row['academic_semester']) && $row['academic_semester'] ? htmlspecialchars($row['academic_semester']) : 'N/A') . '</td>';
        echo '<td>' . htmlspecialchars($row['school_year']) . '</td>';
        echo '<td>' . htmlspecialchars($row['enrollment_status']) . '</td>';
        echo '</tr>';
        $count++;
    }
} else {
    echo '<tr><td colspan="8" style="text-align: center;">No enrollees found with the specified criteria.</td></tr>';
}

echo '</tbody>';
echo '</table>';
echo '</body>';
echo '</html>';

// Close database connection
mysqli_close($conn);
?>