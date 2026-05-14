<?php include "header/header.php"; ?>

<link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css" rel="stylesheet">

<style>

#calendar{
    background: #fff;
    padding: 20px;
    border-radius: 15px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.08);
}

/* Modal */

.modal-content{
    border-radius: 15px;
}

.booking-item{
    padding: 10px;
    margin-bottom: 10px;
    border-left: 4px solid #007bff;
    background: #f8f9fa;
    border-radius: 8px;
}

</style>

<div class="content-wrapper">

<section class="content-header">
    <h1>Booking Calendar</h1>
</section>

<section class="content">

<div id="calendar"></div>

</section>
</div>

<!-- MODAL -->

<div class="modal fade" id="bookingModal">
<div class="modal-dialog">
<div class="modal-content">

<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">&times;</button>
    <h4 class="modal-title">Bookings</h4>
</div>

<div class="modal-body" id="bookingDetails">

</div>

</div>
</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>

document.addEventListener('DOMContentLoaded', function() {

    var calendar = new FullCalendar.Calendar(document.getElementById('calendar'), {

        initialView: 'dayGridMonth',

        height: 700,

        events: function(fetchInfo, successCallback, failureCallback){

            $.ajax({

                url: "calendar_ajax.php",
                type: "POST",
                dataType: "json",

                success: function(data){
                    successCallback(data);
                }
            });

        },

        /* =====================================
           CLICK DATE
        ===================================== */

        dateClick: function(info){

            $.ajax({

                url: "calendar_day_booking.php",
                type: "POST",

                data:{
                    booking_date: info.dateStr
                },

                success: function(response){

                    $("#bookingDetails").html(response);

                    $("#bookingModal").modal("show");
                }

            });

        },

        /* =====================================
           CLICK EVENT
        ===================================== */

        eventClick: function(info){

            let html = `
                <div class="booking-item">
                    <h4>${info.event.title}</h4>

                    <p>
                        <b>Start:</b> ${info.event.start}
                    </p>

                    <p>
                        <b>End:</b> ${info.event.end}
                    </p>
                </div>
            `;

            $("#bookingDetails").html(html);

            $("#bookingModal").modal("show");
        }

    });

    calendar.render();

});

</script>

<?php include "header/footer.php"; ?>