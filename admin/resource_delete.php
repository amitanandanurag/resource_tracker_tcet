<?php
include "../db_connect.php";
$db_handle = new DBController();

$id = $_POST['id'];

$db_handle->query("DELETE FROM rt_resources WHERE resource_id='$id'");
?>