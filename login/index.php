<?php
include('header.php');
include_once("../database/db_connect.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Resource Tracker</title>

<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<style>

html, body {
    height: 100%;
    margin: 0;
    font-family: 'Segoe UI', sans-serif;
}

/* Background Image */
body {
    display: flex;
    position: relative;
    background: url('images/background.jpg') no-repeat center center;
    background-size: cover;
}

/* RIGHT LOGIN PANEL */
.w3layouts-main {
    width: 30%;              /* stays on right */
    height: 100%;
    margin-left: auto;       /* pushes it to right */

    display: flex;
    justify-content: center; /* center card horizontally */
    align-items: center;     /* center card vertically */
}

/* LOGIN CARD */
.bg-layer {
    width: 320px;
    height: 50%;              /* 👈 controls height */
    min-height: 420px;        /* prevents it from becoming too small */
    padding: 20px 18px;
    background: #ffffff;
    border-radius: 15px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.2);

    display: flex;            /* 👇 helps align content nicely */
    flex-direction: column;
    justify-content: center;  /* center content vertically */

    text-align: center;
    backdrop-filter: blur(12px);
}
.main-icon{
    margin-top: -60px;
}

/* TITLE */
.school-name {
    font-size: 20px;
    font-weight: 600;
    color: #6c4df6;
    margin-bottom: 5px;
}

.subtitle {
    font-size: 13px;
    color: #666;
    margin-bottom: 15px;
}

/* INPUT GROUP */
.icon1 {
    margin-top: -15px;
    margin-bottom: 12px;
    text-align: left;
}

.icon1 label {
    font-size: 14px;
    font-weight: 500;
    display: block;
    margin-bottom: 6px;
}

.icon1 input {
    width: 100%;
    padding: 8px;
    border: 1px solid #ddd;
    border-radius: 8px;
    font-size: 13px;
    outline: none;
    transition: 0.3s;
}

.icon1 input:focus {
    border-color: #6c4df6;
    box-shadow: 0 0 5px rgba(108,77,246,0.3);
}

/* BUTTON */
.btn {
    width: 100%;
    background: linear-gradient(135deg, #5f2c82, #8f00ff);
    color: white;
    border: none;
    padding: 10px;
    border-radius: 10px;
    font-size: 14px;
    cursor: pointer;
    transition: 0.3s;
}

.btn:hover {
    opacity: 0.9;
}

/* FORGOT PASSWORD */
.forgot {
    margin-top: 10px;
    font-size: 13px;
}

.forgot a {
    color: #5f2c82;
    text-decoration: none;
}

.forgot a:hover {
    text-decoration: underline;
}

/* ERROR */
#error {
    color: red;
    font-size: 13px;
    margin-top: 10px;
}

/* RESPONSIVE */
@media (max-width: 768px) {
    body::before {
        width: 100%;
    }

    .w3layouts-main {
        width: 100%;
        background: rgba(255,255,255,0.95);
    }
}

</style>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script src="script/validation.min.js"></script>
<script src="script/login.js"></script>

</head>

<body>

<div class="w3layouts-main">
    <div class="bg-layer"><br/><br/><br/><br/><br/>
    
        <div class="main-icon">
            <img src="images/school_logo.jpg" alt="logo" width='120px' height='120px'>
        </div>
        <div class="school-name">Resource Tracker</div>
        <div class="subtitle">Sign in to your account</div>
    
        <form id="login-form" method="post">

            <div class="icon1">
                <label>Username</label>
                <input type="text" placeholder="Enter your username" name="username" id="username" required>
            </div>

            <div class="icon1">
                <label>Password</label>
                <input type="password" placeholder="Enter your password" name="password" id="password" required>
            </div>

            <button type="submit" class="btn" name="login_button" id="login_button">Login</button>

            <div class="forgot">
                <a href="#">Forgot password?</a>
            </div>

            <div id="error"></div>

        </form>

    </div>
</div>

</body>
</html>
