<?php
require 'db_connection.php'; // Include DB connection
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../PHPMailer/src/Exception.php';
require '../PHPMailer/src/PHPMailer.php';
require '../PHPMailer/src/SMTP.php';

header("Content-Type: application/json");

// Decode JSON input
$data = json_decode(file_get_contents("php://input"), true);

// Check if required data is present
if (isset($data['student_id'], $data['admin_user_id'], $data['amount_paid'], $data['status_remarks'])) {
    // Assign values
    $student_id = intval($data['student_id']);
    $admin_user_id = intval($data['admin_user_id']);
    $amount_paid = htmlspecialchars(trim($data['amount_paid']));
    $status_remarks = htmlspecialchars(trim($data['status_remarks']));

    // Ensure amount_paid is numeric
    if (!is_numeric($amount_paid) || $amount_paid <= 0) {
        echo json_encode(["success" => false, "message" => "Invalid payment amount."]);
        exit;
    }

    // Step 1: Fetch student details for email
    $fetchSql = "SELECT email, last_name, gender FROM students WHERE student_id = ?";
    $fetchStmt = $conn->prepare($fetchSql);
    $fetchStmt->bind_param("i", $student_id);
    $fetchStmt->execute();
    $result = $fetchStmt->get_result();

    // Check if student exists
    if (!$row = $result->fetch_assoc()) {
        echo json_encode(["success" => false, "message" => "Student details not found for email notification."]);
        exit;
    }

    $userEmail = filter_var($row['email'], FILTER_SANITIZE_EMAIL);
    $surname = htmlspecialchars($row['last_name']);
    $gender = trim($row['gender']);
    $fetchStmt->close();

    // Update enrollment status
    $enrollment_status = 'For Assignment';
    $updateSql = "UPDATE students 
                    SET enrollment_status = ?, 
                        status_remarks = ?, 
                        status_updated_by = ?, 
                        status_updated_at = NOW() 
                    WHERE student_id = ?";
    $updateStmt = $conn->prepare($updateSql);
    $updateStmt->bind_param("ssii", $enrollment_status, $status_remarks, $admin_user_id, $student_id);

    if (!$updateStmt->execute()) {
        echo json_encode(["success" => false, "message" => "Database error while updating enrollment status: " . $updateStmt->error]);
        exit;
    }
    $updateStmt->close();

    // Step 2: Insert payment history
    $insertSql = "INSERT INTO payment_history (student_id, payment_amount, payment_date, payment_facilitated_by)
                    VALUES (?, ?, NOW(), ?)";
    $insertStmt = $conn->prepare($insertSql);
    $insertStmt->bind_param("idi", $student_id, $amount_paid, $admin_user_id);

    if (!$insertStmt->execute()) {
        echo json_encode(["success" => false, "message" => "Database error while inserting payment history: " . $insertStmt->error]);
        exit;
    }
    $insertStmt->close();

    // Step 3: Send Email Notification
    $salutation = ($gender == 'Male') ? 'Mr.' : (($gender == 'Female') ? 'Ms.' : '');
    
    $mail = new PHPMailer(true);
    try {
        // SMTP Configuration
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'sasatoru510@gmail.com';  // Use environment variable
        $mail->Password = 'iuddupmiyesabbvi'; // Use environment variable
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;
        $mail->setFrom('sasatoru510@gmail.com', 'SJBPS Admin');
        $mail->addAddress($userEmail);
        $mail->isHTML(true);
        $mail->Subject = "Enrollment Approved - Status: $enrollment_status";

        // Email body
        $message = "<p>Dear $salutation $surname,</p>
                    <p>We are pleased to inform you that your payment has been received successfully.</p>";
        $message .= "<p>Your enrollment status has been updated to <strong>For Assignment</strong>. You will be assigned to a section shortly. Please wait for further updates from our Admissions Office.</p>";
        $message .= "<p>For any inquiries, feel free to contact our Admissions Office:</p>
                        <p><a href='mailto:registrar.sjbps@gmail.com'>registrar.sjbps@gmail.com</a> | (02) 8296 5896 | 0920 122 5764</p>";

        $mail->Body = $message;

        if ($mail->send()) {
            echo json_encode(["success" => true, "message" => "Enrollment approved and email sent successfully."]);
        } else {
            echo json_encode(["success" => false, "message" => "Failed to send email."]);
        }
    } catch (Exception $e) {
        echo json_encode(["success" => false, "message" => "Mailer Error: " . $mail->ErrorInfo]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Invalid request data."]);
}

// Close database connection
$conn->close();
?>
