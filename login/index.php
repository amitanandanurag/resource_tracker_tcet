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
        .w3layouts-main {
            position: relative;
            z-index: 1;
            display: flex;
            justify-content: flex-end;
            align-items: center;
            height: 100%;
            width: 100%;
        }

        .bg-layer {
            background: rgba(0, 0, 0, 0.7);
            padding: 40px;
            border-radius: 10px;
            color: #fff;
        }

        .bg-layer {
            background: rgba(0, 0, 0, 0.7);
            padding: 20px 40px; /* Reduced vertical padding */
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
            text-align: center;
            color: #fff;
            margin: 20px 0; /* Adds space above and below the .bg-layer */
        }

        .header-main {
            background: #34495e;
            padding: 20px;
            border-radius: 10px;
        }

        .main-icon img {
            border-radius: 50%;
            margin-bottom: 10px;
        }

        .school-name {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 20px;
            font-family: 'Lucida Sans Unicode', sans-serif;
        }

        .header-left-bottom {
            margin-top: 20px;
        }

        .icon1 {
            margin-bottom: 15px;
            position: relative;
        }

        .icon1 input {
            width: 100%;
            padding: 10px;
            padding-left: 35px;
            border: none;
            border-radius: 5px;
            box-sizing: border-box;
        }

        .icon1 i {
            position: absolute;
            left: 10px;
            top: 50%;
            transform: translateY(-50%);
            color: #34495e;
        }

        .login-check {
            text-align: left;
            margin-bottom: 15px;
        }

        .bottom {
            margin-top: 20px;
        }

        .btn {
            background: #1abc9c;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.3s;
        }

        .btn:hover {
            background: #16a085;
        }

        .copyright {
            margin-top: 10px;
        }

        .copyright a {
            color: #ED2C02; /* Bright red */
        }

        .alert {
            padding: 20px;
            background-color: #f44336;
            color: white;
            margin-bottom: 20px;
            border-radius: 5px;
        }

        .closebtn {
            margin-left: 15px;
            color: white;
            font-weight: bold;
            float: right;
            font-size: 22px;
            line-height: 20px;
            cursor: pointer;
            transition: 0.3s;
        }

        .closebtn:hover {
            color: black;
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
        <div class="header-main">
            <div class="main-icon">
                <img src="images/school_logo.jpg" alt="logo" width='150px' height='150px'>
            </div>
            <div class="school-name">
                SPECIALIZATION TRACKER
            </div>
            <div class="header-left-bottom">
                <form id="login-form" method="post">
                    <div class="icon1">
                        <i class="fa fa-user"></i>
                        <input type="text" placeholder="Enter username" name="username" id="username" required=""/>
                    </div>
                    <div class="icon1">
                        <i class="fa fa-lock"></i>
                        <input type="password" placeholder="Enter password" name="password" id="password" required=""/>
                    </div>
                    <div class="login-check">
                        <label class="checkbox">
                            <input type="checkbox" name="checkbox" checked="">
                            <i></i> Keep me logged in
                        </label>
                    </div>
                    <div id="error" style="color:red;"></div>
                    <div class="bottom">
                        <button type="submit" class="btn" name="login_button" id="login_button">Log In</button>
                    </div>
                </form> 
            </div>
        </div>

        <div class="copyright">
            <p>© 2019. All rights reserved | Designed by <a href="https://dignityitsolution.com/" target="_blank">Dignity IT Solution</a></p>
        </div>
    </div>
</div>


</body>
</html>
