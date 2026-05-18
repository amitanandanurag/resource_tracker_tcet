<?php
include("../database/db_connect.php");
$conn = $db_handle->conn;

$table = $_GET['table'];
$id = $_GET['id'];

// Primary key = first column
$col_res = $conn->query("SHOW COLUMNS FROM $table");
$columns = [];

while ($col = $col_res->fetch_assoc()) {
    $columns[] = $col['Field'];
}

$pk = $columns[0];

$data = $conn->query("SELECT * FROM $table WHERE $pk='$id'")->fetch_assoc();

if ($_POST) {
    $updates = [];

    foreach ($columns as $col) {
        if (
            $col != $pk &&
            $col != 'status' &&
            $col != 'created_at' &&
            $col != 'date'
        ) {
            $updates[] = "$col='" . $_POST[$col] . "'";
        }
    }

    $sql = "UPDATE $table SET " . implode(",", $updates) . " WHERE $pk='$id'";
    $conn->query($sql);

    // Redirecting back to the main dashboard (masters.php) after update
    header("Location: class_crud_new.php?table=$table");
}
?>

<!-- Include Bootstrap CSS for the "nice" look[cite: 5] -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">

            <!-- Navigation Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3 class="fw-bold text-dark">
                    <i class="fas fa-edit text-primary me-2"></i>Edit <?php echo strtoupper(str_replace('_', ' ', $table)); ?>
                </h3>
                <!-- Fixed Back Link to masters.php[cite: 4] -->
                <a href="class_crud_new.php?table=<?php echo $table; ?>" class="btn btn-outline-secondary shadow-sm">
                    <i class="fas fa-arrow-left me-1"></i> Back to Dashboard
                </a>
            </div>

            <!-- Enhanced Edit Card -->
            <div class="card shadow-lg border-0 rounded-3">
                <div class="card-header bg-primary text-white py-3">
                    <h5 class="card-title mb-0">Record Details (ID: <?php echo $id; ?>)</h5>
                </div>
                <div class="card-body p-4">
                    <form method="POST">
                        <div class="row">
                            <?php foreach ($columns as $col): ?>
                                <?php if (
                                    $col != $pk &&
                                    $col != 'status' &&
                                    $col != 'created_at' &&
                                    $col != 'date'
                                ): ?>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-bold text-muted small">
                                            <?php echo strtoupper(str_replace('_', ' ', $col)); ?>
                                        </label>
                                        <input type="text"
                                            name="<?php echo $col; ?>"
                                            value="<?php echo $data[$col]; ?>"
                                            class="form-control form-control-lg shadow-sm border-2"
                                            required>
                                    </div>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </div>

                        <hr class="my-4">

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary btn-lg px-5 shadow">
                                <i class="fas fa-save me-2"></i>Update Changes
                            </button>
                            <a href="class_crud_new.php?table=<?php echo $table; ?>" class="btn btn-link">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>