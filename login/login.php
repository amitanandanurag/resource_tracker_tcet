<?php
session_start();
include_once("../database/db_connect.php");

if(isset($_POST['login_button'])) {

try {
    $db_handle = new DBController();
} catch (Throwable $e) {
    echo "Unable to connect with database";
    exit();
}

$username = trim($_POST['username']);
$user_password = trim($_POST['password']);

$sql = "SELECT * FROM rt_login WHERE username=?";
$stmt = mysqli_prepare($db_handle->conn, $sql);

mysqli_stmt_bind_param($stmt, 's', $username);
mysqli_stmt_execute($stmt);
$resultset = mysqli_stmt_get_result($stmt);

$row = mysqli_fetch_assoc($resultset);

if (!$row) {
    echo "email or password does not exist.";
    exit();
}

// PASSWORD CHECK
if($row['password'] == $user_password){

    // ✅ FIXED SESSION
    // $_SESSION['user_session'] = $row['user_id'];
    // $_SESSION['role_id'] = $row['role_id'];  // 🔥 IMPORTANT

    $_SESSION['user_session'] = $row['user_id'];
    $_SESSION['user_id']      = $row['login_id'];
    $_SESSION['role_id']      = $row['role_id'];

    ob_clean();
    // REDIRECTION RESPONSE
    if($row['role_id'] == 2 && $user_password == "TCET@1234")
    {
        ob_clean();
        echo "update";
        
    }
    else if($row['role_id'] == 1){
        echo "ok";
    }
    else if($row['role_id'] == 2){
        echo "ok1";
    }
    if($row['role_id'] == 3 && $user_password == "TCET@1234")
    {
        ob_clean();
        echo "update";
        
    }
    else if($row['role_id'] == 3){
        echo "ok2";
    }
    else if($row['role_id'] == 4){
        echo "ok3";
    }
    else if($row['role_id'] == 5){
        echo "ok4";
    }

} else {
    echo "email or password does not exist.";
}
}
?>