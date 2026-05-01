<?php
ob_start();
session_start();
require "../database/db_connect.php";
$db_handle = new DBController();

$message = "";
if (isset($_POST['register'])) {

    $first_name = mysqli_real_escape_string($db_handle->conn, $_POST['first_name']);
    $last_name = mysqli_real_escape_string($db_handle->conn, $_POST['last_name']);
    $email = mysqli_real_escape_string($db_handle->conn, $_POST['email']);
    $phone = mysqli_real_escape_string($db_handle->conn, $_POST['phone']);
    $department = mysqli_real_escape_string($db_handle->conn, $_POST['department']);
    $password = mysqli_real_escape_string($db_handle->conn, $_POST['password']);
    $confirm_password = mysqli_real_escape_string($db_handle->conn, $_POST['confirm_password']);

    $role_id = 5;
    $student_id = 0;

    if ($password !== $confirm_password) {
        $message = "Passwords do not match!";
    } elseif (strpos($email, "@tcetmumbai.in") === false) {
        $message = "Use institute email only!";
    } else {

        $check = $db_handle->query("SELECT * FROM rt_user_master WHERE email_id='$email'");

        if ($check && mysqli_num_rows($check) > 0) {
            $message = "Email already exists!";
        } else {

//$hashed_password = password_hash($password, PASSWORD_DEFAULT);

            $sql1 = "INSERT INTO rt_user_master 
            (first_name, last_name, email_id, phone_number, department_id, role_id, student_id)
            VALUES 
            ('$first_name','$last_name','$email','$phone','$department','$role_id','$student_id')";

            if ($db_handle->query($sql1)) {

                $user_id = mysqli_insert_id($db_handle->conn);

                $sql2 = "INSERT INTO rt_login(username,password,role_id,user_id)
                         VALUES('$email','$password','$role_id','$user_id')";

                if ($db_handle->query($sql2)) {

                    $_SESSION['user_id'] = $user_id;
                    $_SESSION['role_id'] = $role_id;
                    //header("Location: index.php");
                    //exit();
                    //echo "<script>
                       //alert('Registration Successful!');
                       //window.top.location.replace('index.php');
                   // </script>";
                    //exit();
                    echo "<script>window.top.location.href='index.php';</script>";
                    exit();

                } else {
                    $message = "Login Insert Error";
                }

            } else {
                $message = "User Insert Error";
            }
        }
    }
}
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
            html, body{
                overflow-y: auto;
            }
            body {
                margin: 0;
                padding: 0;
                font-family: 'Segoe UI', sans-serif;
                display: flex;
                justify-content: center;
                align-items: center;
                align-items: flex-start;  /* 👈 move to top */
                /*padding-top: 15px;*/
                
                background-color: #f5f6fa;
                background: transparent;
            }

            /* Main Container */
            .registerContainer {
                width: 650px;
                
                margin: 20px auto;
                padding: 18px;
                background: #ffffff;
                border: 1px solid #ddd;
                border-radius: 10px;
                box-shadow: 0 8px 20px rgba(0,0,0,0.1);
                text-align: center;
                
            }

            /* Logo */
            
            .logo-box{
                text-align:center;
                margin: 0 0 10px 0;
                margin-bottom: 15px;
            }

            .logo-img{
                width:100px;
                height:100px;
                object-fit:contain;
                display:block;
                margin:auto;
            }

            /* Heading */
            .heading h2 {
                text-align: center;
                margin: 5px 0 10px 0;
                color: #333;
                font-size: 20px;
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
            .dept{
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
        <div class="registerContainer">
            <div class="logo-box">
                <img src="/ResourceTracker/login/images/school_logo.jpg" alt="logo" width="100" height="100"/>
            </div>
            <div class="heading">
                <h2>Registration Form</h2>
            </div>
            <div class="main-form">
                <form id="register-form" method="post">
                    <div class="label1">
                        <label for="first_name">First Name</label>
                        <input type="text" placeholder="Enter your First Name" name="first_name" id="first_name" required /><br>
                    </div>
                    <div class="label1">
                        <label for="last_name">Last Name</label>
                        <input type="text" placeholder="Enter your Last Name" name="last_name" id="last_name" required /><br>
                    </div>
                    <div class="label1">
                        <label for="email">Email</label>
                        <input type="email" placeholder="Enter your Email Id" name="email" id="email" required /><br>
                    </div>
                    <div class="label1">
                        <label for="phone">Phone</label>
                        <input type="number" placeholder="Enter your Contact Number" name="phone" id="phone" required /><br>
                    </div>
                    <div class="label1">
                        <label for="department">Department</label>
                        <select name="department"  class="dept" required>
                        <option value="">SELECT DEPARTMENT</option>
                        <?php
                            $dept = $db_handle->query("SELECT department_id, department_name FROM rt_department_master");

                            if ($dept && $dept->num_rows > 0) {
                                while ($row = $dept->fetch_assoc()) {
                                    echo '<option value="' . $row['department_id'] . '">' . $row['department_name'] . '</option>';
                                }
                            } else {
                                echo '<option value="">No Departments Found</option>';
                            }
                            ?>
                        </select>

                    </div>
                    <div class="label1">
                        <label for="password">Password</label>
                        <input type="password" placeholder="Enter your Password" name="password" id="password" required /><br>
                    </div>
                    <div class="label1">
                        <label for="confirm_password">Confirm Password</label>
                        <input type="password" placeholder="Enter your Password" name="confirm_password" id="confirm_password" required /><br>
                    </div>
                    <button type="submit" class="submit-btn" name="register">Register</button>
                    <div style="margin-top:10px;">
                        <a href="javascript:parent.showLogin()">
                        Already have account? Login
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </body>
</html>