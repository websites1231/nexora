<?php
// Basic contact handler. Your hosting provider must enable PHP mail() for delivery.
$to = 'sopport@websky.com';
function clean($value) { return trim(str_replace(["\r", "\n"], ' ', (string)$value)); }
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { header('Location: contact.html'); exit; }
if (!empty($_POST['website'])) { http_response_code(400); exit('Invalid submission.'); }
$name = clean($_POST['name'] ?? '');
$email = filter_var(trim($_POST['email'] ?? ''), FILTER_VALIDATE_EMAIL);
$subject = clean($_POST['subject'] ?? 'Website enquiry');
$message = trim($_POST['message'] ?? '');
if ($name === '' || !$email || $subject === '' || $message === '' || strlen($message) > 4000) {
  http_response_code(422); exit('Please complete all fields with valid information. Go back and try again.');
}
$body = "New message from the WEBSKY website\n\nName: $name\nEmail: $email\nSubject: $subject\n\nMessage:\n$message\n";
$headers = "From: WEBSKY Website <no-reply@" . preg_replace('/[^a-z0-9.-]/i', '', $_SERVER['HTTP_HOST'] ?? 'websky.in') . ">\r\n";
$headers .= "Reply-To: $email\r\n";
$headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
$sent = @mail($to, "WEBSKY enquiry: $subject", $body, $headers);
if ($sent) {
  echo '<!doctype html><html lang="en"><meta charset="utf-8"><meta name="viewport" content="width=device-width"><title>Message received | WEBSKY</title><link rel="stylesheet" href="assets/style.css"><main class="content"><div class="wrap prose"><h1>Thank you for contacting WEBSKY.</h1><p>Your message has been handed to the hosting mail service. This does not guarantee delivery; please contact us by email if you do not receive a reply.</p><a class="btn" href="index.html">Back to home</a></div></main></html>';
} else {
  http_response_code(500);
  echo 'We could not send your message from this server. Please email sopport@websky.com directly or ask your hosting provider to configure PHP mail/SMTP.';
}
?>
