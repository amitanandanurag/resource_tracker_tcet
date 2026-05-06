<?php include "header/header.php"; ?>

<?php $db = new DBController(); ?>

<div class="content-wrapper">

    <section class="content-header">
        <h1><i class="fa fa-calendar"></i> Book Resource</h1>
    </section>

    <section class="content">

        <div class="box box-primary">

            <form method="POST" action="booking_save.php">

                <div class="box-body">
                    <div class="row">

                        <div class="col-md-6">

                            <!-- DEPARTMENT -->
                            <div class="form-group">
                                <label>Department</label>
                                <select name="department_id" class="form-control" required>
                                    <option value="">Select Department</option>
                                    <?php
                                    $res = $db->runQuery("SELECT * FROM rt_department_master");
                                    foreach ($res as $r) {
                                        echo "<option value='" . $r['department_id'] . "'>" . $r['department_name'] . "</option>";
                                    }
                                    ?>
                                </select>
                            </div>

                            <!-- CLASS -->
                            <div class="form-group">
                                <label>Class</label>
                                <select name="class_id" class="form-control" required>
                                    <option value="">Select Class</option>
                                    <?php
                                    $res = $db->runQuery("SELECT * FROM rt_class_master");
                                    foreach ($res as $r) {
                                        echo "<option value='" . $r['class_id'] . "'>" . $r['class_name'] . "</option>";
                                    }
                                    ?>
                                </select>
                            </div>

                            <!-- DIVISION -->
                            <div class="form-group">
                                <label>Division</label>
                                <select name="division_id" class="form-control" required>
                                    <option value="">Select Division</option>
                                    <?php
                                    $res = $db->runQuery("SELECT * FROM rt_division_master");
                                    foreach ($res as $r) {
                                        echo "<option value='" . $r['division_id'] . "'>" . $r['division_name'] . "</option>";
                                    }
                                    ?>
                                </select>
                            </div>

                            <!-- DATE -->
                            <div class="form-group">
                                <label>Date</label>
                                <input type="date" name="booking_date" class="form-control" required>
                            </div>

                        </div>

                        <div class="col-md-6">

                            <!-- SLOT -->
                            <div class="form-group">
                                <label>Time Slot</label>
                                <select name="slot_id" class="form-control" required>
                                    <option value="">Select Slot</option>
                                    <?php
                                    $slots = $db->runQuery("SELECT * FROM rt_time_slots WHERE is_active=1");
                                    foreach ($slots as $s) {
                                        echo "<option value='" . $s['slot_id'] . "'>" . $s['label'] . "</option>";
                                    }
                                    ?>
                                </select>
                            </div>

                            <!-- RESOURCE TYPE -->
                            <div     class="form-group">
                                <label>Resource Type</label>
                                <select id="resource_type" class="form-control" required>
                                    <option value="">Select Type</option>
                                    <?php
                                    $types = $db->runQuery("SELECT * FROM rt_resource_types");
                                    foreach ($types as $t) {
                                        echo "<option value='" . $t['type_id'] . "'>" . $t['type_name'] . "</option>";
                                    }
                                    ?>
                                </select>
                            </div>

                            <!-- RESOURCE -->
                            <div class="form-group">
                                <label>Resource</label>
                                <select id="resource" name="resource_id" class="form-control" required>
                                    <option value="">Select Resource</option>
                                </select>
                            </div>

                            <!-- PURPOSE -->
                            <div class="form-group">
                                <label>Purpose</label>
                                <textarea name="purpose" class="form-control"></textarea>
                            </div>

                        </div>

                    </div>
                </div>

                <div class="box-footer text-center">
                    <button class="btn btn-success">Book Resource</button>
                </div>

            </form>

            </div>
  
        </section>
  </div>
    
<!-- jQuery (make sure it's loaded once) -->
    <script src="https://code.jquery.co m /jquery- 3.6.0.m in .js"></script> 
    
    <!-- 🔥 ADD YOUR SCRIPT HERE -->
<script>
    $(document).ready(function(){

        $("#resource_type").change(function(){

        var type_id = $(this).val();

        $.post("get_resources.php",{type_id:type_id},function(data){
            $("#resource").html(data);
        });

    });

});
</script>

<?php include "header/footer.php"; ?>