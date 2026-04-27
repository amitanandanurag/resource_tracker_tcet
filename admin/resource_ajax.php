<?php
header('Content-Type: application/json');
include "../database/db_connect.php";

$db_handle = new DBController();
$requestData = $_REQUEST;

// TOTAL COUNT
$totalQuery = "SELECT COUNT(*) as total FROM rt_resources";
$totalResult = $db_handle->query($totalQuery);
$totalRow = mysqli_fetch_assoc($totalResult);

$totalData = $totalRow['total'];
$totalFiltered = $totalData;

// PAGINATION
$start  = $requestData['start'] ?? 0;
$length = $requestData['length'] ?? 10;

// MAIN QUERY
$query = "
SELECT r.*, t.type_name, d.department_name
FROM rt_resources r
LEFT JOIN rt_resource_types t ON r.type_id = t.type_id
LEFT JOIN rt_department_master d ON r.department_id = d.department_id
WHERE r.is_active = 1
LIMIT $start, $length
";

$result = $db_handle->query($query);

// DATA
$data = [];
$sr = $start;

while($row = mysqli_fetch_assoc($result)){

$status = ($row['is_active'] == 1)
    ? "<span class='label label-success'>Active</span>"
    : "<span class='label label-danger'>Inactive</span>";

$action = "
<a href='edit_resource.php?id=".$row['resource_id']."' class='btn btn-warning btn-sm'>Edit</a>
<button onclick='deleteResource(".$row['resource_id'].")' class='btn btn-danger btn-sm'>Delete</button>
";

$data[] = [
    "sr" => ++$sr,
    "name" => $row['resource_name'],
    "code" => $row['code'],
    "type" => $row['type_name'],
    "department" => $row['department_name'],
    "capacity" => $row['capacity'],
    "building" => $row['building'],
    "floor" => $row['floor'],
    "status" => $status,
    "action" => $action
];
}

// RESPONSE
echo json_encode([
    "draw" => intval($requestData['draw'] ?? 0),
    "recordsTotal" => intval($totalData),
    "recordsFiltered" => intval($totalFiltered),
    "data" => $data
]);
exit;
?>