<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = htmlspecialchars($_POST["name"]);
    $mobile = htmlspecialchars($_POST["mobile"]);
    $email = htmlspecialchars($_POST["email"]);
    $message = htmlspecialchars($_POST["message"]);
    $file = $_FILES["attachment"];

    $to = "pradumnkalalp@gmail.com"; // Replace with your email
    $subject = "New Contact Form Submission from $name";
    $headers = "From: $email\r\nReply-To: $email";

    // Email body
    $body = "Name: $name\n";
    $body .= "Mobile: $mobile\n";
    $body .= "Email: $email\n\n";
    $body .= "Message:\n$message\n\n";

    // Handle file attachment
    if ($file["error"] == 0) {
        $file_tmp = $file["tmp_name"];
        $file_name = $file["name"];
        $file_type = $file["type"];
        $file_content = chunk_split(base64_encode(file_get_contents($file_tmp)));

        $boundary = md5(time());

        // Update headers for attachment
        $headers .= "\nMIME-Version: 1.0\r\n";
        $headers .= "Content-Type: multipart/mixed; boundary=$boundary\r\n";

        // Message with attachment
        $message = "--$boundary\n";
        $message .= "Content-Type: text/plain; charset=UTF-8\n\n";
        $message .= $body . "\n";
        $message .= "--$boundary\n";
        $message .= "Content-Type: $file_type; name=\"$file_name\"\n";
        $message .= "Content-Disposition: attachment; filename=\"$file_name\"\n";
        $message .= "Content-Transfer-Encoding: base64\n\n";
        $message .= $file_content . "\n";
        $message .= "--$boundary--";

        // Send email with attachment
        $success = mail($to, $subject, $message, $headers);
    } else {
        // Send email without attachment
        $success = mail($to, $subject, $body, $headers);
    }

    if ($success) {
        echo "Thank you! Your message has been sent.";
    } else {
        echo "Oops! Something went wrong.";
    }
}
?>
