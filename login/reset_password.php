<?php
require "../database/db_connect.php";
$db_handle = new DBController();

if (!isset($_GET['token'])) {
    die("Invalid request");
}

$token = $_GET['token'];

$check = $db_handle->query("SELECT user_id FROM rt_login WHERE reset_token='$token'");

if (!$check || mysqli_num_rows($check) == 0) {
    die("Invalid or expired link");
}

$row = mysqli_fetch_assoc($check);
$user_id = $row['user_id'];

if (isset($_POST['update_btn'])) {

    $password = $_POST['password'];

    // ❗ No hashing (as you requested)
    $db_handle->query("UPDATE rt_login SET password='$password', reset_token=NULL WHERE user_id='$user_id'");

    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Reset Password</title>
<style>
    /* RESET */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Segoe UI', sans-serif;
}

body {
    height: 100vh;
    display: flex;
    justify-content: flex-start;
    align-items: center;
    padding-right: 70px;
    overflow: hidden;
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
    animation: fade 18s infinite;
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
    width: 100%;
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
        <h2>Reset Password</h2>
        <p class="subtitle">Set your new password</p>

        <form method="POST">
            <input type="password" name="password" placeholder="New password" required>
            <button type="submit" name="update_btn">Update Password</button>
        </form>
    </div>
</div>

</body>
</html>
