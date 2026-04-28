<?php
include('header.php');
include_once("../database/db_connect.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TCET</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        html, body {
            height: 100%;
            margin: 0;
            overflow: hidden;
        }

        body {
            background: #2c3e50;
            font-family: 'Arial', sans-serif;
            display: flex;
            justify-content: flex-end; /* Align items to the right */
            align-items: center; /* Center items vertically */
            padding-right: 70px;
        }

        /* 1. The Background Slider */
        .bg-slider {
            position: fixed;
            top: 0; left: 0;
            width: 100%; height: 100%;
            z-index: -1; 
            background-color: #2c3e50; /* Fallback color */
        }

        .bg-slide {
            position: absolute;
            top: 0; left: 0;
            width: 100%; height: 100%;
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            opacity: 0;
            animation: fadeAnimation 30s infinite;
        }

        /* 2. Animation Logic */
        @keyframes fadeAnimation {
           0% { opacity: 0; }
            25% { opacity: 1; }
            75% { opacity: 1; }
            100% { opacity: 0; }
        }

        /* 3. Assign images and delays */
        .img1 { background-image: url('../login/images/auditorium.jpeg'); animation-delay: 0s; }
        .img2 { background-image: url('../login/images/lab1.jpeg'); animation-delay: 5s; }
        .img3 { background-image: url('../login/images/seminar1.jpeg'); animation-delay: 10s; }
        .img4 { background-image: url('../login/images/lab2.jpeg'); animation-delay: 15s; }
        .img5 { background-image: url('../login/images/Seminar2.jpeg'); animation-delay: 20s; }
        .img6 { background-image: url('../login/images/lab3.jpeg'); animation-delay: 25s; }

        /* 4. Main Layout */
        /* .w3layouts-main {
            position: relative;
            z-index: 1;
            display: flex;
            justify-content: flex-end;
            align-items: center;
            height: 100%;
            width: 100%;
            margin-left: auto;  
        } */

        .bg-layer {
    width: 320px;
    height: 50%;              /* 👈 controls height */
    min-height: 420px;        /* prevents it from becoming too small */
    padding: 20px 18px;
    background: rgba(255,255,255,0.92);
    border-radius: 18px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.15);

    display: flex;            /* 👇 helps align content nicely */
    flex-direction: column;
    justify-content: center;  /* center content vertically */

    text-align: center;
    backdrop-filter: blur(12px);
}

       

        /* .header-main {
            background: #34495e;
            padding: 20px;
            border-radius: 10px;
        } */

        .main-icon{
    margin-top: -60px;
}

        .school-name {
    font-size: 20px;
    font-weight: 600;
    color: #6c4df6;
    margin-bottom: 5px;
}

        /* .header-left-bottom {
            margin-top: 20px;
        } */

         .subtitle {
    font-size: 13px;
    color: #666;
    margin-bottom: 15px;
}

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

        .login-check {
            text-align: left;
            margin-bottom: 15px;
        }

        .bottom {
            margin-top: 20px;
        }

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
.register {
    margin-top: 5px;
    font-size: 13px;
}
.register a {
    color: #5f2c82;
    text-decoration: none;
}
.register a:hover {
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
        position: relative;
        z-index: 1;
        padding: 25px;
        border-radius: 25px;

        background: rgba(255,255,255,0.18);
        backdrop-filter: blur(18px);
        -webkit-backdrop-filter: blur(18px);

        box-shadow: 0 8px 32px rgba(0,0,0,0.25);
    }
}

    </style>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
    <script src="script/validation.min.js"></script>
    <script src="script/login.js"></script>
    <script>
        addEventListener("load", function () {
            setTimeout(hideURLbar, 0);
        }, false);

        function hideURLbar() {
            window.scrollTo(0, 1);
        }
        function openRegister(){
            document.getElementById("loginBox").style.display = "none";
            document.getElementById("registerFrameBox").style.display = "block";
        }

        function showLogin(){
            document.getElementById("registerFrameBox").style.display = "none";
            document.getElementById("loginBox").style.display = "block";
        }
    </script>
</head>
<body>

<div class="bg-slider">
    <div class="bg-slide img1"></div>
    <div class="bg-slide img2"></div>
    <div class="bg-slide img3"></div>
    <div class="bg-slide img4"></div>
    <div class="bg-slide img5"></div>
    <div class="bg-slide img6"></div>
</div>


<div class="w3layouts-main"> 
    <div class="bg-layer"><br/><br/><br/><br/><br/>
        <div id="loginBox">
            <div class="main-icon">
                <img src="images/school_logo.jpg" alt="logo" width='150px' height='150px'>
            </div>
            <div class="school-name">
                Resource Tracker
            </div>
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
                    <div class="register">
                        <a href="javascript:void(0)" onclick="openRegister()">
                            New User? Register Here
                        </a>
                    </div>
                    
                    <div id="error"></div>
                </form> 
        </div>
        <div id="registerFrameBox" style="display:none;">
            <iframe 
                src="register.php"
                width="100%"
                height="650"
                frameborder="0"
                style="border:none; border-radius:15px;"
                style="margin-top:0;"
                style="display: flex;">
            </iframe>
        </div>
        <div id="registerContainer" style="display:none;"></div>
    </div>
 </div>
</body>
</html>
