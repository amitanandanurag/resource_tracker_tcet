<?php include "header/header.php"; ?>

<?php
require_once "../database/db_connect.php";

$db = new DBController();

/* =====================================
   FETCH RESOURCES WITH LIVE STATUS
===================================== */

$query = "

SELECT

    r.*,

    (
        SELECT COUNT(*)

        FROM rt_bookings b

        WHERE b.resource_id = r.resource_id

        AND b.booking_date = CURDATE()

        AND b.status IN ('approved','pending')

    ) AS booking_count

FROM rt_resources r

WHERE r.is_active='1'

ORDER BY r.department_id,r.floor,r.resource_name

";

$resources = $db->runQuery($query);

/* =====================================
   GROUP RESOURCES
===================================== */

$grouped = [];

if (!empty($resources)) {

    foreach ($resources as $row) {

        $dept = $row['department_id'];
        $floor = $row['floor'];

        $grouped[$dept][$floor][] = $row;
    }
}
?>

<style>

/* =====================================
   PAGE
===================================== */

.content{
    padding:20px;
    background:#f4f7fb;
}

/* =====================================
   FILTER BAR
===================================== */

.filter-wrapper{

    background:linear-gradient(
        135deg,
        #1e3c72,
        #2a5298
    );

    padding:25px;

    border-radius:22px;

    margin-bottom:35px;

    box-shadow:0 10px 30px rgba(0,0,0,0.15);
}

.filter-title{

    color:#fff;

    font-size:26px;

    font-weight:700;

    margin-bottom:20px;
}

.filter-wrapper .form-control{

    height:50px;

    border-radius:14px;

    border:none;

    box-shadow:none;

    font-size:15px;
}

/* =====================================
   TITLES
===================================== */

.department-title{

    font-size:32px;

    font-weight:800;

    margin:45px 0 25px;

    color:#1e3c72;
}

.floor-title{

    display:inline-block;

    background:#eef2ff;

    color:#3f51b5;

    padding:8px 18px;

    border-radius:30px;

    font-size:15px;

    font-weight:700;

    margin-bottom:22px;
}

/* =====================================
   GRID
===================================== */

.resource-grid{

    display:grid;

    grid-template-columns:
        repeat(auto-fill,minmax(280px,1fr));

    gap:22px;

    margin-bottom:40px;
}

/* =====================================
   CARD
===================================== */

.resource-card{

    position:relative;

    background:linear-gradient(
        145deg,
        #ffffff,
        #f5f7ff
    );

    border-radius:24px;

    padding:24px;

    overflow:hidden;

    transition:all 0.35s ease;

    border:1px solid rgba(63,81,181,0.08);

    box-shadow:0 8px 24px rgba(0,0,0,0.08);

    min-height:280px;
}

.resource-card:hover{

    transform:translateY(-8px) scale(1.01);

    box-shadow:0 18px 40px rgba(0,0,0,0.18);
}

.resource-card::before{

    content:'';

    position:absolute;

    top:0;
    left:0;

    width:100%;
    height:6px;

    background:linear-gradient(
        90deg,
        #3f51b5,
        #00bcd4
    );
}

/* =====================================
   ICON
===================================== */

.resource-icon{

    width:65px;
    height:65px;

    border-radius:18px;

    background:linear-gradient(
        135deg,
        #3f51b5,
        #00bcd4
    );

    display:flex;

    align-items:center;

    justify-content:center;

    color:#fff;

    font-size:28px;

    margin-bottom:18px;
}

/* =====================================
   NAME
===================================== */

.resource-name{

    font-size:21px;

    font-weight:700;

    color:#1a1a1a;

    margin-bottom:12px;
}

/* =====================================
   META
===================================== */

.resource-meta{

    display:flex;

    align-items:center;

    margin-bottom:10px;

    color:#666;

    font-size:14px;
}

.resource-meta i{

    width:24px;

    color:#3f51b5;
}

