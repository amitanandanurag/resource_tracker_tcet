<?php
require_once "../database/db_connect.php";
session_start();

$db = new DBController();

// =======================
// SESSION CHECK
// =======================
$user = $_SESSION['user_session'] ?? '';

if(empty($user)){
    echo "<script>alert('Session expired. Please login again'); window.location='../login/index.php';</script>";
    exit;
}

// =======================
// GET FORM DATA
// =======================
$dept      = $_POST['department_id'] ?? '';
$class     = $_POST['class_id'] ?? '';
$division  = $_POST['division_id'] ?? '';
$date      = $_POST['booking_date'] ?? '';
$slot      = $_POST['slot_id'] ?? '';
$res_id    = $_POST['resource_id'] ?? '';
$purpose   = $_POST['purpose'] ?? '';

// =======================
// BASIC VALIDATION
// =======================
if(empty($dept) || empty($class) || empty($division) || empty($date) || empty($slot) || empty($res_id)){
    echo "<script>alert('All fields are required'); history.back();</script>";
    exit;
}

// =======================
// CHECK SLOT EXISTS
// =======================
$slotCheck = $db->numRows("SELECT * FROM rt_time_slots WHERE slot_id='$slot'");

if($slotCheck == 0){
    echo "<script>alert('Invalid Slot Selected'); history.back();</script>";
    exit;
}

// =======================
// 🔥 CHECK ACTIVE BOOKINGS
// =======================
$activeCheck = $db->numRows("
SELECT * FROM rt_bookings
WHERE resource_id='$res_id'
AND booking_date='$date'
AND slot_id='$slot'
AND status IN ('pending','approved')
");

if($activeCheck > 0){
    echo "<script>alert('This slot is already booked'); history.back();</script>";
    exit;
}

// =======================
// 🔥 CHECK CANCELLED / REJECTED (REUSE)
// =======================
$existing = $db->runQuery("
SELECT booking_id FROM rt_bookings
WHERE resource_id='$res_id'
AND booking_date='$date'
AND slot_id='$slot'
AND status IN ('cancelled','rejected')
LIMIT 1
");

if(!empty($existing)){

    $booking_id = $existing[0]['booking_id'];

    $db->query("
    UPDATE rt_bookings
    SET status='pending',
        booked_by_user_id='$user',
        department_id='$dept',
        class_id='$class',
        division_id='$division',
        purpose='$purpose'
    WHERE booking_id='$booking_id'
    ");

    echo "<script>alert('Booking Re-created Successfully'); window.location='my_bookings.php';</script>";
    exit;
}

// =======================
// INSERT NEW BOOKING
// =======================
$sql = "INSERT INTO rt_bookings
(resource_id, booked_by_user_id, department_id, class_id, division_id, booking_date, slot_id, purpose)
VALUES
('$res_id','$user','$dept','$class','$division','$date','$slot','$purpose')";

// =======================
// SAFE INSERT
// =======================
try {
    $result = $db->executeInsert($sql);

    if($result){
        echo "<script>alert('Booking Successful'); window.location='my_bookings.php';</script>";
    } else {
        echo "<script>alert('Error while booking'); history.back();</script>";
    }

} catch (Exception $e) {

    if(strpos($e->getMessage(), 'Duplicate entry') !== false){
        echo "<script>alert('This slot is already booked'); history.back();</script>";
    } else {
        echo "<script>alert('Something went wrong'); history.back();</script>";
    }
}
?>