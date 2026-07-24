<?php include "header/header.php"; ?>

<div class="content-wrapper">

<section class="content-header">
    <h1>
        <i class="fa fa-users"></i> ROLE MANAGEMENT
    </h1>
</section>

<section class="content">

<div class="box box-primary">

<div class="box-header">

    <h3 class="box-title">
        Manage User Roles
    </h3>

</div>

<div class="box-body">

<table id="roleTable" class="table table-bordered table-striped" width="100%">

<thead>

<tr>

    <th>SR</th>
    <th>Name</th>
    <th>Email</th>
    <th>Department</th>
    <th>Current Role</th>
    <th>New Role</th>
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

    $('#roleTable').DataTable({

        processing:true,

        serverSide:true,

        destroy:true,

        ajax:{

            url:"role_ajax.php",

            type:"POST"

        },

        columns:[

            {data:"sr"},
            {data:"name"},
            {data:"email"},
            {data:"department"},
            {data:"current_role"},
            {data:"new_role",orderable:false},
            {data:"action",orderable:false}

        ]

    });

});

function updateRole(user_id)
{

    var role_id=$("#role_"+user_id).val();

    if(role_id=="")
    {
        alert("Please Select Role");
        return;
    }

    if(confirm("Are you sure you want to change this role?"))
    {

        $.ajax({

            url:"role_update.php",

            type:"POST",

            data:{
                user_id:user_id,
                role_id:role_id
            },

            success:function(response)
            {

                if(response==1)
                {
                    alert("Role Updated Successfully");

                    $('#roleTable').DataTable().ajax.reload(null,false);
                }
                else
                {
                    alert("Unable to Update Role");
                }

            }

        });

    }

}

</script>

<?php include "header/footer.php"; ?>