<?php
include('header.php');
include_once("../database/dbconnect.php");
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Registration Page</title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
        <script src="script/validation.min.js"></script>
        <style>

            body {
                margin: 0;
                font-family: 'Segoe UI', sans-serif;
                display: flex;
                justify-content: center;
                align-items: center;
                height: 100vh;
                background-color: #f5f6fa;
            }

            /* Main Container */
            .container {
                width: 350px;
                padding: 25px 20px;
                background: #ffffff;
                border: 1px solid #ddd;
                border-radius: 10px;
                box-shadow: 0 8px 20px rgba(0,0,0,0.1);
                text-align: center;
            }

            /* Logo */
            .logo img {
                margin-bottom: 10px;
            }

            /* Heading */
            .heading h2 {
                margin-bottom: 20px;
                color: #333;
            }

            /* Form Fields */
            .label1 {
                text-align: left;
                margin-bottom: 12px;
            }

            .label1 label {
                font-size: 14px;
                display: block;
                margin-bottom: 5px;
                color: #333;
            }

            .label1 input {
                width: 95%;
                padding: 8px;
                font-size: 13px;
                border: 1px solid #ccc;
                border-radius: 5px;
                outline: none;
            }

            /* Focus Effect */
            .label1 input:focus {
                border-color: #6c4df6;
                box-shadow: 0 0 4px rgba(108,77,246,0.3);
            }

            /* Button */
            .submit-btn {
                width: 100%;
                margin-top: 15px;
                padding: 10px;
                border: none;
                border-radius: 6px;
                background: #6c4df6;
                color: white;
                font-size: 14px;
                cursor: pointer;
            }

            .submit-btn:hover {
                background: #5937d4;
            }

        </style>

    </head>
    <body>
        <div class="container">
            <div class="logo">
                <img src="images/school_logo.jpg" alt="logo" width="100" height="100"/>
            </div>
            <div class="heading">
                <h2>Registration Form</h2>
            </div>
            <div class="main-form">
                <form id="register-form" method="post">
                    <div class="label1">
                        <label for="firstname">First Name</label>
                        <input type="text" placeholder="Enter your First Name" name="firstname" id="firstname" required /><br>
                    </div>
                    <div class="label1">
                        <label for="lastname">Last Name</label>
                        <input type="text" placeholder="Enter your Last Name" name="lastname" id="lastname" required /><br>
                    </div>
                    <div class="label1">
                        <label for="email">Email</label>
                        <input type="email" placeholder="Enter your Email Id" name="email" id="email" required /><br>
                    </div>
                    <div class="label1">
                        <label for="contact">Contact</label>
                        <input type="number" placeholder="Enter your Contact Number" name="contact" id="contact" required /><br>
                    </div>
                    <div class="label1">
                        <label for="password">Password</label>
                        <input type="password" placeholder="Enter your Password" name="password" id="password" required /><br>
                    </div>
                    <div class="label1">
                        <label for="confirm-password">Confirm Password</label>
                        <input type="password" placeholder="Enter your Password" name="confirm-password" id="confirm-password" required /><br>
                    </div>
                    <button type="submit" class="submit-btn">Submit</button>
                </form>
            </div>
        </div>
    </body>
</html>