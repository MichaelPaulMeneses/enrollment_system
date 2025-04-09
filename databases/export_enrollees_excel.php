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

// Base query
$query = "SELECT 
            s.first_name, 
            s.last_name, 
            s.middle_name,
            g.grade_name,
            sec.section_name,
            e.academic_track,
            e.academic_semester,
            sy.school_year,
            e.enrollment_status
          FROM 
            student_grade_assignments e
            JOIN students s ON e.student_id = s.student_id
            JOIN grades g ON e.grade_id = g.grade_id
            JOIN sections sec ON e.section_id = sec.section_id
            JOIN school_years sy ON e.school_year_id = sy.id
          WHERE 1=1";

// Add filters if provided
if (!empty($gradeFilter)) {
    $query .= " AND g.grade_name = '$gradeFilter'";
}
if (!empty($sectionFilter)) {
    $query .= " AND sec.section_name = '$sectionFilter'";
}
if (!empty($trackFilter)) {
    $query .= " AND e.academic_track = '$trackFilter'";
}
if (!empty($semesterFilter)) {
    $query .= " AND e.academic_semester = '$semesterFilter'";
}
if (!empty($schoolYearFilter)) {
    $query .= " AND sy.school_year = '$schoolYearFilter'";
}

$query .= " ORDER BY g.grade_id ASC, sec.section_name ASC, s.last_name ASC";

$result = mysqli_query($conn, $query);

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
echo '<tr><td>Grade Level</td><td>' . (!empty($gradeFilter) ? $gradeFilter : 'All') . '</td></tr>';
echo '<tr><td>Section</td><td>' . (!empty($sectionFilter) ? $sectionFilter : 'All') . '</td></tr>';
echo '<tr><td>Academic Track</td><td>' . (!empty($trackFilter) ? $trackFilter : 'All') . '</td></tr>';
echo '<tr><td>Academic Semester</td><td>' . (!empty($semesterFilter) ? $semesterFilter : 'All') . '</td></tr>';
echo '<tr><td>School Year</td><td>' . (!empty($schoolYearFilter) ? $schoolYearFilter : 'All') . '</td></tr>';
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
        echo '<td>' . $row['last_name'] . ', ' . $row['first_name'] . ' ' . $row['middle_name'] . '</td>';
        echo '<td>' . $row['grade_name'] . '</td>';
        echo '<td>' . $row['section_name'] . '</td>';
        echo '<td>' . ($row['academic_track'] ? $row['academic_track'] : 'N/A') . '</td>';
        echo '<td>' . ($row['academic_semester'] ? $row['academic_semester'] : 'N/A') . '</td>';
        echo '<td>' . $row['school_year'] . '</td>';
        echo '<td>' . $row['enrollment_status'] . '</td>';
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