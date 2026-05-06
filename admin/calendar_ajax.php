<?php
require_once "../database/db_connect.php";

header('Content-Type: application/json');
error_reporting(0);

$db = new DBController();

$query = "
SELECT b.booking_date, r.resource_name, t.label, b.status
FROM rt_bookings b
JOIN rt_resources r ON r.resource_id = b.resource_id
JOIN rt_time_slots t ON t.slot_id = b.slot_id
";

$result = $db->runQuery($query);

$events = [];

if(!empty($result)){
    foreach($result as $row){

        // 🎨 color based on status
        $color = "#ffc107"; // pending

        if($row['status'] == 'approved') $color = "#28a745";
        if($row['status'] == 'rejected') $color = "#dc3545";

        $events[] = [
            "title" => $row['resource_name']." (".$row['label'].")",
            "start" => $row['booking_date'],
            "color" => $color
        ];
    }
}

echo json_encode($events);