<?php
include "../database/db_connect.php";

$db_handle = new DBController();

if(isset($_POST['id'])){

    $id = intval($_POST['id']);

    // Soft delete (deactivate)
    $sql = "UPDATE rt_resources SET is_active = 0 WHERE resource_id = $id";

    $result = $db_handle->query($sql);

    if($result){
        echo "success";
    }else{
        echo "error";
    }

}else{
    echo "invalid";
}
?>