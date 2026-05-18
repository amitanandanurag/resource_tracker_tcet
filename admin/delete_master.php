<?php
include("../database/db_connect.php");
$conn = $db_handle->conn;

$table = $_GET['table'];
$id = $_GET['id'];

// Fetch the primary key dynamically
$col_res = $conn->query("SHOW COLUMNS FROM $table");
$pk = $col_res->fetch_assoc()['Field'];

$sql = "UPDATE $table SET status = 0 WHERE $pk = '$id'";
$conn->query($sql);

// FIX: Redirect to masters.php (the dashboard with tabs) instead of masters_data.php
header("Location: class_crud_new.php?table=$table");
exit();
?>