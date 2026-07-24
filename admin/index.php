<style>
    /* Force the calendar to be visible and white */
    #calendar {
        background-color: white !important;
        color: black !important;
        min-height: 500px;
        /* Ensure it has space to grow */
        padding: 15px;
    }

    /* Fix the chart container to prevent squashing */
    .chart-container {
        position: relative;
        height: 350px;
        width: 100%;
    }
</style>
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

// ==========================================================
// RESOURCE UTILIZATION (PIE CHART DATA)
// ==========================================================
$util_query = "SELECT 
    (SELECT COUNT(DISTINCT resource_id) FROM rt_bookings WHERE booking_date = CURDATE() AND status = 'Approved') as booked,
    ((SELECT COUNT(*) FROM rt_resources) - (SELECT COUNT(DISTINCT resource_id) FROM rt_bookings WHERE booking_date = CURDATE() AND status = 'Approved')) as available";
$util_data = mysqli_fetch_assoc(mysqli_query($db, $util_query));


// ==========================================================
// DEPARTMENT WISE USAGE (BAR GRAPH DATA)
// ==========================================================
$dept_usage_query = "SELECT d.department_name, COUNT(b.booking_id) as usage_count FROM rt_department_master d LEFT JOIN rt_bookings b ON d.department_id = b.department_id GROUP BY d.department_id";
$dept_usage_result = mysqli_query($db, $dept_usage_query);

$dept_names = [];
$usage_counts = [];
while ($row = mysqli_fetch_assoc($dept_usage_result)) {
    $dept_names[] = $row['department_name'];
    $usage_counts[] = $row['usage_count'];
}


// HEATMAP DATA: Join with rt_slots to get from_time
$heatmap_query = "SELECT HOUR(s.start_time) as booking_hour, COUNT(b.booking_id) as count FROM rt_bookings b JOIN rt_time_slots s ON b.slot_id = s.slot_id GROUP BY HOUR(s.start_time) ORDER BY booking_hour ASC;";
$heatmap_result = mysqli_query($db, $heatmap_query);

$hours_data = array_fill(0, 24, 0);
while ($row = mysqli_fetch_assoc($heatmap_result)) {
    $hours_data[(int) $row['booking_hour']] = (int) $row['count'];
}

// 2. CALENDAR DATA: Join with rt_resources to get names for the display
$events_query = "SELECT b.booking_id, b.booking_date, r.resource_name, b.status FROM rt_bookings b JOIN rt_resources r ON b.resource_id = r.resource_id";
$events_result = mysqli_query($db, $events_query);
$events = [];
// while($row = mysqli_fetch_assoc($events_result)) {
//     $events[] = [
//         'title' => $row['resource_name'],
//         'start' => $row['booking_date'],
//         // Styling based on status
//         'backgroundColor' => ($row['status'] == 'Approved' ? '#00a65a' : '#f39c12'),
//         'borderColor' => ($row['status'] == 'Approved' ? '#008d4c' : '#db8b0b')
//     ];
// }

