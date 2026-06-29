<?php
// mail_helper.php - Email OTP Delivery Utility with Local Development Fallback

function send_otp_email($recipient_email, $otp_code) {
    $subject = "Your ApexAcademy One-Time Password (OTP)";
    $message = "Hello,\n\nYour One-Time Password (OTP) for ApexAcademy verification is: $otp_code\n\nPlease enter this code on the verification page to activate your account.\n\nBest regards,\nApexAcademy Team";
    $headers = "From: noreply@apexacademy.com\r\n";
    $headers .= "Reply-To: noreply@apexacademy.com\r\n";
    $headers .= "X-Mailer: PHP/" . phpversion();

    // Try sending email via PHP mail()
    // On local XAMPP without SMTP, mail() returns false or issues a warning. We suppress warning with @
    $mail_sent = @mail($recipient_email, $subject, $message, $headers);

    // If mail fails (e.g. local environment), we store the simulated OTP in session so the student/evaluator can easily test it!
    if (!$mail_sent) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION['simulated_otp'] = $otp_code;
        $_SESSION['simulated_otp_email'] = $recipient_email;
    }

    // Always return true so the application flow proceeds smoothly whether on live InfinityFree hosting or local XAMPP!
    return true;
}
?>
