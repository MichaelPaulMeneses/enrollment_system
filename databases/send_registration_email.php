<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../PHPMailer/src/Exception.php';
require '../PHPMailer/src/PHPMailer.php';
require '../PHPMailer/src/SMTP.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['email'], $_POST['surname'], $_POST['gender'])) {
    $userEmail = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $surname = htmlspecialchars($_POST['surname']);
    $gender = trim($_POST['gender']);

    if (!filter_var($userEmail, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email format.";
        exit;
    }

    // Determine salutation based on gender and log it
    if ($gender == 'Male') {
        $salutation = 'Mr.';
    } elseif ($gender == 'Female') {
        $salutation = 'Ms.';
    } else {
        $salutation = '';
    }

    $mail = new PHPMailer(true);

    try {
        // SMTP Configuration
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; // Your SMTP server
        $mail->SMTPAuth = true;
        $mail->Username = 'sasatoru510@gmail.com'; // Your email
        $mail->Password = 'iuddupmiyesabbvi'; // Your email password (Use App Password if using Gmail)
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Sender and Recipient
        $mail->setFrom('sasatoru510@gmail.com', 'SJBPS Admin');
        $mail->addAddress($userEmail); // Send email to user

        // Email Content
        $mail->isHTML(true);
        $mail->Subject = 'Registration For Review';
        $mail->Body = "<p>Dear $salutation $surname,</p>
                        <p>Thank you for your interest in enrolling at Saint John the Baptist Parochial School. We are pleased to inform you that we have received your application for the upcoming school year.</p>
                        <p>Our admissions team will review your application and contact you within 5-7 business days regarding the next steps.</p>
                        <p>Please check your email regularly for updates and further instructions. If you do not see any updates in your inbox, kindly check your spam or junk folder.</p>
                        <p>If you have any immediate questions, please contact our Admissions Office at 
                        <a href='mailto:registrar.sjbps@gmail.com'>registrar.sjbps@gmail.com</a> or call (02) 8296 5896 and 0920 122 5764.</p>";


        // Send Email
        if ($mail->send()) {
            echo "Email sent successfully!";
        } else {
            echo "Failed to send email.";
        }
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
} else {
    echo "Required fields missing.";
}
?>
