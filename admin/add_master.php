<?php
include("../database/db_connect.php");
$conn = $db_handle->conn;
$table = $_GET['table'];

// Get columns excluding auto-increment ones[cite: 1]
$columns = [];
$result = $conn->query("SHOW COLUMNS FROM $table");

while($row = $result->fetch_assoc()) {
    if($row['Extra'] != 'auto_increment') {
        $columns[] = $row['Field'];
    }
}

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $values = [];
    foreach($columns as $col){
        $values[] = "'".$conn->real_escape_string($_POST[$col])."'";
    }

    $sql = "INSERT INTO $table (".implode(",", $columns).") VALUES (".implode(",", $values).")";
    $conn->query($sql);

    // Redirect back to dashboard
    header("Location: class_crud_new.php?table=$table");
    exit();
}
?>

<!-- UI Styling[cite: 5] -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3 class="fw-bold text-dark">
                    <i class="fas fa-plus-square text-success me-2"></i>Add to <?php echo strtoupper(str_replace('_', ' ', $table)); ?>
                </h3>
                <a href="class_crud_new.php?table=<?php echo $table; ?>" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left"></i> Back
                </a>
            </div>

            <div class="card shadow border-0">
                <div class="card-body p-4">
                    <form method="POST">
                        <div class="row">
                            <?php foreach($columns as $col): ?>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold text-muted small">
                                        <?php echo strtoupper(str_replace('_', ' ', $col)); ?>
                                    </label>
                                    <input type="text" name="<?php echo $col; ?>" class="form-control shadow-sm" required>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <div class="mt-3">
                            <button class="btn btn-success px-5">Save Record</button>
                            <a href="class_crud_new.php?table=<?php echo $table; ?>" class="btn btn-link">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>