while ($row = mysqli_fetch_assoc($events_result)) {
    $events[] = [
        'title' => $row['resource_name'],
        'start' => $row['booking_date'],
        'backgroundColor' => ($row['status'] == 'approved' ? '#00a65a' : '#f39c12'),
        'borderColor' => ($row['status'] == 'approved' ? '#008d4c' : '#db8b0b'),
        // Use extendedProps to store extra data for the modal
        'extendedProps' => [
            'status' => $row['status']
        ]
    ];
    // var_dump(json_encode($events));
}
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
        <div class="dashboard-container">
            <?php
            $stats = [
                [
                    'label' => 'Total Resources',
                    'value' => $total_resources,
                    'icon' => 'fa-cubes',
                    'theme' => 'theme-1',
                    'link' => 'resource_list.php'          // Change as required
                ],
                [
                    'label' => 'Total Users',
                    'value' => $total_users,
                    'icon' => 'fa-users',
                    'theme' => 'theme-2',
                    'link' => 'class_crud_new.php?tab=user-list'
                ],
                [
                    'label' => 'Active Bookings',
                    'value' => $active_bookings,
                    'icon' => 'fa-calendar-check-o',
                    'theme' => 'theme-4',
                    'link' => 'active_bookings.php'
                ],
                [
                    'label' => 'Departments',
                    'value' => $total_departments,
                    'icon' => 'fa-building',
                    'theme' => 'theme-5',
                    'link' => 'class_crud_new.php?tab=department-list'
                ],
                [
                    'label' => 'Pending Action',
                    'value' => $pending_approvals,
                    'icon' => 'fa-bell',
                    'theme' => 'theme-3',
                    'link' => 'pending_bookings.php'
                ]
            ];

            foreach ($stats as $stat) {
                ?>
                <div class="glass-card <?php echo $stat['theme']; ?>"
                    onclick="window.location.href='<?php echo $stat['link']; ?>';" style="cursor:pointer;">

                    <div class="glass-icon">
                        <i class="fa <?php echo $stat['icon']; ?>"></i>
                    </div>

                    <div class="glass-content">
                        <span class="glass-label"><?php echo $stat['label']; ?></span>
                        <h3 class="glass-number"><?php echo $stat['value']; ?></h3>
                    </div>

                </div>
            <?php } ?>
        </div>

        <div class="row">
            <!-- Resource Utilization Pie Chart -->
            <div class="col-md-6">
                <div class="box box-danger">
                    <div class="box-header with-border">
                        <h3 class="box-title">Resource Utilization (Today)</h3>
                    </div>
                    <div class="box-body" style="height:310px; justify-content:center; display:flex">
                        <canvas id="utilPieChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Department Usage Bar Graph -->
            <div class="col-md-6">
                <div class="box box-success">
                    <div class="box-header with-border">
                        <h3 class="box-title">Department Wise Distribution</h3>
                    </div>
                    <div class="box-body">
                        <canvas id="deptBarChart" style="height:250px"></canvas>
                    </div>
                </div>
            </div>




            <!-- Calendar View -->
            <div class="col-md-6">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-calendar"></i> Resource Schedule</h3>
                    </div>
                    <div class="box-body no-padding">
                        <!-- Calendar Container -->
                        <div id="calendar" style="padding: 10px;"></div>
                    </div>
                </div>
            </div>

            <!-- Peak Hours Heatmap -->
            <div class="col-md-6">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-clock-o"></i> Peak Booking Hours</h3>
                    </div>
                    <div class="box-body">
                        <div class="chart">
                            <canvas id="peakHoursChart"
                                style="height: 200px; display: block; box-sizing: border-box; width: 600px"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<div class="row">

</div>

<!-- Modal for Booking Details -->
<div class="modal fade" id="bookingModal" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Resources Booked on <span id="modalDate"></span></h4>
            </div>
            <div class="modal-body">
                <div id="modalContent"></div>
            </div>
        </div>
    </div>