/* =====================================
   FOOTER
===================================== */

.resource-footer{

    position:absolute;

    bottom:20px;

    left:24px;
    right:24px;

    display:flex;

    justify-content:space-between;

    align-items:center;
}

/* =====================================
   STATUS
===================================== */

.status-pill{

    padding:7px 16px;

    border-radius:30px;

    background:#e8f5e9;

    color:#2e7d32;

    font-size:12px;

    font-weight:700;
}

/* =====================================
   BUTTON
===================================== */

.view-btn{

    background:linear-gradient(
        135deg,
        #3f51b5,
        #5c6bc0
    );

    color:#fff;

    border:none;

    padding:9px 18px;

    border-radius:12px;

    font-size:13px;

    font-weight:600;

    transition:0.3s;
}

.view-btn:hover{

    background:linear-gradient(
        135deg,
        #2c3e9f,
        #3f51b5
    );

    color:#fff;
}

/* =====================================
   BOOKING ITEM
===================================== */

.booking-item{

    padding:18px;

    background:#f8f9fa;

    border-radius:15px;

    margin-bottom:15px;

    border-left:5px solid #28a745;
}

/* =====================================
   MODAL
===================================== */

.modal-content{
    border-radius:20px;
}

.modal-header{

    background:#3f51b5;

    color:#fff;

    border-radius:20px 20px 0 0;
}

</style>

<div class="content-wrapper">

<section class="content-header">

    <h1>Resource Explorer</h1>

</section>

<section class="content">

<!-- FILTERS -->

<div class="filter-wrapper">

<div class="filter-title">

    Resource Explorer

</div>

