<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer-master/src/PHPMailer.php';
require 'PHPMailer-master/src/SMTP.php';
require 'PHPMailer-master/src/Exception.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = htmlspecialchars($_POST["name"]);
    $email = htmlspecialchars($_POST["email"]);
    $message = htmlspecialchars($_POST["message"]);
    
    $admin_email = "donorenge001@gmail.com"; // Change to your actual admin email
    $subject = "Access Request from $name";

    $mail = new PHPMailer(true);
    
    try {
        // SMTP Configuration
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; // Gmail SMTP server
        $mail->SMTPAuth = true;
        $mail->Username = 'donorenge001@gmail.com'; // Your Gmail address
        $mail->Password = 'qlqq jatc vksk itus'; // Generate an App Password (DO NOT USE your real password)
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Email Details
        $mail->setFrom($email, $name);
        $mail->addAddress($admin_email);
        $mail->Subject = $subject;
        $mail->Body = "Name: $name\nEmail: $email\nMessage:\n$message";

        // Send Email
        if ($mail->send()) {
            // Redirect back to dashboard.php after successful email
            header("Location: dashboard.php");
            exit(); // Ensure script stops execution after redirection
        } else {
            echo "Error sending email.";
        }
    } catch (Exception $e) {
        echo "Error sending email: {$mail->ErrorInfo}";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f8f9fa;
        }
        .container {
            width: 80%;
            margin: auto;
            overflow: hidden;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .contact-info, .contact-form {
            width: 48%;
            float: left;
            padding: 10px;
        }
        .contact-info i {
            font-size: 20px;
            color: #007bff;
            margin-right: 10px;
        }
        .contact-form input, .contact-form textarea {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .contact-form button {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
            cursor: pointer;
        }
        .success, .error {
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
        }
        .success {
            background-color: #d4edda;
            color: #155724;
        }
        .error {
            background-color: #f8d7da;
            color: #721c24;
        }
        iframe {
            width: 100%;
            height: 200px;
            border: none;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="contact-info">
            <h3><i class="fas fa-map-marker-alt"></i> Address</h3>
            <p>Nyanchwa Street, Kisii, 40200-69</p>
            <h3><i class="fas fa-phone"></i> Call Us</h3>
            <p>+254 796 414 456</p>
            <h3><i class="fas fa-envelope"></i> Email Us</h3>
            <p>Donorenge001@gmail.com</p>
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3023.8502659983544!2d-74.0132332845939!3d40.71277607933006!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x89c25a31682e99fd%3A0x9b04345b6b992cae!2sDowntown%20Conference%20Center!5e0!3m2!1sen!2sus!4v1612993442154!5m2!1sen!2sus" allowfullscreen></iframe>
        </div>
        <div class="contact-form">
            <h3>Contact Us</h3>
            <?php if (isset($msg)) : ?>
                <div class="<?= strpos($msg, 'successfully') !== false ? 'success' : 'error' ?>">
                    <?= $msg; ?>
                </div>
            <?php endif; ?>
            <form action="" method="post">
                <input type="text" name="name" placeholder="Your Name" required>
                <input type="email" name="email" placeholder="Your Email" required>
                <input type="text" name="subject" placeholder="Subject" required>
                <textarea name="message" placeholder="Message" rows="5" required></textarea>
                <button type="submit">Send Message</button>
            </form>
        </div>
        <div style="clear: both;"></div>
    </div>
</body>
</html>
