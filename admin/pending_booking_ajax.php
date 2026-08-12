<?php
require_once "../database/db_connect.php";
session_start();

header('Content-Type: application/json');
error_reporting(0);

$db = new DBController();

$role = $_SESSION['role_id'] ?? 0;

// FETCH DATA
$query = "
SELECT b.booking_id, b.booking_date,
       r.resource_name,
       t.label
FROM rt_bookings b
JOIN rt_resources r ON r.resource_id = b.resource_id
JOIN rt_time_slots t ON t.slot_id = b.slot_id
WHERE b.status = 'underprocess'
";

$result = $db->runQuery($query);

$data = [];

if (!empty($result)) {
    foreach ($result as $row) {

        // ROLE CHECK
        if ($role == 1 || $role == 2) {
            $action = "
            <button onclick='approve(" . $row['booking_id'] . ")' class='btn btn-success btn-sm'>Approve</button>
            <button onclick='reject(" . $row['booking_id'] . ")' class='btn btn-danger btn-sm'>Reject</button>
            ";
        } else {
            $action = "<span class='label label-warning'>No Permission</span>";
        }

        $data[] = [
            "id" => $row['booking_id'],
            "resource" => $row['resource_name'],
            "date" => $row['booking_date'],
            "slot" => $row['label'],
            "action" => $action
        ];
    }
}

echo json_encode(["data" => $data]);
exit;