<?php

// Include database connection
require_once 'db_connection.php';

// Include PhpSpreadsheet
require_once __DIR__ . '/../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;

// Get filter parameters from GET request
$schoolYearFilter = isset($_GET['school_year_id']) ? $_GET['school_year_id'] : '';
$minAmount = isset($_GET['min_amount']) ? $_GET['min_amount'] : '';
$maxAmount = isset($_GET['max_amount']) ? $_GET['max_amount'] : '';
$startDate = isset($_GET['start_date']) ? $_GET['start_date'] : '';
$endDate = isset($_GET['end_date']) ? $_GET['end_date'] : '';
$facilitator = isset($_GET['facilitator']) ? $_GET['facilitator'] : '';

// Start building the query for fetching transaction history
// Start building the query for fetching transaction history
$query = "SELECT 
                p.payment_id,
                CONCAT(s.last_name, ', ', s.first_name, ' ', COALESCE(s.suffix, '')) AS student_name,
                p.payment_amount,
                p.payment_date,
                CONCAT(u.user_type, ' ', u.first_name, ' ', u.last_name) AS facilitator_name,
                sy.school_year,  -- Fetch the school_year name
                sy.school_year_id
            FROM 
                payment_history p
                JOIN students s ON p.student_id = s.student_id
                JOIN users u ON p.payment_facilitated_by = u.user_id
                JOIN school_year sy ON s.school_year_id = sy.school_year_id  -- Join with the school_year table
            WHERE 1=1"; // Base condition to make adding other filters easier

// Add filters if provided
if (!empty($schoolYearFilter)) {
    $query .= " AND sy.school_year_id = '" . mysqli_real_escape_string($conn, $schoolYearFilter) . "'";
}
if (!empty($minAmount)) {
    $query .= " AND p.payment_amount >= " . mysqli_real_escape_string($conn, $minAmount);
}
if (!empty($maxAmount)) {
    $query .= " AND p.payment_amount <= " . mysqli_real_escape_string($conn, $maxAmount);
}
if (!empty($startDate)) {
    $query .= " AND p.payment_date >= '" . mysqli_real_escape_string($conn, $startDate) . " 00:00:00'";
}
if (!empty($endDate)) {
    $query .= " AND p.payment_date <= '" . mysqli_real_escape_string($conn, $endDate) . " 23:59:59'";
}

if (!empty($facilitator)) {
    $query .= " AND CONCAT(u.user_type, ' ', u.first_name, ' ', u.last_name) = '" . mysqli_real_escape_string($conn, $facilitator) . "'";
}

$query .= " ORDER BY p.payment_date DESC";

// Execute the query
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

$schoolyear_query = "SELECT
                        school_year 
                    FROM school_year
                    WHERE school_year_id = ?
";

$schoolYearName = ''; // Default to empty

if (!empty($schoolYearFilter)) {
    $stmt = mysqli_prepare($conn, $schoolyear_query);
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $schoolYearFilter);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $schoolYearName);
        mysqli_stmt_fetch($stmt);
        mysqli_stmt_close($stmt);
    } else {
        error_log("Statement prepare failed: " . mysqli_error($conn));
    }
}




// Set title and filters
$sheet->setCellValue('A1', 'Transaction History Report');
$sheet->setCellValue('A2', 'Generated on ' . date('F d, Y'));
$sheet->setCellValue('A3', 'School Year: ' . (!empty($schoolYearName) ? $schoolYearName : 'All'));
$sheet->setCellValue('A4', 'Amount Range: ' . (!empty($minAmount) ? '$' . $minAmount : 'No Minimum') . ' - ' . (!empty($maxAmount) ? '$' . $maxAmount : 'No Maximum'));
$sheet->setCellValue('A5', 'Date Range: ' . (!empty($startDate) ? $startDate : 'No Start Date') . ' to ' . (!empty($endDate) ? $endDate : 'No End Date'));
$sheet->setCellValue('A6', 'Facilitator: ' . (!empty($facilitator) ? $facilitator : 'All'));

// Apply bold and larger font size for title and filters
$sheet->getStyle('A1:A6')->getFont()->setBold(true);
$sheet->getStyle('A1')->getFont()->setSize(16);
$sheet->getStyle('A2')->getFont()->setSize(14);

// Set headers for the data table
$sheet->setCellValue('A8', 'No');
$sheet->setCellValue('B8', 'Student Name');
$sheet->setCellValue('C8', 'Payment Amount');
$sheet->setCellValue('D8', 'Payment Date');
$sheet->setCellValue('E8', 'Facilitator');
$sheet->setCellValue('F8', 'School Year');

// Apply header styles
$sheet->getStyle('A8:F8')->getFont()->setBold(true);
$sheet->getStyle('A8:F8')->getAlignment()->setHorizontal('center');
$sheet->getStyle('A8:F8')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
$sheet->getStyle('A8:F8')->getFill()->getStartColor()->setRGB('D9EAD3');

// Set column width
$sheet->getColumnDimension('A')->setWidth(5);
$sheet->getColumnDimension('B')->setWidth(30);
$sheet->getColumnDimension('C')->setWidth(15);
$sheet->getColumnDimension('D')->setWidth(15);
$sheet->getColumnDimension('E')->setWidth(20);
$sheet->getColumnDimension('F')->setWidth(15);

// Fetch data and populate rows
$rowNum = 9;
$count = 1;
while ($row = mysqli_fetch_assoc($result)) {
    $sheet->setCellValue('A' . $rowNum, $count);
    $sheet->setCellValue('B' . $rowNum, $row['student_name']);
    $sheet->setCellValue('C' . $rowNum, '$' . number_format($row['payment_amount'], 2));
    $sheet->setCellValue('D' . $rowNum, $row['payment_date']);
    $sheet->setCellValue('E' . $rowNum, $row['facilitator_name'] ?: 'N/A');
    $sheet->setCellValue('F' . $rowNum, $row['school_year']);

    // Apply alternating row colors for better readability
    if ($rowNum % 2 == 0) {
        $sheet->getStyle('A' . $rowNum . ':F' . $rowNum)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
        $sheet->getStyle('A' . $rowNum . ':F' . $rowNum)->getFill()->getStartColor()->setRGB('F4F4F4');
    }

    // Apply borders to each row
    $sheet->getStyle('A' . $rowNum . ':F' . $rowNum)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

    $rowNum++;
    $count++;
}

// Apply overall table borders
$sheet->getStyle('A8:F' . ($rowNum - 1))->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

// Create an Excel writer
$writer = new Xlsx($spreadsheet);

// Set headers for the Excel file download
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="Transaction_History_' . date('Y-m-d') . '.xlsx"');
header('Cache-Control: max-age=0');

// Write the file to output
$writer->save('php://output');

// Close database connection
mysqli_close($conn);
?>
