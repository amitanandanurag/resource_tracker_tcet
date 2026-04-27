<?php include "header/header.php"; ?>

<script>
    function validateResourceForm() {
        if (document.myform.name.value == "") {
            alert("Enter Resource Name");
            return false;
        }
        if (document.myform.type_id.value == "") {
            alert("Select Resource Type");
            return false;
        }
        return true;
    }
</script>

<div class="content-wrapper">
    <section class="content-header">
        <h1>Add Resource</h1>
    </section>

    <section class="content">
        <form action="resource_save.php" name="myform" method="POST" onsubmit="return validateResourceForm()">

            <div class="box box-default">
                <div class="box-header with-border">
                    <h3 class="box-title">RESOURCE DETAILS</h3>
                </div>

                <div class="box-body">

                    <div class="row">

                        <!-- Name -->
                        <div class="col-md-4">
                            <label>Resource Name *</label>
                            <input type="text" name="resource_name" class="form-control" required>
                        </div>

                        <!-- Code -->
                        <!-- <div class="col-md-4">
                            <label>Code</label>
                            <input type="text" name="code" class="form-control">
                        </div> -->

                        <!-- Type -->
                        <div class="col-md-4">
                            <label>Type *</label>
                            <select name="type_id" class="form-control" required>
                                <option value="">Select Type</option>
                                <?php
                                $res = $db_handle->conn->query("SELECT * FROM rt_resource_types");
                                while ($row = $res->fetch_assoc()) {
                                    ?>
                                    <option value="<?= $row['type_id'] ?>">
                                        <?= $row['type_name'] ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>

                    </div>

                    <div class="row">

                        <!-- Department -->
                        <div class="col-md-4">
                            <label>Department</label>
                            <select name="department_id" class="form-control">
                                <option value="">Select Department</option>
                                <?php
                                $res = $db_handle->conn->query("SELECT * FROM rt_department_master");
                                while ($row = $res->fetch_assoc()) {
                                    ?>
                                    <option value="<?= $row['department_id'] ?>">
                                        <?= $row['department_name'] ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>

                        <!-- Capacity -->
                        <div class="col-md-4">
                            <label>Capacity</label>
                            <input type="number" name="capacity" class="form-control">
                        </div>

                        <!-- Building -->
                        <div class="col-md-4">
                            <label>Building</label>
                            <input type="text" name="building" class="form-control">
                        </div>

                    </div>

                    <div class="row">

                        <!-- Floor -->
                        <div class="col-md-4">
                            <label>Floor</label>
                            <input type="text" name="floor" class="form-control">
                        </div>

                        <!-- Status -->
                        <div class="col-md-4">
                            <label>Status</label>
                            <select name="is_active" class="form-control">
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>

                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <label>Description</label>
                            <textarea name="description" class="form-control"></textarea>
                        </div>
                    </div>

                    <div class="text-center" style="margin-top:20px;">
                        <button type="submit" class="btn btn-success">Save</button>
                        <button type="reset" class="btn btn-danger">Reset</button>
                    </div>

                </div>
            </div>

        </form>
    </section>
</div>

<?php include "header/footer.php"; ?>