</div>
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // 1. Utilization Pie Chart
    const utilCtx = document.getElementById('utilPieChart').getContext('2d');
    new Chart(utilCtx, {
        type: 'pie',
        data: {
            labels: ['Booked', 'Available'],
            datasets: [{
                data: [<?php echo $util_data['booked']; ?>, <?php echo $util_data['available']; ?>],
                backgroundColor: ['#f56954', '#00a65a']
            }]
        }

    });

    // 2. Department Bar Chart
    const deptCtx = document.getElementById('deptBarChart').getContext('2d');
    new Chart(deptCtx, {
        type: 'bar',
        data: {
            labels: <?php echo json_encode($dept_names); ?>,
            datasets: [{
                label: 'Number of Bookings',
                data: <?php echo json_encode($usage_counts); ?>,
                backgroundColor: '#3c8dbc'
            }]
        },
        options: {
            scales: { y: { beginAtZero: true } }
        }
    });

    document.addEventListener('DOMContentLoaded', function () {
        // 1. Heatmap (Bar Chart Proxy)
        const ctx = document.getElementById('peakHoursChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode(array_map(function ($h) {
                    return $h . ":00"; }, range(0, 23))); ?>,
                datasets: [{
                    label: 'Number of Bookings',
                    data: <?php echo json_encode(array_values($hours_data)); ?>,
                    backgroundColor: 'rgba(0, 192, 239, 0.6)',
                    borderColor: '#00c0ef',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: { y: { beginAtZero: true } }
            }
        });

        // 2. Interactive Calendar
        // const calendarEl = document.getElementById('calendar');
        // const calendar = new FullCalendar.Calendar(calendarEl, {
        //     initialView: 'dayGridMonth',
        //     height: 450,
        //     events: <?php echo json_encode($events); ?>,
        //     dateClick: function(info) {
        //         // Get all events for the clicked date
        //         const selectedDate = info.dateStr;
        //         const dailyEvents = calendar.getEvents().filter(e => {
        //             // Ensure date format matches for comparison
        //             const eventDate = e.start.toISOString().split('T')[0];
        //             return eventDate === selectedDate;
        //         });

        //         // Populate Modal
        //         document.getElementById('modalDate').innerText = selectedDate;
        //         let listHtml = dailyEvents.length > 0 
        //             ? "<ul class='list-group'>" + dailyEvents.map(e => 
        //                 `<li class='list-group-item'><span class='badge' style='background-color:${e.backgroundColor}'>${e.title}</span></li>`
        //               ).join('') + "</ul>"
        //             : "<p class='text-center text-muted'>No bookings scheduled for this day.</p>";

        //         document.getElementById('modalContent').innerHTML = listHtml;
        //         $('#bookingModal').modal('show');
        //     }
        // });
        // calendar.render();

        var calendarEl = document.getElementById('calendar');

        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            height: 450,
            // Ensure events are passed as a clean JSON array
            events: <?php echo json_encode($events); ?>,

            dateClick: function (info) {
                const clickedDate = info.dateStr; // "2026-05-04"
                const allEvents = calendar.getEvents();

                const dailyEvents = allEvents.filter(event => {
                    // Use the local date parts to avoid the UTC/ISO shift
                    const d = event.start;
                    const year = d.getFullYear();
                    const month = String(d.getMonth() + 1).padStart(2, '0');
                    const day = String(d.getDate()).padStart(2, '0');

                    const eventDate = `${year}-${month}-${day}`;

                    console.log("Comparing:", eventDate, "to", clickedDate);
                    return eventDate === clickedDate;
                });

                document.getElementById('modalDate').innerText = clickedDate;
                let listHtml = "";

                if (dailyEvents.length > 0) {
                    listHtml = '<div class="box-body no-padding"><table class="table table-condensed">';
                    listHtml += '<thead><tr><th>Resource Name</th><th style="width: 40px">Status</th></tr></thead><tbody>';

                    dailyEvents.forEach(e => {
                        const status = e.extendedProps.status || 'Pending';
                        const labelColor = (status.toLowerCase() === 'approved') ? 'label-success' : 'label-warning';

                        listHtml += `
                <tr>
                    <td><b>${e.title}</b></td>
                    <td><span class="label ${labelColor}">${status}</span></td>
                </tr>`;
                    });

                    listHtml += '</tbody></table></div>';
                } else {
                    listHtml = `
            <div class="text-center" style="padding: 20px;">
                <i class="fa fa-calendar-times-o fa-3x text-muted"></i>
                <p>No bookings found for ${clickedDate}.</p>
            </div>`;
                }

                document.getElementById('modalContent').innerHTML = listHtml;
                $('#bookingModal').modal('show');
            }
        });

        calendar.render();
    });
</script>

<?php include "header/footer.php"; ?>