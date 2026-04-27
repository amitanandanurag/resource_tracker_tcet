<?php include "header/header.php"; ?>

<div class="content-wrapper">
<section class="content-header">
    <h1><i class="fa fa-building"></i> RESOURCE LIST</h1>
</section>

<section class="content">
<div class="box box-primary">

<div class="box-header">
    <div class="row">

        <div class="col-md-2">
            <button onclick="window.location.href='resources_addition.php'" class="btn btn-primary btn-block">
                <i class="fa fa-plus"></i> ADD RESOURCE
            </button>
        </div>

    </div>
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

<?php include "header/footer.php"; ?>

<script>
$(document).ready(function(){

$('#resourceTable').DataTable({
    "processing": true,
    "serverSide": true,

    "ajax": {
        "url": "resource_ajax.php",
        "type": "POST"
    },

    "columns": [
        { data: "sr" },
        { data: "name" },
        { data: "code" },
        { data: "type_name" },
        { data: "department_name" },
        { data: "capacity" },
        { data: "building" },
        { data: "floor" },
        { data: "status" },
        { data: "action" }
    ]
});

});
</script>

<script>
function deleteResource(id){

if(confirm("Are you sure to delete?")){
    $.post("resource_delete.php",{id:id},function(){
        $('#resourceTable').DataTable().ajax.reload();
    });
}

}
</script>