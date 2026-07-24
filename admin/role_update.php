<?php

include "../database/db_connect.php";

$db_handle = new DBController();

if(isset($_POST['user_id']) && isset($_POST['role_id']))
{

    $user_id = $_POST['user_id'];
    $role_id = $_POST['role_id'];

    // Check if role exists
    $checkRole = $db_handle->conn->query("
        SELECT role_id
        FROM rt_role_master
        WHERE role_id='$role_id'
    ");

    if($checkRole->num_rows == 0)
    {
        echo 0;
        exit;
    }

    // Start Transaction
    $db_handle->conn->begin_transaction();

    try
    {

        // Update Login Table
        $sql1 = "
        UPDATE rt_login
        SET role_id='$role_id'
        WHERE user_id='$user_id'
        ";

        if(!$db_handle->conn->query($sql1))
        {
            throw new Exception("Login Update Failed");
        }

        // Update User Master Table
        $sql2 = "
        UPDATE rt_user_master
        SET role_id='$role_id'
        WHERE user_id='$user_id'
        ";

        if(!$db_handle->conn->query($sql2))
        {
            throw new Exception("User Update Failed");
        }

        $db_handle->conn->commit();

        echo 1;

    }
    catch(Exception $e)
    {

        $db_handle->conn->rollback();

        echo 0;

    }

}
else
{

    echo 0;

}

?>