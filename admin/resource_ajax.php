<?php
header('Content-Type: application/json');
include "../database/db_connect.php";

$db_handle = new DBController();

$query = "
SELECT r.*, t.type_name, d.department_name
FROM rt_resources r
LEFT JOIN rt_resource_types t ON r.type_id = t.type_id
LEFT JOIN rt_department_master d ON r.department_id = d.department_id
";

$result = $db_handle->conn->query($query);

$data = [];
$sr = 1;

while($row = $result->fetch_assoc()){

$status = ($row['is_active'] == 1)
    ? "<span class='label label-success'>Active</span>"
    : "<span class='label label-danger'>Inactive</span>";

$action = "
<a href='edit_resource.php?id=".$row['resource_id']."' class='btn btn-warning btn-sm'>Edit</a>
<button onclick='deleteResource(".$row['resource_id'].")' class='btn btn-danger btn-sm'>Delete</button>
";

$data[] = [
    "sr" => $sr++,
    "name" => $row['resource_name'],
    "code" => $row['code'],
    "type_name" => $row['type_name'],
    "department_name" => $row['department_name'],
    "capacity" => $row['capacity'],
    "building" => $row['building'],
    "floor" => $row['floor'],
    "status" => $status,
    "action" => $action
];
}

echo json_encode(["data" => $data]);
exit;
?>