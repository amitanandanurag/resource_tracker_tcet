<?php include "header/header.php"; ?>

<div class="content-wrapper">

<section class="content-header">
    <h1>Audit Logs</h1>
</section>

<section class="content">

<div class="box">
<div class="box-body">

<table id="table" class="table table-bordered table-striped">

<thead>

<tr>
    <th>ID</th>
    <th>User</th>
    <th>Action</th>
    <th>Table</th>
    <th>Record</th>
    <th>Description</th>
    <th>Date Time</th>
</tr>

</thead>

</table>

</div>
</div>

</section>
</div>

<script>

$('#table').DataTable({

ajax:{
    url:'audit_log_ajax.php',
    type:'POST'
},

columns:[

{data:'id'},
{data:'user'},
{data:'action'},
{data:'table'},
{data:'record'},
{data:'description'},
{data:'date'}

]

});

</script>

<?php include "header/footer.php"; ?>