<?php
// Set the timezone
date_default_timezone_set('America/Mexico_City');

// Include required files
require_once '../libs/email_settings.php';
$config = include '../libs/config.php';
require_once '../libs/CustomDB.php';
require_once '../vendor/autoload.php'; // Ensure you have PHPMailer installed via Composer

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


// Validate email
function isValidEmail($email): bool {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

// Get the posted email
$posted_email = $_POST['email'] ?? null;

// Validate the email
if (!$posted_email || !isValidEmail($posted_email)) {
    echo "Invalid email address";
    exit;
}

// Save email to the database securely using CustomDB
function saveEmailToDatabase($email) {
    global $config;
    try {
        $db = new CustomDB($config);

        // Insert email into the database using parameterized query
        $query = "INSERT INTO subscription (email) VALUES (:email)";
        $params = [':email' => $email];
        $db->executeQuery($query, $params);
        return true;
    } catch (Exception $e) {
        throw new Exception("Database Error: " . $e->getMessage());
    }
}

// Send email using PHPMailer with email_settings
function sendSubscriptionEmail($email) {
    try {
        // Initialize the email_settings object
        $emailSettings = new email_settings();

        // Create a new PHPMailer instance
        $mail = new PHPMailer(true);

        // Configure SMTP
        $mail->isSMTP();
        $mail->Host = $emailSettings->getHost();
        $mail->SMTPAuth = $emailSettings->isSMTPAuth();
        $mail->Username = $emailSettings->getUsername();
        $mail->Password = $emailSettings->getPassword();
        $mail->SMTPSecure = $emailSettings->getSMTPSecure(); // e.g., 'tls' or 'ssl'
        $mail->Port = $emailSettings->getPort();

        // Set sender information
        $mail->setFrom($emailSettings->getFrom(), $emailSettings->getFromName());
        $mail->addAddress($email); // Recipient

        // Email contents
        $mail->isHTML(true); // Enable HTML
        $mail->Subject = "Subscription Confirmation";
        $mail->Body = "<p>Thank you for subscribing with <strong>{$email}</strong>.</p>";
        $mail->AltBody = "Thank you for subscribing with {$email}."; // Plain text alternative

        // Send the email
        if (!$mail->send()) {
            throw new Exception("Email could not be sent.");// . $mail->ErrorInfo);
        }
        return true;
    } catch (Exception $e) {
        throw new Exception("Email Error");// . $e->getMessage());
    }
}

// Main execution
try {
    // Save the email to the database
    saveEmailToDatabase($posted_email);

    // Send subscription confirmation email
    sendSubscriptionEmail($posted_email);

    // Echo success response
    echo "OK";
} catch (Exception $e) {
    // Echo error response
    echo "Error: " . $e->getMessage();
}