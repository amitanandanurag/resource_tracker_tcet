<?php
require_once "../database/db_connect.php";

header('Content-Type: application/json');
error_reporting(0);

$db = new DBController();

// 🔥 FETCH ONLY CANCELLED
$query = "
SELECT b.booking_id, b.booking_date, b.status,
       r.resource_name,
       t.label
FROM rt_bookings b
JOIN rt_resources r ON r.resource_id = b.resource_id
JOIN rt_time_slots t ON t.slot_id = b.slot_id
WHERE b.status = 'cancelled'
";

$result = $db->runQuery($query);

$data = [];

if(!empty($result)){
    foreach($result as $row){

        // 🎨 status badge
        $status = "<span class='label label-danger'>Cancelled</span>";

        $data[] = [
            "id" => $row['booking_id'],
            "resource" => $row['resource_name'],
            "date" => $row['booking_date'],
            "slot" => $row['label'],
            "status" => $status
        ];
    }
}

echo json_encode(["data"=>$data]);
exit;