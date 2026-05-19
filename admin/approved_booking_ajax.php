<?php
require_once "../database/db_connect.php";
session_start();

header('Content-Type: application/json');

$db = new DBController();

$query = "
SELECT b.booking_id,
       b.booking_date,
       r.resource_name,
       d.department_name,
       t.label
FROM rt_bookings b
JOIN rt_resources r ON r.resource_id = b.resource_id
JOIN rt_department_master d ON d.department_id = b.department_id
JOIN rt_time_slots t ON t.slot_id = b.slot_id
WHERE b.status='approved'
ORDER BY b.booking_id DESC
";

$result = $db->runQuery($query);

$data = [];

if (!empty($result)) {

    foreach ($result as $row) {

        $data[] = [
            "id" => $row['booking_id'],
            "resource" => $row['resource_name'],
            "department" => $row['department_name'],
            "date" => $row['booking_date'],
            "slot" => $row['label']
        ];

    }

}

echo json_encode(["data" => $data]);
exit;
?>