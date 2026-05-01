<?php
// Only process POST reqeusts.
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the form fields and remove whitespace.
    $fullname = strip_tags(trim($_POST["q-full-name"]));
    $fullname = str_replace(array("\r","\n"),array(" "," "),$fullname);
    $name = $fullname;
    $email = filter_var(trim($_POST["q-email"]), FILTER_SANITIZE_EMAIL);
    $phone = trim($_POST["q-phone"]);
    $depature = trim($_POST["q-depature"]);
    $destination = trim($_POST["q-destination"]);
    $weight = trim($_POST["q-weight"]);
    $freight_type = trim($_POST["q-freight-type"]);
    $message = trim($_POST["q-message"]);

    // Check that data was sent to the mailer.
    if ( empty($name) OR empty($phone) OR empty($message) OR empty($depature) OR empty($destination) OR empty($weight) OR empty($freight_type) OR !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // Set a 400 (bad request) response code and exit.
        http_response_code(400);
        echo "Oops! There was a problem with your submission. Please complete the form and try again.";
        exit;
    }

    // Update this to your desired email address.
    $recipient = "contact@yourdomain.com";
    $subject = "Message from $name";

    // Email content.
    $email_content = "Name: $name\n";
    $email_content .= "Email: $email\n\n";
    $email_content .= "Subject: $subject\n\n";
    $email_content .= "Phone: $phone\n\n";
    $email_content .= "Depature: $depature\n\n";
    $email_content .= "Destination: $destination\n\n";
    $email_content .= "Weight: $weight\n\n";
    $email_content .= "Freight Type: $freight_type\n\n";
    $email_content .= "Message: $message\n";

    // Email headers.
    $email_headers = "From: $name <$email>\r\nReply-to: <$email>";

    // Send the email.
    if (mail($recipient, $subject, $email_content, $email_headers)) {
        // Set a 200 (okay) response code.
        http_response_code(200);
        echo "Thank You! Your submission has been sent.";
    } else {
        // Set a 500 (internal server error) response code.
        http_response_code(500);
        echo "Oops! Something went wrong and we couldn't send your submission.";
    }

} else {
    // Not a POST request, set a 403 (forbidden) response code.
    http_response_code(403);
    echo "There was a problem with your submission, please try again.";
}