<?php

function addAuditLog(
    $db,
    $action_type,
    $affected_table='',
    $affected_record='',
    $description=''
){

    if(session_status() == PHP_SESSION_NONE){
        session_start();
    }

    $user_id = $_SESSION['user_session'] ?? 0;

    $action_type = mysqli_real_escape_string(
        $db->conn,
        $action_type
    );

    $affected_table = mysqli_real_escape_string(
        $db->conn,
        $affected_table
    );

    $affected_record = mysqli_real_escape_string(
        $db->conn,
        $affected_record
    );

    $description = mysqli_real_escape_string(
        $db->conn,
        $description
    );

    $sql = "

    INSERT INTO rt_audit_log
    (
        user_id,
        action_type,
        affected_table,
        affected_record,
        description
    )

    VALUES
    (
        '$user_id',
        '$action_type',
        '$affected_table',
        '$affected_record',
        '$description'
    )

    ";

    mysqli_query($db->conn, $sql);

}
?>