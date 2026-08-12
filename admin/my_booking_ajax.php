<?php
require_once "../database/db_connect.php";
session_start();

header('Content-Type: application/json');
error_reporting(0);

$db = new DBController();

// 🔥 YOUR SESSION
$user = $_SESSION['user_session'];

$query = "
SELECT b.booking_id, b.booking_date, b.status,
       r.resource_name,
       t.label
FROM rt_bookings b
JOIN rt_resources r ON r.resource_id = b.resource_id
JOIN rt_time_slots t ON t.slot_id = b.slot_id
WHERE b.booked_by_user_id = '$user'
";

$result = $db->runQuery($query);

$data = [];

if(!empty($result)){
    foreach($result as $row){

        $action = "<button onclick='cancel(".$row['booking_id'].")' class='btn btn-danger btn-sm'>Cancel</button>";
        $status = trim(strtolower($row['status']));

        if($status == "underprocess"){
            $statusBtn = "<span class='btn btn-warning btn-sm'>Under Process</span>";
        }
        elseif($status == "approved"){
            $statusBtn = "<span class='btn btn-success btn-sm'>Approved</span>";
        }
        elseif($status == "rejected"){
            $statusBtn = "<span class='btn btn-danger btn-sm'>Rejected</span>";
        }
        elseif($status == "cancelled"){
            $statusBtn = "<span class='btn btn-default btn-sm'>Cancelled</span>";
        }
        else{
            $statusBtn = $row['status'];
        }
        $data[] = [
            "id" => $row['booking_id'],
            "resource" => $row['resource_name'],
            "date" => $row['booking_date'],
            "slot" => $row['label'],
            "status" => $statusBtn,
            "action" => $action
        ];
    }
}

echo json_encode(["data"=>$data]);
exit;