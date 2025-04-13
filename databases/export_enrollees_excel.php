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

// Include PhpSpreadsheet
require_once __DIR__ . '/../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;

// Get filter parameters
$gradeFilter = isset($_GET['grade']) ? $_GET['grade'] : '';
$sectionFilter = isset($_GET['section']) ? $_GET['section'] : '';
$trackFilter = isset($_GET['track']) ? $_GET['track'] : '';
$semesterFilter = isset($_GET['semester']) ? $_GET['semester'] : '';
$schoolYearFilter = isset($_GET['school_year']) ? $_GET['school_year'] : '';

// Query the database
$query = "SELECT 
            a.assigned_id,
            CONCAT(s.last_name, ', ', s.first_name, ' ', COALESCE(s.suffix, '')) AS student_name,
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

// Create a new Spreadsheet object
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Set title and filters
$sheet->setCellValue('A1', 'St. John\'s Baptist Parochial School');
$sheet->setCellValue('A2', 'Enrollees Report - Generated on ' . date('F d, Y'));
$sheet->setCellValue('A3', 'Grade Level: ' . (!empty($gradeFilter) ? $gradeFilter : 'All'));
$sheet->setCellValue('A4', 'Section: ' . (!empty($sectionFilter) ? $sectionFilter : 'All'));
$sheet->setCellValue('A5', 'Track: ' . (!empty($trackFilter) ? $trackFilter : 'All'));
$sheet->setCellValue('A6', 'Semester: ' . (!empty($semesterFilter) ? $semesterFilter : 'All'));
$sheet->setCellValue('A7', 'School Year: ' . (!empty($schoolYearFilter) ? $schoolYearFilter : 'All'));

// Apply bold and larger font size for title and filters
$sheet->getStyle('A1:A7')->getFont()->setBold(true);
$sheet->getStyle('A1')->getFont()->setSize(16);
$sheet->getStyle('A2')->getFont()->setSize(14);

// Set headers for the data table
$sheet->setCellValue('A9', 'No');
$sheet->setCellValue('B9', 'Student Name');
$sheet->setCellValue('C9', 'Grade Level');
$sheet->setCellValue('D9', 'Section');
$sheet->setCellValue('E9', 'Track');
$sheet->setCellValue('F9', 'Semester');
$sheet->setCellValue('G9', 'School Year');
$sheet->setCellValue('H9', 'Status');

// Apply header styles
$sheet->getStyle('A9:H9')->getFont()->setBold(true);
$sheet->getStyle('A9:H9')->getAlignment()->setHorizontal('center');
$sheet->getStyle('A9:H9')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
$sheet->getStyle('A9:H9')->getFill()->getStartColor()->setRGB('D9EAD3');

// Set column width
$sheet->getColumnDimension('A')->setWidth(5);
$sheet->getColumnDimension('B')->setWidth(30);
$sheet->getColumnDimension('C')->setWidth(15);
$sheet->getColumnDimension('D')->setWidth(15);
$sheet->getColumnDimension('E')->setWidth(15);
$sheet->getColumnDimension('F')->setWidth(15);
$sheet->getColumnDimension('G')->setWidth(15);
$sheet->getColumnDimension('H')->setWidth(15);

// Fetch data and populate rows
$rowNum = 10;
$count = 1;
while ($row = mysqli_fetch_assoc($result)) {
    $sheet->setCellValue('A' . $rowNum, $count);
    $sheet->setCellValue('B' . $rowNum, $row['student_name']);
    $sheet->setCellValue('C' . $rowNum, $row['grade_name']);
    $sheet->setCellValue('D' . $rowNum, $row['section_name']);
    $sheet->setCellValue('E' . $rowNum, $row['academic_track'] ?: 'N/A');
    $sheet->setCellValue('F' . $rowNum, $row['academic_semester'] ?: 'N/A');
    $sheet->setCellValue('G' . $rowNum, $row['school_year']);
    $sheet->setCellValue('H' . $rowNum, $row['enrollment_status']);

    // Apply alternating row colors for better readability
    if ($rowNum % 2 == 0) {
        $sheet->getStyle('A' . $rowNum . ':H' . $rowNum)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
        $sheet->getStyle('A' . $rowNum . ':H' . $rowNum)->getFill()->getStartColor()->setRGB('F4F4F4');
    }

    // Apply borders to each row
    $sheet->getStyle('A' . $rowNum . ':H' . $rowNum)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

    $rowNum++;
    $count++;
}

// Apply overall table borders
$sheet->getStyle('A9:H' . ($rowNum - 1))->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

// Create an Excel writer
$writer = new Xlsx($spreadsheet);

// Set headers for the Excel file download
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="SJBPS_All_Enrollees_' . date('Y-m-d') . '.xlsx"');
header('Cache-Control: max-age=0');

// Write the file to output
$writer->save('php://output');

// Close database connection
mysqli_close($conn);
?>
