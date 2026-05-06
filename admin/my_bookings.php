<?php include "header/header.php"; ?>

<div class="content-wrapper">

<section class="content-header">
    <h1>My Bookings</h1>
</section>

<section class="content">

<div class="box">
<div class="box-body">

<table id="table" class="table table-bordered table-striped">
<thead>
<tr>
<th>ID</th>
<th>Resource</th>
<th>Date</th>
<th>Slot</th>
<th>Status</th>
<th>Action</th>
</tr>
</thead>
</table>

</div>
</div>

</section>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.js"></script>

<script>
$(document).ready(function(){

$('#table').DataTable({
    ajax:"my_booking_ajax.php",
    columns:[
        {data:"id"},
        {data:"resource"},
        {data:"date"},
        {data:"slot"},
        {data:"status"},
        {data:"action"}
    ]
});

});

// cancel function
function cancel(id){
    if(confirm("Cancel booking?")){
        $.post("booking_cancel.php",{id:id},function(res){
            alert(res);
            $('#table').DataTable().ajax.reload();
        });
    }
}
</script>

<?php include "header/footer.php"; ?>