<?php include "header/header.php"; ?>

<div class="content-wrapper">

    <section class="content-header">
        <h1>Pending Bookings</h1>
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
    $(document).ready(function () {

        $('#table').DataTable({
            processing: true,
            ajax: {
                url: "pending_booking_ajax.php",
                type: "POST"
            },
            columns: [
                { data: "id" },
                { data: "resource" },
                { data: "date" },
                { data: "slot" },
                { data: "action" }
            ]
        });

    });

    // APPROVE
    function approve(id) {
        if (confirm("Approve booking?")) {
            $.post("booking_approve.php", { id: id, decision: "approved" }, function (res) {
                alert(res);
                $('#table').DataTable().ajax.reload();
            });
        }
    }

    // REJECT
    function reject(id) {
        if (confirm("Reject booking?")) {
            $.post("booking_approve.php", { id: id, decision: "rejected" }, function (res) {
                alert(res);
                $('#table').DataTable().ajax.reload();
            });
        }
    }
</script>

<?php include "header/footer.php"; ?>