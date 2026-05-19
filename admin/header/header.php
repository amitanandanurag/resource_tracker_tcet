<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
  session_start();
}
include_once("../database/db_connect.php");
if (isset($_SESSION['user_session'])) {
} else {
  header("location: ../index.php");
}

$db_handle = new DBController();

$sql = "SELECT * FROM rt_login 
WHERE user_id='" . $_SESSION['user_session'] . "'";

$result = mysqli_query($db_handle->conn, $sql)
  or die("database error:" . mysqli_error($db_handle->conn));

$row = mysqli_fetch_assoc($result);

$username = $row['username'];
$userid = $row['user_id'];
$usertype = $row['role_id'];
$name = $row['username'];

$full_email = $name;

/* Extract name before @ */
$user_name = strstr($full_email, '@', true);

/* Convert dots/underscores to spaces */
$display_name = ucwords(
  str_replace(
    ['.', '_'],
    ' ',
    $user_name
  )
);

$sql = "SELECT * FROM rt_role_master WHERE role_id='" . $usertype . "'";
$result = mysqli_query($db_handle->conn, $sql) or die("database error:" . mysqli_error($db_handle->conn));
while ($row = $result->fetch_assoc()) {
  $role_name = $row['role_name'];
}


?>
<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>TCET | Dashboard</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.6 -->
  <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/AdminLTE.min.css">
  <!-- AdminLTE Skins. Choose a skin from the css/skins folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="dist/css/skins/_all-skins.min.css">
  <!-- iCheck -->
  <link rel="stylesheet" href="plugins/iCheck/flat/blue.css">
  <!-- Morris chart -->
  <link rel="stylesheet" href="plugins/morris/morris.css">
  <!-- jvectormap -->
  <link rel="stylesheet" href="plugins/jvectormap/jquery-jvectormap-1.2.2.css">
  <!-- Date Picker -->
  <link rel="stylesheet" href="plugins/datepicker/datepicker3.css">
  <!-- Daterange picker -->
  <link rel="stylesheet" href="plugins/daterangepicker/daterangepicker.css">
  <!-- bootstrap wysihtml5 - text editor -->
  <link rel="stylesheet" href="plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">
  <script src="https://code.jquery.com/jquery-3.3.1.js" integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60="
    crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.7.1/jszip.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/2.0.2/FileSaver.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.13/jspdf.plugin.autotable.min.js"></script>

  <style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;700;800&display=swap');

    body {
      font-family: 'Plus Jakarta Sans', sans-serif !important;
      background-color: #f8fafc;
    }

    /* --- UNIFIED HEADER GRADIENT --- */
    /* This treats the logo and navbar as one continuous entity */
    /* --- 1. THE TOGGLE BUTTON & HEADER CLEANUP --- */
    .skin-blue .main-header .navbar .sidebar-toggle {
      color: #fff !important;
      background: transparent !important;
      border: none !important;
      transition: background 0.3s ease;
    }

    .skin-blue .main-header .navbar .sidebar-toggle:hover {
      background: rgba(255, 255, 255, 0.1) !important;
    }

    /* --- 2. SIDEBAR COLOR (Full & Mini Mode) --- */
    /* This ensures the background remains consistent when shrunk */
    .skin-blue .main-sidebar,
    .skin-blue .wrapper,
    .skin-blue .main-sidebar .sidebar-menu>li.header,
    .sidebar-mini.sidebar-collapse .main-sidebar {
      background-color: #0f172a !important;
      /* The Deep Navy */
    }

    /* --- 3. MINI SIDEBAR ICON STYLING --- */
    /* Fixes the look when collapsed */
    .sidebar-mini.sidebar-collapse .sidebar-menu>li>a {
      padding: 12px 0 !important;
      text-align: center;
      margin: 5px 5px !important;
      /* Added margin for that "floating" look */
      border-radius: 12px;
    }

    .sidebar-mini.sidebar-collapse .sidebar-menu>li:hover>a {
      background: #7c3aed !important;
      /* Purple highlight on hover in mini mode */
      color: #fff !important;
    }

    /* --- 4. ACTIVE & HOVER STATES (Enhanced) --- */
    .skin-blue .sidebar-menu>li.active>a,
    .skin-blue .sidebar-menu>li:hover>a {
      background: linear-gradient(90deg, rgba(124, 58, 237, 0.3) 0%, rgba(124, 58, 237, 0) 100%) !important;
      color: #fff !important;
      border-left: 4px solid #7c3aed !important;
    }

    /* --- 5. THE CONTENT WRAPPER SHADOW --- */
    /* This adds a slight "depth" separation between the sidebar and dashboard */
    .content-wrapper {
      background-color: #f8fafc !important;
      border-left: 1px solid rgba(0, 0, 0, 0.05);
    }

    /* --- 6. USER PANEL FIX --- */
    .user-panel>.info>a {
      color: #10b981 !important;
      /* Make the "Online" text pop */
      font-weight: 600;
    }

    .skin-blue .sidebar-menu>li>a {
      margin: 5px 15px;
      border-radius: 10px;
      color: #94a3b8 !important;
      font-weight: 500;
    }

    .skin-blue .sidebar-menu>li.active>a,
    .skin-blue .sidebar-menu>li:hover>a {
      background: linear-gradient(90deg, rgba(124, 58, 237, 0.2) 0%, rgba(124, 58, 237, 0) 100%) !important;
      color: #fff !important;
      border-left: 3px solid #7c3aed !important;
    }

    /* --- MODERN STAT CARDS --- */
    .dashboard-container {
      display: grid;
      grid-template-columns: repeat(5, 1fr);
      gap: 20px;
      margin: 25px 0;
    }

    .glass-card {
      position: relative;
      padding: 25px 20px;
      border-radius: 24px;
      color: white;
      overflow: hidden;
      transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
      border: 1px solid rgba(255, 255, 255, 0.1);
      box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
    }

    .glass-card:hover {
      transform: translateY(-10px);
      box-shadow: 0 20px 40px -10px rgba(0, 0, 0, 0.2);
    }

    /* Subtle Shine Effect */
    .glass-card::after {
      content: '';
      position: absolute;
      top: -50%;
      left: -50%;
      width: 200%;
      height: 200%;
      background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, transparent 70%);
      pointer-events: none;
    }

    .glass-icon {
      font-size: 28px;
      margin-bottom: 15px;
      opacity: 0.9;
      background: rgba(255, 255, 255, 0.2);
      width: 50px;
      height: 50px;
      display: flex;
      align-items: center;
      justify-content: center;
      border-radius: 14px;
    }

    .glass-label {
      font-size: 12px;
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      opacity: 0.8;
    }

    .glass-number {
      font-size: 32px;
      font-weight: 800;
      margin: 5px 0 0 0;
    }

    /* THEME GRADIENTS */
    .theme-1 {
      background: linear-gradient(135deg, #6366f1, #a855f7);
    }

    .theme-2 {
      background: linear-gradient(135deg, #3b82f6, #2dd4bf);
    }

    .theme-3 {
      background: linear-gradient(135deg, #f43f5e, #fb923c);
    }

    .theme-4 {
      background: linear-gradient(135deg, #8b5cf6, #d946ef);
    }

    .theme-5 {
      background: linear-gradient(135deg, #1e293b, #475569);
    }
  </style>

</head>

<body class="hold-transition skin-blue sidebar-mini">
  <div class="wrapper">

    <header class="main-header">
      <!-- Logo -->
      <a href="index.php" class="logo">
        <!-- mini logo for sidebar mini 50x50 pixels -->
        <span class="logo-mini"><img src="images/booklogo.webp" class="py-2" height="40px" /></span>
        <!-- logo for regular state and mobile devices -->
        <span class="logo-lg"><img src="images/booklogo.webp" class="py-2" height="40px" /> <small>TCET</small></span>
      </a>
      <!-- Header Navbar: style can be found in header.less -->
      <nav class="navbar navbar-static-top">
        <!-- Sidebar toggle button-->
        <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
          <span class="sr-only">Toggle navigation</span>
        </a>

        <div class="navbar-custom-menu">
          <ul class="nav navbar-nav">

            <li class="dropdown user user-menu">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                <img src="dist/img/user2-160x160.jpg" class="user-image" alt="User Image">
                <span class="hidden-xs">&nbsp;Welcome, <?php echo $display_name; ?> &nbsp;</span>
              </a>

              <ul class="dropdown-menu shadow-lg"
                style="width: 280px; border-radius: 8px; overflow: hidden; border: none;">
                <!-- User image and info -->
                <li class="user-header"
                  style="background: linear-gradient(135deg, #605ca8 0%, #3c8dbc 100%); height: auto; padding: 25px 15px;">
                  <img src="dist/img/user2-160x160.jpg" class="img-circle" alt="User Image"
                    style="border: 3px solid rgba(255,255,255,0.2); box-shadow: 0 4px 10px rgba(0,0,0,0.2);">
                  <p style="margin-top: 15px; font-weight: 600; font-size: 18px;">
                    <?php echo $display_name; ?>
                    <small style="display: block; opacity: 0.8; font-weight: 400; font-size: 12px; margin-top: 5px;">
                      <?php echo $full_email; ?>
                    </small>
                  </p>
                  <span class="label label-default"
                    style="background: rgba(255,255,255,0.2); font-size: 10px; text-transform: uppercase; letter-spacing: 1px;">
                    Role: <?php echo $role_name; ?>
                  </span>
                </li>

                <!-- Menu Footer-->
                <li class="user-footer" style="background-color: #ffffff; padding: 15px;">
                  <div class="pull-left">
                    <a href="profile.php" class="btn btn-default btn-flat"
                      style="border-radius: 4px; border: 1px solid #ddd;">
                      <i class="fa fa-user"></i> My Profile
                    </a>
                  </div>
                  <div class="pull-right">
                    <a href="../login/logout.php" class="btn btn-danger btn-flat"
                      style="border-radius: 4px; background-color: #dd4b39; border: none; padding: 6px 15px;">
                      <i class="fa fa-sign-out"></i> Sign out
                    </a>
                  </div>
                </li>
              </ul>
              <!-- <ul class="dropdown-menu">
                <li class="user-header text-center" style="padding: 14px; background-color: #423cbc;">
                  <img src="dist/img/user2-160x160.jpg" class="img-circle" alt="User Image"
                    style="width: 90px; height: 90px; border: 2px solid rgba(255,255,255,0.2);">

                  <p class="text-white" style="margin-top: 10px; color: #fff; font-size: 17px;">
                    <?php echo $username; ?>
                    <br>
                    <small>Role: <span class="badge"><?php echo $role_name; ?></span></small>
                  </p>
                </li>

                <li class="user-footer" style="background-color: #f9f9f9; padding: 10px;">
                  <div class="pull-left">
                    <a href="profile.php" class="btn btn-default btn-flat">Profile</a>
                  </div>
                  <div class="pull-right">
                    <a href="../login/logout.php" class="btn btn-danger btn-flat">Sign out</a>
                  </div>
                  <div class="clearfix"></div>
                </li>
              </ul> -->
            </li>
            <!-- Control Sidebar Toggle Button -->
            <li>
              <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
            </li>
          </ul>
        </div>
      </nav>
    </header>
    <!-- Left side column. contains the logo and sidebar -->
    <aside class="main-sidebar">
      <!-- sidebar: style can be found in sidebar.less -->
      <section class="sidebar">
        <!-- Sidebar user panel -->
        <div class="user-panel">
          <div class="pull-left image">
            <img src="dist/img/user2-160x160.jpg" class="img-circle" alt="User Image">
          </div>
          <div class="pull-left info">
            <p><?php echo $name; ?></p>
            <a class="badge" href="#" style="background:white; color: green;">
              <i class="fa fa-circle text-success"></i> Online
            </a>
          </div>
        </div>
        <br />
        <?php include "side_menu.php"; ?>
        <!-- /.sidebar -->
    </aside>