@extends('layouts.main-app')

@section('title', 'SRS Calendar Blocker')

@section('links_css')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.css">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" />
<style>
    @import url('https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap');

    body {
        font-family: 'Nunito', sans-serif;
    }
</style>
@endsection

@section('content')

<div class="container mt-4">
    <div class="d-flex justify-content-end">
        <!-- Button trigger modal -->
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
            Add Event
        </button>

        <!-- Modal -->
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Add Event</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form method="POST" action="/srs/calendar-blocker" method="POST">
                        @csrf
                        <div class="modal-body">
                            <div class="mb-4">
                                <label class="form-label">Event name: </label>
                                <input type="text" class="form-control" name="title" required>
                            </div>

                            <div class="mb-4">
                                <label class="form-label">Date Start: </label>
                                <input type="date" class="form-control" name="start" id="date" required>
                            </div>

                            <div class="mb-4">
                                <label class="form-label">Date End: </label>
                                <input type="date" class="form-control" name="end" required>
                            </div>

                            <div class="mb-4">
                                <label class="form-label">Time Start: </label>
                                <input type="time" class="form-control" name="time_start" required>
                            </div>

                            <div class="mb-4">
                                <label class="form-label">Time End: </label>
                                <input type="time" class="form-control" name="time_end" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Save changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="modal fade" id="editEvent" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Update Event</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form method="POST" action="/srs/calendar-blocker">
                        @method('patch')
                        @csrf
                        <input type="hidden" id="id" name="id" value="">
                        <div class="modal-body">
                            <div class="mb-4">
                                <label class="form-label">Event name: </label>
                                <input id="event_name" type="text" class="form-control" name="event_name" required>
                            </div>

                            <div class="mb-4">
                                <label class="form-label">Date Start: </label>
                                <input id="start" type="date" class="form-control" name="start" required>
                            </div>

                            <div class="mb-4">
                                <label class="form-label">Date End: </label>
                                <input id="end" type="date" class="form-control" name="end" required>
                            </div>

                            <div class="mb-4">
                                <label class="form-label">Time Start: </label>
                                <input id="time_start" type="time" class="form-control" name="time_start" required>
                            </div>

                            <div class="mb-4">
                                <label class="form-label">Time End: </label>
                                <input id="time_end" type="time" class="form-control" name="time_end" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Save changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="card mt-4" style="border-radius: 15px;">
        <div class="card-body ">
            <div class="d-flex justify-content-between">
                <div class="h2 p-3">
                    SRS CALENDAR BLOCKER
                </div>
                <!-- <div>
                    <a href="/usercalendar">To User Page -></a>
                </div> -->
            </div>
            <div class="table-responsive p-3">
                <table class="table table-hover table-md tabled-bordered display" id="table_id">
                    <thead>
                        <tr>
                            <th scope="col">Event Name</th>
                            <th scope="col">Date Start</th>
                            <th scope="col">Date End</th>
                            <th scope="col">Created By</th>
                            <th scope="col">Created At</th>
                            <th scope="col">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($events as $value)
                        <tr>
                            <td>{{$value->title}}</td>
                            <td><?= date('d M, Y (D)', strtotime($value->start)) . " " . date('h:i:a', strtotime($value->time_start)) ?></td>
                            <td><?= date('d M, Y (D)', strtotime($value->end)) . " " . date('h:i:a', strtotime($value->time_end)) ?></td>
                            <td class="text-nowrap text-center">{{$value->created_by}}</td>
                            <td>{{date('d M, Y (D) h:i a', strtotime($value->created_at))}}</td>
                            <td class="d-flex">
                                <div class="me-2 mt-3">
                                    <form action="/srs/calendar-blocker" method="POST">
                                        @method('delete')
                                        @csrf
                                        <input type="hidden" name="delete_id" value="<?= $value->id ?>">
                                        <button onclick="return confirm('Are you sure you want to delete this item?');" class="btn btn-danger btn-md" type="submit">
                                            <i class="far fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </div>
                                <div class="mt-3">
                                    <a data-bs-toggle="modal" data-bs-target="#editEvent" href="javascript:void(0)" onclick="editEvent(<?= $value->id  ?>)" class="btn btn-success btn-md">
                                        <i class="far fa-edit"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection

@section('links_js')
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.js"></script>
<?php
if (session()->has('success')) {
?>

    <script>
        Swal.fire({
            title: '<?php echo  session()->get('success');  ?>',
            icon: 'success',
            showCancelButton: false,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Okay'
        })
    </script>
<?php
}


?>

<script>
    function editEvent(id) {
        $.get('/srs/calendar-blocker/' + id + '/edit', function(event) {

            $('#id').val(event.id);
            $('#event_name').val(event.title);
            // $('#time_start').val(event.time_start);
            // $('#time_end').val(event.time_end);
            $('#time_start').val(event.formattedTimeStart);
            $('#time_end').val(event.formattedTimeEnd);

            const start = new Date(event.start);

            const yyyy = start.getFullYear();
            let mm = start.getMonth() + 1; // Months start at 0!
            let dd = start.getDate();

            if (dd < 10) dd = '0' + dd;
            if (mm < 10) mm = '0' + mm;

            const formattedToday = yyyy + '-' + mm + '-' + dd;
            $('#start').val(formattedToday);

            const end = new Date(event.end);

            const yyyy1 = end.getFullYear();
            let mm1 = end.getMonth() + 1; // Months start at 0!
            let dd1 = end.getDate();

            if (dd1 < 10) dd1 = '0' + dd1;
            if (mm1 < 10) mm1 = '0' + mm1;

            const formattedToday1 = yyyy1 + '-' + mm1 + '-' + dd1;
            $('#end').val(formattedToday1);


        });
    }
</script>
@endsection