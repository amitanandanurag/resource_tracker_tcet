<?php include "header/header.php"; ?>
<script src="https://code.jquery.com/jquery-3.3.1.js"></script>

<div class="content-wrapper">
    <section class="content">
        <section class="content-header">
            <h1><i class="fa fa-building"></i> RESOURCE CONCISE DETAILS</h1>
        </section>

        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">

                    <div class="box-header with-border">
                        <h3 class="box-title">Filter By</h3>
                    </div>

                    <table class="table table-bordered">
                        <thead>
                            <tr>

                                <!-- TYPE -->
                                <th>
                                    <select class="form-control" id="select_type">
                                        <option value="">Type</option>
                                        <?php
                                        $res = $db_handle->query("SELECT * FROM rt_resource_types");
                                        while ($row = $res->fetch_assoc()) {
                                            ?>
                                            <option value="<?= $row['type_id'] ?>">
                                                <?= $row['type_name'] ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                </th>

                                <!-- DEPARTMENT -->
                                <th>
                                    <select class="form-control" id="select_department">
                                        <option value="">Department</option>
                                        <?php
                                        $res = $db_handle->query("SELECT * FROM rt_department_master");
                                        while ($row = $res->fetch_assoc()) {
                                            ?>
                                            <option value="<?= $row['department_id'] ?>">
                                                <?= $row['department_name'] ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                </th>

                                <!-- BUILDING -->
                                <th>
                                    <input type="text" id="select_building" class="form-control" placeholder="Building">
                                </th>

                                <!-- FLOOR -->
                                <th>
                                    <input type="text" id="select_floor" class="form-control" placeholder="Floor">
                                </th>

                                <!-- BUTTONS -->
                                <th>
                                    <button id="applyFilters" class="btn btn-primary">Apply</button>
                                    <button id="resetFilters" class="btn btn-danger">Reset</button>
                                </th>

                            </tr>
                        </thead>
                    </table>

                    <!-- TABLE -->
                    <table id="resourceTable" class="table table-bordered">
                        <thead>
                            <tr>
                                <th>SR NO</th>
                                <th>Type</th>
                                <th>Department</th>
                                <th>Building</th>
                                <th>Floor</th>
                                <th>Resource Count</th>
                            </tr>
                        </thead>
                    </table>

                </div>
            </div>
        </div>

    </section>
</div>

<script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.js"></script>

<script>
    $(document).ready(function () {

        fetch_data();

        function fetch_data() {

            $('#resourceTable').DataTable({
                destroy: true,
                processing: true,
                serverSide: true,
                searching: false,

                ajax: {
                    url: "resource_concise_details_ajax.php",
                    type: "POST",
                    data: function (d) {
                        d.select_type = $('#select_type').val();
                        d.select_department = $('#select_department').val();
                        d.select_building = $('#select_building').val();
                        d.select_floor = $('#select_floor').val();
                    }
                }
            });
        }

        // APPLY
        $('#applyFilters').click(function () {
            $('#resourceTable').DataTable().destroy();
            fetch_data();
        });

        // RESET
        $('#resetFilters').click(function () {
            $('#select_type').val('');
            $('#select_department').val('');
            $('#select_building').val('');
            $('#select_floor').val('');

            $('#resourceTable').DataTable().destroy();
            fetch_data();
        });

    });
</script>

<?php include "header/footer.php"; ?>