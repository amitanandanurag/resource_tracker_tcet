<?php
require_once "../database/db_connect.php";
session_start();

$db = new DBController();

$id = $_POST['id'] ?? '';
$user = $_SESSION['user_session'] ?? '';

if(empty($id)){
    echo "Invalid Request";
    exit;
}

// 🔥 Only owner can cancel
$check = $db->numRows("
SELECT * FROM rt_bookings 
WHERE booking_id='$id' 
AND booked_by_user_id='$user'
");

if($check == 0){
    echo "Not Allowed";
    exit;
}

// UPDATE STATUS
$db->query("UPDATE rt_bookings SET status='cancelled' WHERE booking_id='$id'");

// OPTIONAL: log cancellation
$db->query("
INSERT INTO rt_booking_cancellations (booking_id, cancelled_by_user_id)
VALUES ('$id','$user')
");

echo "Booking Cancelled";