<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = htmlspecialchars($_POST["first_name"]);
    $last_name = htmlspecialchars($_POST["last_name"]);
    $email = htmlspecialchars($_POST["email"]);
    $phone = htmlspecialchars($_POST["phone"]);
    $position = htmlspecialchars($_POST["position"]);
    $experience = htmlspecialchars($_POST["experience"]);
    $skills = htmlspecialchars($_POST["skills"]);
    $qualification = htmlspecialchars($_POST["qualification"]);
    $location = htmlspecialchars($_POST["location"]);
    $resume = $_FILES["resume"];

    $to = "pradumnkalalp@gmail.com"; // Replace with your email
    $subject = "New Job Application - $first_name $last_name for $position";
    $headers = "From: $email\r\nReply-To: $email";

    // Email body
    $body = "First Name: $first_name\n";
    $body .= "Last Name: $last_name\n";
    $body .= "Email: $email\n";
    $body .= "Phone: $phone\n";
    $body .= "Position Applied: $position\n";
    $body .= "Experience: $experience years\n";
    $body .= "Skills: $skills\n";
    $body .= "Qualification: $qualification\n";
    $body .= "Location: $location\n\n";
    $body .= "Resume Attached Below.\n\n";

    // Handle file attachment
    if ($resume["error"] == 0) {
        $file_tmp = $resume["tmp_name"];
        $file_name = $resume["name"];
        $file_type = $resume["type"];
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
        echo "Thank you! Your application has been submitted.";
    } else {
        echo "Oops! Something went wrong.";
    }
}
?>
