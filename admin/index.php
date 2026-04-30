<?php
require "header/header.php";

// ==========================================================
// NEW DASHBOARD STATS FETCHING (RESOURCES & BOOKINGS)
// ==========================================================
$db = $db_handle->conn;

// 1. Total Resources
$res_query = "SELECT COUNT(*) as total FROM rt_resources";
$res_result = mysqli_query($db, $res_query);
$total_resources = $res_result ? mysqli_fetch_assoc($res_result)['total'] : 0;

// 2. Total Users (using your master table)
$users_query = "SELECT COUNT(*) as total FROM rt_user_master";
$users_result = mysqli_query($db, $users_query);
$total_users = $users_result ? mysqli_fetch_assoc($users_result)['total'] : 0;

// 3. Active Bookings Today
$bookings_query = "SELECT COUNT(*) as total FROM rt_bookings WHERE booking_date = CURDATE() AND status = 'Approved'";
$bookings_result = mysqli_query($db, $bookings_query);
$active_bookings = $bookings_result ? mysqli_fetch_assoc($bookings_result)['total'] : 0;

// 4. Total Departments
$dept_query = "SELECT COUNT(*) as total FROM rt_department_master";
$dept_result = mysqli_query($db, $dept_query);
$total_departments = $dept_result ? mysqli_fetch_assoc($dept_result)['total'] : 0;

// 5. Pending Approvals
$pending_query = "SELECT COUNT(*) as total FROM rt_bookings WHERE status = 'Pending'";
$pending_result = mysqli_query($db, $pending_query);
$pending_approvals = $pending_result ? mysqli_fetch_assoc($pending_result)['total'] : 0;

// --- KEEPING EXISTING LOGIC BELOW ---
// (Your existing HOD, Specialization, and Chart data fetching code from index_old_1.php goes here)
// ...
?>

<div class="content-wrapper">
    <section class="content-header">
        <h1>Dashboard <small>Control Panel</small></h1>
        <ol class="breadcrumb">
            <li><a href="./index.php"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Dashboard</li>
        </ol>
    </section>

    <section class="content">

        <div class="row">
            <div class="col-lg-2 col-xs-6">
                <div class="small-box bg-blue">
                    <div class="inner">
                        <h3><?php echo $total_resources; ?></h3>
                        <p>Total Resources</p>
                    </div>
                    <div class="icon"><i class="fa fa-cubes"></i></div>
                </div>
            </div>
            <div class="col-lg-2 col-xs-6">
                <div class="small-box bg-green">
                    <div class="inner">
                        <h3><?php echo $total_users; ?></h3>
                        <p>Total Users</p>
                    </div>
                    <div class="icon"><i class="fa fa-users"></i></div>
                </div>
            </div>
            <div class="col-lg-3 col-xs-6">
                <div class="small-box bg-yellow">
                    <div class="inner">
                        <h3><?php echo $active_bookings; ?></h3>
                        <p>Active Bookings Today</p>
                    </div>
                    <div class="icon"><i class="fa fa-calendar-check-o"></i></div>
                </div>
            </div>
            <div class="col-lg-2 col-xs-6">
                <div class="small-box bg-purple">
                    <div class="inner">
                        <h3><?php echo $total_departments; ?></h3>
                        <p>Departments</p>
                    </div>
                    <div class="icon"><i class="fa fa-building"></i></div>
                </div>
            </div>
            <div class="col-lg-3 col-xs-6">
                <div class="small-box bg-red">
                    <div class="inner">
                        <h3><?php echo $pending_approvals; ?></h3>
                        <p>Pending Approvals</p>
                    </div>
                    <div class="icon"><i class="fa fa-clock-o"></i></div>
                </div>
            </div>
        </div>

        </section>
</div>

<?php include "header/footer.php"; ?>