<?php
session_start();
require "../database/db_connect.php";
$db_handle = new DBController();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  header('Location: ' . $infoFile);
  exit;
}

$userId = intval($_POST['user_id'] ?? 0);
$firstName = trim($_POST['first_name'] ?? '');
$lastName = trim($_POST['last_name'] ?? '');
$emailId = trim($_POST['email_id'] ?? '');
$phoneNumber = trim($_POST['phone_number'] ?? '');
$departmentId = intval($_POST['department_id'] ?? 0);

if ($firstName === '' || $lastName === '' ||  $emailId === '' || $departmentId <= 0) {
  echo "<script>alert('Please fill all required fields.'); window.history.back();</script>";
  exit;
}

$firstNameEsc = mysqli_real_escape_string($db_handle->conn, $firstName);
$lastNameEsc = mysqli_real_escape_string($db_handle->conn, $lastName);
$emailEsc = mysqli_real_escape_string($db_handle->conn, $emailId);
$phoneEsc = mysqli_real_escape_string($db_handle->conn, $phoneNumber);

if ($userId > 0) {
  $dupSql = "SELECT user_id FROM rt_user_master WHERE email_id = '$emailEsc' AND user_id != $userId LIMIT 1";
} else {
  $dupSql = "SELECT user_id FROM rt_user_master WHERE email_id = '$emailEsc' LIMIT 1";
}
$dupResult = $db_handle->query($dupSql);
if ($dupResult && $dupResult->num_rows > 0) {
  echo "<script>alert('Email already exists.'); window.history.back();</script>";
  exit;
}

if ($userId > 0) {
  $sql = "UPDATE rt_user_master SET first_name='$firstNameEsc', last_name='$lastNameEsc', email_id='$emailEsc', phone_number='$phoneEsc', department_id=$departmentId WHERE user_id=$userId AND role_id=" . intval($roleId);
} else {
  $sql = "INSERT INTO rt_user_master (first_name, last_name, email_id, password, phone_number, department_id, role_id, student_id) VALUES ('$firstNameEsc', '$lastNameEsc', '$emailEsc', 'TCET@1234', '$phoneEsc', $departmentId, " . intval($roleId) . ", 0)";
  $db_handle->query($sql);

    // Step B: Get the new user_id from database
  $newUserId = mysqli_insert_id($db_handle->conn);
  
  $sqlLogin = "INSERT INTO rt_login (username, password, role_id, user_id, created_at) 
                 VALUES ('$emailEsc', 'TCET@1234', $roleId, $newUserId, NOW())";
  $db_handle->query($sqlLogin);
}

// $db_handle->query($sql);
header('Location: ' . $infoFile);
exit;
?>
