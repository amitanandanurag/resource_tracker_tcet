<?php

require_once "../database/db_connect.php";

$db = new DBController();

$resource_id = $_POST['resource_id'];

$query = "
SELECT

    b.booking_id,
    b.booking_date,
    b.status,
    b.purpose,

    t.label,

    d.department_name,

    u.first_name,
    u.last_name

FROM rt_bookings b

JOIN rt_time_slots t
ON t.slot_id = b.slot_id

JOIN rt_department_master d
ON d.department_id = b.department_id

LEFT JOIN rt_user_master u
ON u.user_id = b.booked_by_user_id

WHERE b.resource_id='$resource_id'
AND b.booking_date = CURDATE()

ORDER BY b.slot_id ASC
";

$result = $db->runQuery($query);

if(empty($result)){

    echo "

    <div class='alert alert-warning'>

        No bookings for today

    </div>

    ";

    exit;
}

foreach($result as $row){

    if($row['status'] == 'approved'){

        $color = '#28a745';

    } else if($row['status'] == 'pending'){

        $color = '#ffc107';

    } else {

        $color = '#dc3545';
    }

    echo "

    <div class='booking-item'
         style='border-left:5px solid $color'>

        <h4>

            ".$row['label']."

        </h4>

        <p>

            <b>Department :</b>

            ".$row['department_name']."

        </p>

        <p>

            <b>Booked By :</b>

            ".$row['first_name'].' '.$row['last_name']."

        </p>

        <p>

            <b>Purpose :</b>

            ".$row['purpose']."

        </p>

        <p>

            <b>Status :</b>

            ".strtoupper($row['status'])."

        </p>

    </div>

    ";
}
?>