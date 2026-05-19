<?php
require_once "../database/db_connect.php";
session_start();

header('Content-Type: application/json');

error_reporting(0);

$db = new DBController();

$user = $_SESSION['user_session'] ?? '';

if (empty($user)) {
    echo json_encode(["data" => []]);
    exit;
}

/* ===================================
   GET USER DEPARTMENT
=================================== */

$userData = $db->runQuery("
SELECT department_id
FROM rt_user_master
WHERE user_id='$user'
LIMIT 1
");

/* ===================================
   USER NOT FOUND
=================================== */

if (empty($userData)) {

    echo json_encode(["data" => []]);
    exit;
}

$department_id = $userData[0]['department_id'];

/* ===================================
   FETCH DEPARTMENT BOOKINGS
=================================== */

$query = "
SELECT b.booking_id,
       b.booking_date,
       b.status,
       r.resource_name,
       d.department_name,
       t.label
FROM rt_bookings b

JOIN rt_resources r
ON r.resource_id = b.resource_id

JOIN rt_department_master d
ON d.department_id = b.department_id

JOIN rt_time_slots t
ON t.slot_id = b.slot_id

WHERE b.department_id='$department_id'

ORDER BY b.booking_id DESC
";

$result = $db->runQuery($query);

$data = [];

if (!empty($result)) {

    foreach ($result as $row) {

        $status = strtoupper($row['status']);

        $data[] = [
            "id" => $row['booking_id'],
            "resource" => $row['resource_name'],
            "department" => $row['department_name'],
            "date" => $row['booking_date'],
            "slot" => $row['label'],
            "status" => $status
        ];

    }

}

echo json_encode(["data" => $data]);
exit;
?>