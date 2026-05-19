<?php

require_once "../database/db_connect.php";

header('Content-Type: application/json');

$db = new DBController();

$query = "

SELECT
    a.*,
    u.first_name,
    u.last_name

FROM rt_audit_log a

LEFT JOIN rt_user_master u
ON u.user_id = a.user_id

ORDER BY audit_id DESC

";

$result = $db->runQuery($query);

$data = [];

if(!empty($result)){

foreach($result as $row){

$data[] = [

    "id" => $row['audit_id'],

    "user" =>
        $row['first_name'].' '.$row['last_name'],

    "action" => $row['action_type'],

    "table" => $row['affected_table'],

    "record" => $row['affected_record'],

    "description" => $row['description'],

    "date" => $row['performed_at']

];

}

}

echo json_encode(["data"=>$data]);
exit;

?>