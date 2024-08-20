@extends('layouts.main-app')

@section('title', 'Sticker Application Appointments')

@section('links_css')
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.css">
@endsection

@section('content')
<div class="container-fluid mt-3">
    <div id="calendar"></div>
</div>

<div class="modal fade" id="viewAppointmentModal" tabindex="-1" aria-labelledby="viewAppointmentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewAppointmentModalLabel">Sticker Appointment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row apt-view">
                    <div class="row">
                        <div class="col-md-4">
                            SRS #: 
                        </div>
                        <div class="col-md-8">
                            <div id="request_id"></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            Name: 
                        </div>
                        <div class="col-md-8">
                            <div id="appointment_name"></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            Date: 
                        </div>
                        <div class="col-md-8">
                            <div id="appointment_date"></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            Time: 
                        </div>
                        <div class="col-md-8">
                            <div id="appointment_time"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('links_js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.17.1/moment.min.js"></script>
{{-- <script src="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.js"></script> --}}
{{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.1.0/fullcalendar.min.js"></script> --}}
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.js"></script>
<script>
$(document).ready(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    });

    let colors = [
        '#3ec2a1',
        '#1c8c2f',
        '#d8bb37',
        '#d83f37',
        '#d8375a',
        'purple',
        'orange',
        'chocolate',
        'darkblue',
        'darkseagreen',
        'fuchsia',
        'lightcoral'
    ];

    let lastColor = '';

    getRandomColor = () => {
        let color = '';

        do {
            color = colors[Math.floor(Math.random() * colors.length)];
        } while(color == lastColor)

        lastColor = color;

        return color;
    }

    var calendarEl = document.getElementById('calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'timeGridDay',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        slotDuration: '00:05:00',
        displayEventTime : false,
        eventOverlap: false,
        events: '/srs/appointments',
        eventClick: function(info) {
            $('#viewAppointmentModal #request_id').html(info.event.extendedProps.srs);
            $('#viewAppointmentModal #appointment_name').text(info.event.extendedProps.name);
            $('#viewAppointmentModal #appointment_date').text(info.event.extendedProps.date);
            $('#viewAppointmentModal #appointment_time').text(info.event.extendedProps.time);
            $('#viewAppointmentModal').modal('show');
        },
        eventOrder: false
    });


    calendar.render();


});
</script>
@endsection