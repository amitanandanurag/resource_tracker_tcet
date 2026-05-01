<?php
session_start();
require "../database/db_connect.php";
$db_handle = new DBController();
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../phpmailer/src/Exception.php';
require '../phpmailer/src/PHPMailer.php';
require '../phpmailer/src/SMTP.php';
$message = "";
$messageType = "";
$link = "";

if (isset($_POST['reset_btn'])) {

    $email = mysqli_real_escape_string($db_handle->conn, $_POST['email']);

    if (strpos($email, "@tcetmumbai.in") === false) {
        $message = "🚫 Use institute email only!";
        $messageType = "error";
    } else {

        $check = $db_handle->query("SELECT user_id FROM rt_user_master WHERE email_id='$email'");

        if ($check && mysqli_num_rows($check) > 0) {

            $row = mysqli_fetch_assoc($check);
            $user_id = $row['user_id'];

            // 🔐 Generate token
            $token = bin2hex(random_bytes(50));

        // Save token
        $db_handle->query("UPDATE rt_login SET reset_token='$token' WHERE user_id='$user_id'");

        // Create reset link
        $link = "http://localhost/ResourceTracker/login/reset_password.php?token=$token";

        // ✅ SEND EMAIL USING SMTP
        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;

            // 🔴 YOUR EMAIL HERE
            $mail->Username   = 'chaudharihindavi12@gmail.com';
            $mail->Password   = 'psni rhhq igky msiw'; // NOT your Gmail password

            $mail->SMTPSecure = 'tls';
            $mail->Port       = 587;

            $mail->setFrom('chaudharihindavi12@gmail.com', 'Resource Tracker');
            $mail->addAddress($email);

            $mail->isHTML(true);
            $mail->Subject = 'Password Reset Link';

            $mail->Body = "
                <h3>Password Reset Request</h3>
                <p>Click the link below to reset your password:</p>
                <a href='$link'>$link</a>
                <br><br>
                <small>This link will expire soon.</small>
            ";

            $mail->send();

            $message = "✅ Reset link sent to your email!";
            $messageType = "success";

        } catch (Exception $e) {
            $message = "❌ Mail could not be sent. Error: {$mail->ErrorInfo}";
            $messageType = "error";
        }
    }
}
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Forgot Password</title>
<style>
    /* RESET */
html, body{
    margin: 0;
    padding: 0;
    height: 100%;
    box-sizing: border-box;
    font-family: 'Segoe UI', sans-serif;
    overflow: hidden;
}

body {
    height: 100vh;
    display: flex;
    justify-content: flex-start;
    align-items: center;
    padding-right: 70px;
    
}

/* BACKGROUND SLIDER */
.bg-slider {
    position: fixed;
    width: 100vw;
    height: 100vh;
    z-index: -1;
    background-color: #2c3e50; /* Fallback color */
    margin: 0;
    padding: 0;
}

.bg-slide {
    position: absolute;
    top: 0; left: 0;
    width: 100%;
    height: 100%;
    background-size: cover;
    background-position: center;
    opacity: 0;
    animation: fade 30s infinite;
}

.img1 { background-image: url('images/auditorium.jpeg'); }
.img2 { background-image: url('images/lab1.jpeg'); animation-delay: 6s; }
.img3 { background-image: url('images/seminar1.jpeg'); animation-delay: 12s; }

@keyframes fade {
    0% { opacity: 0; }
    25% { opacity: 1; }
    75% { opacity: 1; }
    100% { opacity: 0; }
}

/* CARD */
.container {
    display: flex;
    justify-content: flex-end;
    width: 100%;
}

.card {
    width: 320px;
    min-height: 380px;
    padding: 25px;
    border-radius: 18px;
    background: rgba(255,255,255,0.55);
    backdrop-filter: blur(15px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.2);
    text-align: center;
}

/* TEXT */
h2 {
    color: #6c4df6;
    margin-bottom: 10px;
}

.subtitle {
    font-size: 13px;
    color: #555;
    margin-bottom: 20px;
}

/* INPUT */
input {
    width: 90%;
    padding: 10px;
    margin-bottom: 15px;
    border-radius: 8px;
    border: 1px solid rgba(0,0,0,0.2);
    outline: none;
}

input:focus {
    border-color: #6c4df6;
    box-shadow: 0 0 5px rgba(108,77,246,0.3);
}

/* BUTTON */
button {
    width: 100%;
    padding: 10px;
    border: none;
    border-radius: 10px;
    background: linear-gradient(135deg, #5f2c82, #8f00ff);
    color: white;
    font-size: 14px;
    cursor: pointer;
}

button:hover {
    opacity: 0.9;
}

/* LINKS */
.back {
    margin-top: 12px;
    font-size: 13px;
}

.back a {
    color: #5f2c82;
    text-decoration: none;
}

.back a:hover {
    text-decoration: underline;
}

/* MESSAGES */
.success {
    color: green;
    margin-top: 10px;
}

.error {
    color: red;
    margin-top: 10px;
}
</style>
</head>
<body>

<div class="bg-slider">
    <div class="bg-slide img1"></div>
    <div class="bg-slide img2"></div>
    <div class="bg-slide img3"></div>
</div>

<div class="container">
    <div class="card">
        <h2>Forgot Password</h2>
        <p class="subtitle">Enter your institute email</p>

        <form method="POST">
            <input type="email" name="email" placeholder="Enter email" required>
            <button type="submit" name="reset_btn">Send Reset Link</button>
        </form>

        <div class="back">
            <a href="index.php">← Back to Login</a>
        </div>

        <?php if($message != "") { ?>
            <p class="<?= $messageType ?>"><?= $message ?></p>
        <?php } ?>
    </div>
</div>

</body>
</html>