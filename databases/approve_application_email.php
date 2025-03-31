<?php
require 'db_connection.php'; // Include DB connection
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../PHPMailer/src/Exception.php';
require '../PHPMailer/src/PHPMailer.php';
require '../PHPMailer/src/SMTP.php';

header("Content-Type: application/json");

$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['email'], $data['surname'], $data['gender'])) {
    $userEmail = filter_var($data['email'], FILTER_SANITIZE_EMAIL);
    $surname = htmlspecialchars($data['surname']);
    $gender = trim($data['gender']);

    if (!filter_var($userEmail, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(["success" => false, "message" => "Invalid email format."]);
        exit;
    }

    $salutation = ($gender == 'Male') ? 'Mr.' : (($gender == 'Female') ? 'Ms.' : '');

    $mail = new PHPMailer(true);

    try {
        // SMTP Configuration
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'sasatoru510@gmail.com';
        $mail->Password = 'iuddupmiyesabbvi'; 
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Sender and Recipient
        $mail->setFrom('sasatoru510@gmail.com', 'SJBPS Admin');
        $mail->addAddress($userEmail);

        // Email Content
        $mail->isHTML(true);
        $mail->Subject = 'Enrollment Approved - Proceed to Payment';
        $mail->Body    = "<p>Dear $salutation $surname,</p>
                            <p>Your enrollment at Saint John the Baptist Parochial School has been approved!</p>
                            <p>Please proceed with the payment process to finalize your registration.</p>
                            <p>For payment instructions, visit our website or contact our Admissions Office:</p>
                            <p><a href='mailto:registrar.sjbps@gmail.com'>registrar.sjbps@gmail.com</a> | (02) 8296 5896 | 0920 122 5764</p>";

        if ($mail->send()) {
            echo json_encode(["success" => true, "message" => "Email sent successfully."]);
        } else {
            echo json_encode(["success" => false, "message" => "Failed to send email."]);
        }
    } catch (Exception $e) {
        echo json_encode(["success" => false, "message" => "Mailer Error: {$mail->ErrorInfo}"]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Missing email details."]);
}
?>