<div class="row">

    <!-- DEPARTMENT -->

    <div class="col-md-4">

        <select id="departmentFilter"
                class="form-control">

            <option value="">
                All Departments
            </option>

            <?php

            $departments = $db->runQuery("
            SELECT *
            FROM rt_department_master
            ORDER BY department_name
            ");

            foreach ($departments as $d) {

                echo "

                <option value='" . $d['department_id'] . "'>
                    " . $d['department_name'] . "
                </option>

                ";
            }

            ?>

        </select>

    </div>

    <!-- FLOOR -->

    <div class="col-md-3">

        <select id="floorFilter"
                class="form-control">

            <option value="">
                All Floors
            </option>

            <?php

            $floors = $db->runQuery("
            SELECT DISTINCT floor
            FROM rt_resources
            ORDER BY floor ASC
            ");

            foreach ($floors as $f) {

                echo "

                <option value='" . $f['floor'] . "'>
                    Floor " . $f['floor'] . "
                </option>

                ";
            }

            ?>

        </select>

    </div>

    <!-- TYPE -->

    <div class="col-md-2">

        <select id="typeFilter"
                class="form-control">

            <option value="">
                All Types
            </option>

            <?php

            $types = $db->runQuery("
            SELECT *
            FROM rt_resource_types
            ORDER BY type_name
            ");

            foreach ($types as $t) {

                echo "

                <option value='" . $t['type_id'] . "'>
                    " . $t['type_name'] . "
                </option>

                ";
            }

            ?>

        </select>

    </div>

    <!-- SEARCH -->

    <div class="col-md-3">

        <input type="text"
               id="resourceSearch"
               class="form-control"
               placeholder="Search resources...">

    </div>

</div>

</div>

<?php

foreach ($grouped as $dept_id => $floors) {

    $deptData = $db->runQuery("
    SELECT department_name
    FROM rt_department_master
    WHERE department_id='$dept_id'
    ");

    $dept_name =
        $deptData[0]['department_name']
        ?? 'Unknown';

    echo "

    <div class='resource-section'>

        <div class='department-title'>
            " . $dept_name . " Department
        </div>

    ";

    foreach ($floors as $floor_no => $items) {

        echo "

        <div class='floor-title'>
            Floor : " . $floor_no . "
        </div>

        ";

        echo "<div class='resource-grid'>";

        foreach ($items as $res) {

            echo "

            <div class='resource-col'>

                <div class='resource-card resourceFilter'

                     data-floor='" . $res['floor'] . "'

                     data-name='" . strtolower($res['resource_name']) . "'

                     data-type='" . $res['type_id'] . "'>

                    <div class='resource-icon'>
                        <i class='fa fa-desktop'></i>
                    </div>

                    <div class='resource-name'>
                        " . $res['resource_name'] . "
                    </div>

                    <div class='resource-meta'>
                        <i class='fa fa-building'></i>
                        Building : " . $res['building'] . "
                    </div>

                    <div class='resource-meta'>
                        <i class='fa fa-layer-group'></i>
                        Floor : " . $res['floor'] . "
                    </div>

                    <div class='resource-meta'>
                        <i class='fa fa-barcode'></i>
                        Code : " . $res['code'] . "
                    </div>

                    <div class='resource-footer'>
            ";

            if($res['booking_count'] > 0){

                echo "

                <div class='status-pill'
                     style='background:#ffebee;color:#c62828;'>

                    Booked

                </div>

                ";

            }else{

                echo "

                <div class='status-pill'>

                    Available

                </div>

                ";
            }

            echo "

                        <button class='view-btn'

                                onclick='viewBookings(
                                    " . $res['resource_id'] . ",
                                    \"" . $res['resource_name'] . "\"
                                )'>

                            View Bookings

                        </button>

                    </div>

                </div>

            </div>

            ";
        }

        echo "</div>";
    }

    echo "</div>";
}

?>

</section>
</div>

<!-- MODAL -->

<div class="modal fade" id="bookingModal">

<div class="modal-dialog modal-lg">

<div class="modal-content">

<div class="modal-header">

    <button type="button"
            class="close"
            data-dismiss="modal">

        &times;

    </button>

    <h4 class="modal-title"
        id="modalTitle">

        Resource Bookings

    </h4>

</div>

<div class="modal-body"
     id="bookingData">

</div>

</div>
</div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>

/* =====================================
   VIEW BOOKINGS
===================================== */

function viewBookings(resource_id, resource_name) {

    $("#modalTitle").html(
        resource_name + " - Today's Bookings"
    );

    $.ajax({

        url: 'resource_booking_today.php',

        type: 'POST',

        data: {

            resource_id: resource_id,

            department_id:
                $("#departmentFilter").val()
        },

        success: function (response) {

            $("#bookingData").html(response);

            $("#bookingModal").modal('show');
        }

    });
}

/* =====================================
   FILTER EVENTS
===================================== */

$("#floorFilter, #typeFilter").change(function () {

    applyFilters();

});

$("#resourceSearch").keyup(function () {

    applyFilters();

});

/* =====================================
   RESOURCE FILTERS
===================================== */

function applyFilters() {

    let floor =
        $("#floorFilter").val();

    let type =
        $("#typeFilter").val();

    let search =
        $("#resourceSearch")
            .val()
            .toLowerCase();

    $(".resource-col").hide();

    $(".resourceFilter").each(function () {

        let flr =
            $(this)
                .data("floor")
                .toString();

        let name =
            $(this)
                .data("name");

        let typ =
            $(this)
                .data("type")
                .toString();

        let show = true;

        /* FLOOR */

        if (floor != "" &&
            flr != floor) {

            show = false;
        }

        /* TYPE */

        if (type != "" &&
            typ != type) {

            show = false;
        }

        /* SEARCH */

        if (search != "" &&
            !name.includes(search)) {

            show = false;
        }

        if (show) {

            $(this)
                .closest(".resource-col")
                .show();
        }

    });

    /* RESET */

    if (
        floor == "" &&
        type == "" &&
        search == ""
    ) {

        $(".resource-col").show();
    }

}

</script>

<?php include "header/footer.php"; ?>