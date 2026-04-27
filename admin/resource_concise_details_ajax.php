<?php
session_start();
require "../database/db_connect.php";

$db_handle = new DBController();
$requestData = $_REQUEST;

// FILTERS
$select_type = $_POST['select_type'] ?? '';
$select_department = $_POST['select_department'] ?? '';
$select_building = $_POST['select_building'] ?? '';
$select_floor = $_POST['select_floor'] ?? '';

// BASE QUERY
$baseSql = " FROM rt_resources r

LEFT JOIN rt_resource_types t 
    ON t.type_id = r.type_id

LEFT JOIN rt_department_master d 
    ON d.department_id = r.department_id

WHERE 1=1
";

// FILTERS
if (!empty($select_type)) {
    $baseSql .= " AND r.type_id = '$select_type'";
}

if (!empty($select_department)) {
    $baseSql .= " AND r.department_id = '$select_department'";
}

if (!empty($select_building)) {
    $baseSql .= " AND r.building LIKE '%$select_building%'";
}

if (!empty($select_floor)) {
    $baseSql .= " AND r.floor LIKE '%$select_floor%'";
}

// COUNT
$countSql = "SELECT COUNT(*) as total " . $baseSql;
$countResult = $db_handle->query($countSql);
$totalRow = mysqli_fetch_assoc($countResult);

$totalData = $totalRow['total'];
$totalFiltered = $totalData;

// MAIN QUERY
$sql = "SELECT 
    t.type_name,
    d.department_name,
    r.building,
    r.floor,
    COUNT(*) as resource_count
    $baseSql
    GROUP BY r.type_id, r.department_id, r.building, r.floor
";

// PAGINATION
$start = $requestData['start'] ?? 0;
$length = $requestData['length'] ?? 10;

if ($length != -1) {
    $sql .= " LIMIT $start, $length";
}

$result = $db_handle->query($sql);

// DATA
$data = [];
$srNo = $start;
$total = 0;

while($row = mysqli_fetch_assoc($result)){

    $count = (int)$row['resource_count'];

    $data[] = [
        ++$srNo,
        $row['type_name'],
        $row['department_name'],
        $row['building'],
        $row['floor'],
        $count
    ];

    $total += $count;
}

// TOTAL ROW
$data[] = [
    '',
    '<b>Total</b>',
    '',
    '',
    '',
    '<b>'.$total.'</b>'
];

// RESPONSE
echo json_encode([
    "draw" => intval($requestData['draw'] ?? 0),
    "recordsTotal" => intval($totalData),
    "recordsFiltered" => intval($totalFiltered),
    "data" => $data
]);
?>