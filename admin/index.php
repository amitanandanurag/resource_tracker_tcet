<?php
require "header/header.php";

// ========================
// 1. FETCH HOD DATA FROM DATABASE
// ========================

?>

<!-- Font Awesome & Ionicons for icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">

<style>
    /* ========== IMPROVED CSS FOR MODERN DASHBOARD ========== */
    :root {
        --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        --card-shadow: 0 10px 20px rgba(0, 0, 0, 0.08), 0 6px 6px rgba(0, 0, 0, 0.1);
        --hover-shadow: 0 20px 25px -12px rgba(0, 0, 0, 0.15);
        --border-radius-lg: 8px;
        --border-radius-md: 6px;
    }

    /* Modern Card Styling */
    .small-box {
        border-radius: var(--border-radius-lg);
        box-shadow: var(--card-shadow);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        overflow: hidden;
        position: relative;
    }

    .small-box:hover {
        transform: translateY(-5px);
        box-shadow: var(--hover-shadow);
    }

    .small-box .inner {
        padding: 20px;
    }

    .small-box h3 {
        font-size: 38px;
        font-weight: 700;
        margin-bottom: 5px;
    }

    .small-box p {
        font-size: 15px;
        opacity: 0.9;
        margin-bottom: 0;
    }

    .small-box .icon {
        font-size: 70px;
        top: 15px;
        right: 10px;
        opacity: 0.25;
        transition: all 0.3s ease;
    }

    .small-box:hover .icon {
        transform: scale(1.1);
        opacity: 0.35;
    }

    /* Box styling */
    .box {
        border-radius: var(--border-radius-md);
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        transition: all 0.3s ease;
        margin-bottom: 25px;
        border: none;
    }

    .box:hover {
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    }

    .box-header {
        border-bottom: 2px solid #f0f2f5;
        padding: 15px 20px;
    }

    .box-header .box-title {
        font-size: 18px;
        font-weight: 600;
        color: #2c3e50;
    }

    /* Table styling */
    .table {
        margin-bottom: 0;
    }

    .table thead tr th {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        font-weight: 600;
        text-transform: uppercase;
        font-size: 12px;
        letter-spacing: 0.5px;
        padding: 12px 15px;
        border-bottom: 2px solid #dee2e6;
    }

    .table tbody tr {
        transition: background-color 0.2s ease;
    }

    .table tbody tr:hover {
        background-color: #f8f9fc;
    }

    .table td {
        vertical-align: middle;
        padding: 12px 15px;
    }

    /* Status Badges */
    .status-badge {
        padding: 6px 12px;
        border-radius: 20px;
        font-weight: 600;
        font-size: 11px;
        display: inline-block;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }

    .status-available {
        background: linear-gradient(135deg, #00b09b, #96c93d);
        color: white;
        box-shadow: 0 2px 5px rgba(0, 176, 155, 0.3);
    }

    .status-meeting {
        background: linear-gradient(135deg, #f2994a, #f2c94c);
        color: white;
    }

    .status-leave {
        background: linear-gradient(135deg, #eb3349, #f45c43);
        color: white;
    }

    /* Action Buttons */
    .btn-action {
        background: linear-gradient(135deg, #667eea, #764ba2);
        border: none;
        padding: 6px 14px;
        border-radius: 25px;
        color: white;
        font-size: 12px;
        font-weight: 500;
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .btn-action:hover {
        transform: scale(1.05);
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
        color: white;
    }

    .btn-group{
        gap: 4px;
    }

    .btn{
        margin: 2px;
    }

    /* HOD Table special styling */
    .hod-table-container {
        background: white;
        border-radius: var(--border-radius-md);
        overflow: hidden;
        margin-top: 20px;
    }

    /* Modal styling */
    .modal-content {
        border-radius: var(--border-radius-lg);
        border: none;
    }

    .modal-header {
        background: var(--primary-gradient);
        color: white;
        border-radius: var(--border-radius-lg) var(--border-radius-lg) 0 0;
        padding: 20px;
    }

    .modal-header .close {
        color: white;
        opacity: 0.8;
    }

    .modal-header .close:hover {
        opacity: 1;
    }

    /* Chart containers */
    .chart-container {
        position: relative;
        height: 250px;
        width: 100%;
        margin: 0 auto;
    }

    /* Event list styling */
    .event-list>li>a {
        padding: 12px 15px;
        border-bottom: 1px solid #f4f4f4;
        transition: all 0.2s ease;
        display: block;
        color: #444;
    }

    .event-list>li>a:hover {
        background-color: #f9fafc;
        transform: scale(1.02);
        text-decoration: none;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .small-box h3 {
            font-size: 28px;
        }

        .box-header .box-title {
            font-size: 16px;
        }

        .table td,
        .table th {
            padding: 8px 10px;
            font-size: 13px;
        }
    }

    /* Counter animation */
    .counter {
        animation: fadeInUp 0.6s ease;
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>

<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <i class="fa fa-dashboard"></i> <span><strong> Dashboard</strong></span>
        </h1>
        <ol class="breadcrumb">
            <li><a href="index.php"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Dashboard</li>
        </ol>
    </section>

>
        </div>
    </div>
</div>

<!-- Include Chart.js & FullCalendar -->
<!-- <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js'></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script> -->



<?php include "header/footer.php"; ?>