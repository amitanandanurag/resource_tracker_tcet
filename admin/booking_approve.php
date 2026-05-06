<?php
require_once "../database/db_connect.php";
session_start();

$db = new DBController();

$id = $_POST['id'] ?? '';
$decision = $_POST['decision'] ?? '';
$user = $_SESSION['user_session'] ?? '';
$role = $_SESSION['role_id'] ?? 0;

// 🔒 SECURITY CHECK
if ($role != 1 && $role != 2) {
    echo "Access Denied";
    exit;
}

if (empty($id) || empty($decision)) {
    echo "Invalid Request";
    exit;
}

// UPDATE STATUS
$db->query("UPDATE rt_bookings SET status='$decision' WHERE booking_id='$id'");

// LOG APPROVAL
$db->query("
INSERT INTO rt_booking_approvals (booking_id, approver_user_id, decision)
VALUES ('$id','$user','$decision')
");

echo "Updated Successfully";