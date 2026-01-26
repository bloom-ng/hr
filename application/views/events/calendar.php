
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper bg-[#3E3E3E]">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Events Calendar
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Events</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box border-t-10 border-[#DA7F00] bg-[#2C2C2C]">
                    <div class="box-body">
                        <!-- THE CALENDAR -->
                        <div id="calendar"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- FullCalendar CSS through CDN -->
<link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css' rel='stylesheet' />

<!-- FullCalendar JS through CDN -->
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js'></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay'
            },
            // themeSystem: 'bootstrap5',
            events: '<?php echo base_url("events/get_events"); ?>',
            eventClick: function(info) {
                // Show event details in a modal or alert
                alert('Event: ' + info.event.title + '\nDescription: ' + info.event.extendedProps.description);
            }
        });
        calendar.render();
    });
</script>

<style>
    #calendar {
        max-width: 1100px;
        margin: 40px auto;
        padding: 0 10px;
    }
</style>
