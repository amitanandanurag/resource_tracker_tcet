<?php
include "../database/db_connect.php";
$db_handle = new DBController();

$id = $_POST['resource_id'];

$name = $db_handle->cleanData($_POST['resource_name']);
$type_id = $_POST['type_id'];
$department_id = $_POST['department_id'];
$capacity = $_POST['capacity'];
$building = $_POST['building'];
$floor = $_POST['floor'];
$description = $db_handle->cleanData($_POST['description']);
$is_active = $_POST['is_active'];

$sql = "UPDATE rt_resources SET
resource_name='$name',
type_id='$type_id',
department_id='$department_id',
capacity='$capacity',
building='$building',
floor='$floor',
description='$description',
is_active='$is_active'
WHERE resource_id='$id'";

$result = $db_handle->query($sql);

if($result){
    echo "<script>alert('Resource Updated Successfully'); window.location='resource_list.php';</script>";
}else{
    echo "Error: " . $db_handle->conn->error;
}
?>