<?php include "header/header.php"; ?>

<div class="content-wrapper">
<section class="content-header">
    <h1><i class="fa fa-building"></i> RESOURCE LIST</h1>
</section>

<section class="content">

<div class="box box-primary">

<div class="box-header">
    <button onclick="window.location.href='resources_addition.php'" class="btn btn-primary">
        + ADD RESOURCE
    </button>
</div>

<div class="box-body">

<table id="resourceTable" class="table table-bordered table-striped" width="100%">
<thead>
<tr>
    <th>SR</th>
    <th>Name</th>
    <th>Code</th>
    <th>Type</th>
    <th>Department</th>
    <th>Capacity</th>
    <th>Building</th>
    <th>Floor</th>
    <th>Status</th>
    <th>Action</th>
</tr>
</thead>
</table>

</div>
</div>

</section>
</div>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.js"></script>

<script>
$(document).ready(function(){

    $('#resourceTable').DataTable({
        processing: true,
        serverSide: true,
        destroy: true,

        ajax: {
            url: "resource_ajax.php",
            type: "POST"
        },

        columns: [
            { data: "sr" },
            { data: "name" },
            { data: "code" },
            { data: "type" },
            { data: "department" },
            { data: "capacity" },
            { data: "building" },
            { data: "floor" },
            { data: "status", orderable: false },
            { data: "action", orderable: false }
        ]
    });

});

// DELETE FUNCTION
function deleteResource(id){

    if(confirm("Are you sure you want to delete this resource?")){

        $.ajax({
            url: "resource_delete.php",
            type: "POST",
            data: { id: id },

            success: function(response){
                $('#resourceTable').DataTable().ajax.reload(null, false);
                alert("Resource deleted successfully");
            },

            error: function(){
                alert("Error deleting resource");
            }
        });

    }
}
</script>

<?php include "header/footer.php"; ?>