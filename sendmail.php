<?php
// Show errors for debugging (remove in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Start session for rate limiting
session_start();

// Helper function to send JSON or redirect
function respond($response) {
    if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
        header('Content-Type: application/json');
        echo json_encode($response);
    } else {
        $url = 'contact.html?status=' . $response['status'] . '&message=' . urlencode($response['message']);
        header("Location: $url");
    }
    exit;
}

// Optional: Referer check (comment out if not needed)
if (!isset($_SERVER['HTTP_REFERER']) || parse_url($_SERVER['HTTP_REFERER'], PHP_URL_HOST) !== $_SERVER['HTTP_HOST']) {
    respond([
        'status' => 'error',
        'message' => 'Invalid request origin.'
    ]);
}

// Only accept POST
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    respond([
        'status' => 'error',
        'message' => 'Invalid request method.'
    ]);
}

// Rate limiting: Max 5 requests per hour per session
$now = time();
$limit = 5;
$period = 3600;

if (isset($_SESSION['last_time']) && ($now - $_SESSION['last_time']) < $period) {
    $_SESSION['count'] = ($_SESSION['count'] ?? 0) + 1;
    if ($_SESSION['count'] > $limit) {
        respond([
            'status' => 'error',
            'message' => 'Too many requests. Please try again later.'
        ]);
    }
} else {
    $_SESSION['last_time'] = $now;
    $_SESSION['count'] = 1;
}

// Sanitize inputs
$fname   = htmlspecialchars(trim($_POST['fname'] ?? ''));
$lname   = htmlspecialchars(trim($_POST['lname'] ?? ''));
$email   = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);
$phone   = htmlspecialchars(trim($_POST['phone'] ?? ''));
$message = htmlspecialchars(trim($_POST['msg'] ?? ''));

// Validate
$errors = [];

if (empty($fname) || strlen($fname) > 50) $errors[] = "First name is invalid.";
if (empty($lname) || strlen($lname) > 50) $errors[] = "Last name is invalid.";
if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($email) > 100) $errors[] = "Email is invalid.";
if (empty($phone) || strlen($phone) > 20) $errors[] = "Phone number is invalid.";
if (empty($message) || strlen($message) > 1000) $errors[] = "Message is invalid.";

if (!empty($errors)) {
    respond([
        'status' => 'error',
        'message' => implode(" ", $errors)
    ]);
}

// Prepare email
$to = "info@royalicecandy.com";
$subject = "New Contact Form Submission - IceDelights";

$headers = "MIME-Version: 1.0\r\n";
$headers .= "Content-type:text/html;charset=UTF-8\r\n";
$headers .= "From: IceDelights <no-reply@royalicecandy.com>\r\n";
$headers .= "Reply-To: $email\r\n";

$body = '
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Contact Form Submission</title>
</head>
<body style="font-family:Arial,sans-serif;max-width:600px;margin:auto;background:#f4f4f4;padding:20px;">
    <div style="background:#764ba2;color:#fff;padding:20px;border-radius:8px 8px 0 0;">
        <h2>🍦 IceDelights - New Contact Submission</h2>
    </div>
    <div style="background:#fff;padding:20px;border-radius:0 0 8px 8px;">
        <p><strong>Name:</strong> ' . $fname . ' ' . $lname . '</p>
        <p><strong>Email:</strong> <a href="mailto:' . $email . '">' . $email . '</a></p>
        <p><strong>Phone:</strong> <a href="tel:' . $phone . '">' . $phone . '</a></p>
        <p><strong>Message:</strong><br>' . nl2br($message) . '</p>
        <hr>
        <p style="font-size:12px;color:#888;">
            Submitted on ' . date('F j, Y \a\t g:i A') . '<br>
            IP Address: ' . $_SERVER['REMOTE_ADDR'] . '<br>
            This message was sent from your website contact form.
        </p>
    </div>
</body>
</html>';

// Send the email
$mailSent = mail($to, $subject, $body, $headers);

if ($mailSent) {
    respond([
        'status' => 'success',
        'message' => 'Thank you! Your message has been sent.'
    ]);
} else {
    respond([
        'status' => 'error',
        'message' => 'Sorry, there was an error sending your message. Please try again later.'
    ]);
}
?>
