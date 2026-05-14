<?php

require_once "../database/db_connect.php";
session_start();

$db = new DBController();

$date = $_POST['booking_date'];

$user_id = $_SESSION['user_session'];
$role_id = $_SESSION['role_id'];

/* ===================================
   USER DEPARTMENT
=================================== */

$userData = $db->runQuery("
SELECT department_id
FROM rt_user_master
WHERE user_id='$user_id'
");

$department_id = $userData[0]['department_id'] ?? 0;

/* ===================================
   ROLE FILTER
=================================== */

$where = "";

if($role_id != 1 && $role_id != 2){

    $where = " AND b.department_id='$department_id'";
}

/* ===================================
   QUERY
=================================== */

$query = "
SELECT 
    r.resource_name,
    d.department_name,
    t.label,
    b.status

FROM rt_bookings b

JOIN rt_resources r
ON r.resource_id = b.resource_id

JOIN rt_department_master d
ON d.department_id = b.department_id

JOIN rt_time_slots t
ON t.slot_id = b.slot_id

WHERE b.booking_date='$date'

$where

ORDER BY t.start_time ASC
";

$result = $db->runQuery($query);

if(empty($result)){

    echo "<h4>No bookings found</h4>";
    exit;
}

foreach($result as $row){

    if($row['status'] == 'approved'){
        $color = "#28a745";
    }
    else if($row['status'] == 'pending'){
        $color = "#ffc107";
    }
    else{
        $color = "#dc3545";
    }

    echo "
    <div class='booking-item' style='border-left:4px solid $color'>
        <h4>".$row['resource_name']."</h4>

        <p><b>Department:</b> ".$row['department_name']."</p>

        <p><b>Slot:</b> ".$row['label']."</p>

        <p><b>Status:</b> ".strtoupper($row['status'])."</p>
    </div>
    ";
}
?>