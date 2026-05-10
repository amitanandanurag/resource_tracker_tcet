<?php
require "header/header.php";
include_once("header/side_menu.php");
include_once("../database/db_connect.php");
$conn = $db_handle->conn;

// Get all master tables
$tables = [];
$result = $conn->query("SHOW TABLES LIKE '%_master'");

while ($row = $result->fetch_array()) {
    $tables[] = $row[0];
}
// Active tab
$activeTable = $_GET['table'] ?? $tables[0];
?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<div class="content-wrapper">
<section class="content mt-3">

<div class="container-fluid">

    <div class="mb-3">
        <h3 class="text-dark d-flex align-items-center">
            <i class="fas fa-cog me-2 text-secondary"></i>Master Data
        </h3>
    </div>

    <ul class="nav nav-tabs mb-4 border-bottom-0">
        <?php foreach($tables as $table): 
            $displayName = ucwords(str_replace(['rt_', '_master', '_'], ['', '', ' '], $table));
        ?>
            <li class="nav-item">
                <a class="nav-link <?php echo ($activeTable == $table) ? 'active fw-bold text-primary border-0 border-bottom border-primary border-3' : 'text-muted border-0'; ?>"
                   href="?table=<?php echo $table; ?>" style="background: transparent;">
                   <?php echo $displayName; ?>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <a href="add_master.php?table=<?php echo $activeTable; ?>" 
        class="btn btn-success rounded shadow-sm d-flex align-items-center justify-content-start" 
        style="width: 42px; height: 42px;">
            <i class="fas fa-plus fs-5"></i>
        </a>
    </div>

    <div class="card shadow-sm border-0 rounded-3">
        <div class="card-body p-0"> <?php
            // Fetch columns
            $columns = [];
            $col_res = $conn->query("SHOW COLUMNS FROM $activeTable");
            while($col = $col_res->fetch_assoc()){
                if($col['Field'] != 'status') { // Hide status from UI
                    $columns[] = $col['Field'];
                }
            }

            // Fetch data (Soft Delete Filter)
            $data = $conn->query("SELECT * FROM $activeTable WHERE status = 1");
            ?>

            <table id="masterTable" class="table table-hover mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="text-muted small fw-bold">NO.</th>
                        <?php foreach($columns as $col): ?>
                            <th class="text-muted small fw-bold"><?php echo strtoupper(str_replace('_', ' ', $col)); ?></th>
                        <?php endforeach; ?>
                        <th class="text-muted small fw-bold text-center">ACTIONS</th>
                    </tr>
                </thead>

                <tbody>
                    <?php $no = 1; while($row = $data->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $no++; ?></td>
                            <?php foreach($columns as $col): ?>
                                <td><?php echo $row[$col]; ?></td>
                            <?php endforeach; ?>

                            <td class="text-center">
                                <a href="edit_master.php?table=<?php echo $activeTable; ?>&id=<?php echo $row[$columns[0]]; ?>"
                                   class="text-primary me-3"><i class="fas fa-edit fs-10"></i></a>

                                <a href="delete_master.php?table=<?php echo $activeTable; ?>&id=<?php echo $row[$columns[0]]; ?>"
                                   class="text-danger"
                                   onclick="return confirm('Are you sure you want to delete this record?')">
                                   <i class="fas fa-trash-alt fs-10"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

</section>
</div>
<script>
$(document).ready(function() {
    $('#masterTable').DataTable({
        pageLength: 5
    });
});
</script>
?>
