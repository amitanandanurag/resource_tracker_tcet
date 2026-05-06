<?php
include "../database/db_connect.php";

$db_handle = new DBController();

// ========================
// AUTO CODE FUNCTION
// ========================
function generateResourceCode($db_handle, $type_id){

    // Validate type_id
    if (empty($type_id)) {
        return 'RES001';
    }

    // Get type name
    $typeQuery = "SELECT type_name FROM rt_resource_types WHERE type_id = '$type_id'";
    $typeResult = $db_handle->conn->query($typeQuery);

    if (!$typeResult || $typeResult->num_rows == 0) {
        return 'RES001';
    }

    $typeRow = $typeResult->fetch_assoc();
    $type = strtolower($typeRow['type_name']);

    // Prefix mapping
    if($type == 'classroom'){
        $prefix = 'CR';
    } elseif($type == 'laboratory'){
        $prefix = 'LAB';
    } elseif($type == 'auditorium'){
        $prefix = 'AUD';
    } elseif($type == 'seminar hall'){
        $prefix = 'SH';
    } else {
        $prefix = 'RES';
    }

    // Get last code
    $query = "SELECT code FROM rt_resources WHERE code LIKE '$prefix%' ORDER BY resource_id DESC LIMIT 1";
    $result = $db_handle->conn->query($query);

    if($result && $result->num_rows > 0){
        $row = $result->fetch_assoc();
        $lastNumber = intval(substr($row['code'], strlen($prefix)));
        $newNumber = $lastNumber + 1;
    } else {
        $newNumber = 1;
    }

    return $prefix . str_pad($newNumber, 3, '0', STR_PAD_LEFT);
}

// ========================
// GET FORM DATA
// ========================
$name = isset($_POST['resource_name']) ? $db_handle->cleanData($_POST['resource_name']) : '';
$type_id = $_POST['type_id'] ?? '';
$department_id = $_POST['department_id'] ?? 'NULL';
$capacity = $_POST['capacity'] ?? 0;
$building = $_POST['building'] ?? '';
$floor = $_POST['floor'] ?? '';
$description = isset($_POST['description']) ? $db_handle->cleanData($_POST['description']) : '';
$is_active = $_POST['is_active'] ?? 1;

// Basic validation
if (empty($name)) {
    die("Resource name is required");
}

// ========================
// AUTO GENERATE CODE
// ========================
$code = generateResourceCode($db_handle, $type_id);

// ========================
// INSERT QUERY
// ========================
$sql = "INSERT INTO rt_resources
(resource_name, code, type_id, department_id, capacity, building, floor, description, is_active)
VALUES
('$name','$code','$type_id','$department_id','$capacity','$building','$floor','$description','$is_active')";

$result = $db_handle->executeInsert($sql);

// ========================
// RESPONSE
// ========================
if ($result) {
    echo "<script>alert('Resource Added Successfully (Code: $code)'); window.location='resource_list.php';</script>";
} else {
    echo "Error: " . $db_handle->conn->error;
}
?>