<?php
include "../database/db_connect.php";
$db_handle = new DBController();

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id == 0) {
    die("Invalid Resource ID");
}

$query = "SELECT * FROM rt_resources WHERE resource_id = $id";
$result = $db_handle->conn->query($query);

if (!$result || $result->num_rows == 0) {
    die("No resource found");
}

$row = $result->fetch_assoc();

// SAFE VALUES
$name = $row['resource_name'] ?? '';
$code = $row['code'] ?? '';
$type_id = $row['type_id'] ?? '';
$department_id = $row['department_id'] ?? '';
$capacity = $row['capacity'] ?? '';
$building = $row['building'] ?? '';
$floor = $row['floor'] ?? '';
$description = $row['description'] ?? '';
$is_active = $row['is_active'] ?? 1;
?>

<?php include "header/header.php"; ?>

<div class="content-wrapper">
    <section class="content-header">
        <h1>Edit Resource</h1>
    </section>

    <section class="content">
        <form action="update_resource.php" method="POST">

            <input type="hidden" name="resource_id" value="<?= $id ?>">

            <div class="box box-default">
                <div class="box-header with-border">
                    <h3 class="box-title">RESOURCE DETAILS</h3>
                </div>

                <div class="box-body">

                    <div class="row">

                        <div class="col-md-4">
                            <label>Resource Name</label>
                            <input type="text" name="resource_name" value="<?= htmlspecialchars($name) ?>"
                                class="form-control" required>
                        </div>

                        <div class="col-md-4">
                            <label>Code (Auto Generated)</label>
                            <input type="text" value="<?= htmlspecialchars($code) ?>" class="form-control" disabled>
                        </div>

                        <div class="col-md-4">
                            <label>Type</label>
                            <select name="type_id" class="form-control">
                                <option value="">Select Type</option>
                                <?php
                                $res = $db_handle->conn->query("SELECT * FROM rt_resource_types");
                                while ($t = $res->fetch_assoc()) {
                                    ?>
                                    <option value="<?= $t['type_id'] ?>" <?= ($t['type_id'] == $type_id ? 'selected' : '') ?>>
                                        <?= $t['type_name'] ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>

                    </div>

                    <div class="row">

                        <div class="col-md-4">
                            <label>Department</label>
                            <select name="department_id" class="form-control">
                                <option value="">Select Department</option>
                                <?php
                                $res = $db_handle->conn->query("SELECT * FROM rt_department_master");
                                while ($d = $res->fetch_assoc()) {
                                    ?>
                                    <option value="<?= $d['department_id'] ?>" <?= ($d['department_id'] == $department_id ? 'selected' : '') ?>>
                                        <?= $d['department_name'] ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label>Capacity</label>
                            <input type="number" name="capacity" value="<?= htmlspecialchars($capacity) ?>"
                                class="form-control">
                        </div>

                        <div class="col-md-4">
                            <label>Building</label>
                            <input type="text" name="building" value="<?= htmlspecialchars($building) ?>"
                                class="form-control">
                        </div>

                    </div>

                    <div class="row">

                        <div class="col-md-4">
                            <label>Floor</label>
                            <input type="text" name="floor" value="<?= htmlspecialchars($floor) ?>"
                                class="form-control">
                        </div>

                        <div class="col-md-4">
                            <label>Status</label>
                            <select name="is_active" class="form-control">
                                <option value="1" <?= ($is_active == 1 ? 'selected' : '') ?>>Active</option>
                                <option value="0" <?= ($is_active == 0 ? 'selected' : '') ?>>Inactive</option>
                            </select>
                        </div>

                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <label>Description</label>
                            <textarea name="description"
                                class="form-control"><?= htmlspecialchars($description) ?></textarea>
                        </div>
                    </div>

                    <div class="text-center" style="margin-top:20px;">
                        <button type="submit" class="btn btn-success">Update</button>
                        <button type="button" onclick="history.back()" class="btn btn-danger">Cancel</button>
                    </div>

                </div>
            </div>

        </form>
    </section>
</div>

<?php include "header/footer.php"; ?>