<?php include "header/header.php"; ?>

<link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css" rel="stylesheet">

<div class="content-wrapper">
<section class="content-header">
    <h1>Booking Calendar</h1>
</section>

<section class="content">
<div id="calendar"></div>
</section>
</div>

<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {

    var calendar = new FullCalendar.Calendar(document.getElementById('calendar'), {
        initialView: 'dayGridMonth',

        events: function(fetchInfo, successCallback, failureCallback){
            $.ajax({
                url: "calendar_ajax.php",
                type: "POST",
                dataType: "json",
                success: function(data){
                    successCallback(data);
                }
            });
        }
    });

    calendar.render();
});
</script>

<?php include "header/footer.php"; ?>