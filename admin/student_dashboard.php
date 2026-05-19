<?php
require "header/header.php"; 
$db = $db_handle->conn;

// 1. Initialize variables from POST or set to null
$selected_class = isset($_POST['class_id']) ? $_POST['class_id'] : null;
$selected_div = isset($_POST['division_id']) ? $_POST['division_id'] : null;

// 2. Fetch the Student's Department ID (This IS in your DB)
$dept_query = "SELECT department_id FROM rt_user_master WHERE user_id = '$userid'";
$dept_res = mysqli_query($db, $dept_query);
$s_dept = mysqli_fetch_assoc($dept_res)['department_id'];

// 3. Fetch Master Data for Dropdowns (to make them dynamic)
$classes = mysqli_query($db, "SELECT * FROM rt_class_master");
$divisions = mysqli_query($db, "SELECT * FROM rt_division_master");
?>

<div class="content-wrapper">
    <section class="content-header">
        <h1>Student Resource Schedule</h1>
    </section>

    <section class="content">
        <div class="box box-default">
            <div class="box-header with-border">
                <h3 class="box-title">Select your Class and Division</h3>
            </div>
            <div class="box-body">
                <form method="POST" action="">
                    <div class="row">
                        <div class="col-md-4">
                            <label>Class</label>
                            <select name="class_id" class="form-control" required>
                                <option value="">-- Select Class --</option>
                                <?php while($c = mysqli_fetch_assoc($classes)): ?>
                                    <option value="<?php echo $c['class_id']; ?>" <?php echo ($selected_class == $c['class_id']) ? 'selected' : ''; ?>>
                                        <?php echo $c['class_name']; ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label>Division</label>
                            <select name="division_id" class="form-control" required>
                                <option value="">-- Select Division --</option>
                                <?php while($d = mysqli_fetch_assoc($divisions)): ?>
                                    <option value="<?php echo $d['division_id']; ?>" <?php echo ($selected_div == $d['division_id']) ? 'selected' : ''; ?>>
                                        <?php echo $d['division_name']; ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label>&nbsp;</label>
                            <button type="submit" class="btn btn-primary btn-block">Show Bookings</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <?php if($selected_class && $selected_div): ?>
            <?php
            // Fetch bookings based on selection + Student's Dept
            $query = "SELECT b.*, r.resource_name, s.start_time, s.end_time 
                      FROM rt_bookings b
                      JOIN rt_resources r ON b.resource_id = r.resource_id
                      JOIN rt_time_slots s ON b.slot_id = s.slot_id
                      WHERE b.department_id = '$s_dept' 
                      AND b.class_id = '$selected_class' 
                      AND b.division_id = '$selected_div'
                      AND b.status = 'Approved'
                      AND b.booking_date >= CURDATE()
                      ORDER BY b.booking_date ASC";
            $results = mysqli_query($db, $query);
            ?>

            <div class="box box-success">
                <div class="box-body table-responsive no-padding">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Resource</th>
                                <th>Time</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(mysqli_num_rows($results) > 0): ?>
                                <?php while($row = mysqli_fetch_assoc($results)): ?>
                                    <tr>
                                        <td><?php echo date('d-M-Y', strtotime($row['booking_date'])); ?></td>
                                        <td><b><?php echo $row['resource_name']; ?></b></td>
                                        <td><?php echo $row['start_time']; ?> - <?php echo $row['end_time']; ?></td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr><td colspan="4" class="text-center">No active bookings found for your selection.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php else: ?>
            <div class="callout callout-info">
                <h4>Information</h4>
                <p>Please select your Class and Division above to view active resource schedules.</p>
            </div>
        <?php endif; ?>
    </section>
</div>

<?php include "header/footer.php"; ?>