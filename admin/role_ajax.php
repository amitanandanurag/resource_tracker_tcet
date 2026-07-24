<?php

include "../database/db_connect.php";

$db_handle = new DBController();

$columns = array(
    0 => 'user_id',
    1 => 'first_name',
    2 => 'email_id',
    3 => 'department_name',
    4 => 'role_name'
);

$totalQuery = "
SELECT COUNT(*) AS total
FROM rt_user_master
";

$totalResult = $db_handle->conn->query($totalQuery);
$totalData = $totalResult->fetch_assoc()['total'];
$totalFiltered = $totalData;

$sql = "
SELECT
u.user_id,
u.first_name,
u.last_name,
u.email_id,
u.role_id,
d.department_name,
r.role_name

FROM rt_user_master u

LEFT JOIN rt_department_master d
ON u.department_id=d.department_id

INNER JOIN rt_role_master r
ON u.role_id=r.role_id
";

if(!empty($_POST['search']['value']))
{
    $search=$_POST['search']['value'];

    $sql.=" WHERE
    u.first_name LIKE '%$search%'
    OR
    u.last_name LIKE '%$search%'
    OR
    u.email_id LIKE '%$search%'
    OR
    d.department_name LIKE '%$search%'
    OR
    r.role_name LIKE '%$search%' ";
}

$totalFilteredQuery="
SELECT COUNT(*) AS total
FROM (
$sql
) x";

$totalFilteredResult=$db_handle->conn->query($totalFilteredQuery);
$totalFiltered=$totalFilteredResult->fetch_assoc()['total'];

$sql.=" ORDER BY ".$columns[$_POST['order'][0]['column']]." ".$_POST['order'][0]['dir'];

$sql.=" LIMIT ".$_POST['start']." , ".$_POST['length'];

$result=$db_handle->conn->query($sql);

$data=array();

$sr=$_POST['start']+1;

while($row=$result->fetch_assoc())
{

    $roles="";

    $roleQuery=$db_handle->conn->query("
    SELECT *
    FROM rt_role_master
    ORDER BY role_id
    ");

    $roles.="<select class='form-control' id='role_".$row['user_id']."'>";

    while($role=$roleQuery->fetch_assoc())
    {

        $selected="";

        if($role['role_id']==$row['role_id'])
        {
            $selected="selected";
        }

        $roles.="
        <option value='".$role['role_id']."' $selected>
        ".$role['role_name']."
        </option>";

    }

    $roles.="</select>";

    $action='
    <button
    class="btn btn-success btn-sm"
    onclick="updateRole('.$row['user_id'].')">

    <i class="fa fa-save"></i>

    Update

    </button>
    ';

    $nested=array();

    $nested["sr"]=$sr++;

    $nested["name"]=$row['first_name']." ".$row['last_name'];

    $nested["email"]=$row['email_id'];

    $nested["department"]=$row['department_name'];

    $nested["current_role"]='
    <span class="label label-primary">
    '.$row['role_name'].'
    </span>';

    $nested["new_role"]=$roles;

    $nested["action"]=$action;

    $data[]=$nested;

}

$json_data=array(

"draw"=>intval($_POST['draw']),

"recordsTotal"=>intval($totalData),

"recordsFiltered"=>intval($totalFiltered),

"data"=>$data

);

echo json_encode($json_data);

?>