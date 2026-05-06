<?php
require_once "../database/db_connect.php";

$db = new DBController();

$type_id = $_POST['type_id'];

$res = $db->runQuery("
SELECT resource_id, resource_name 
FROM rt_resources 
WHERE type_id='$type_id' AND is_active=1
");

echo "<option value=''>Select Resource</option>";

foreach($res as $r){
echo "<option value='".$r['resource_id']."'>".$r['resource_name']."</option>";
}
?>