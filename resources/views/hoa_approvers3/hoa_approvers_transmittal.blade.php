@extends('layouts.main-app')

@section('links_css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"
        integrity="sha512-9usAa10IRO0HhonpyAIVpjrylPvoDwiPUiKdWk5t3PyolY1cOd4DSE0Ga+ri4AuTroPR5aQvXU9xC6qOPnzFeg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/select/1.7.0/css/select.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css">
@endsection

@section('content')
    <div class="container-fluid mt-4">
        <div class="card mt-3">
            <div class="card-body p-5">
                <div class="card card-body mb-3">
                    @if (count(auth()->user()->hoa) > 0)
                        @php
                            $hoaNames = [];
                            foreach (auth()->user()->hoa as $hoa) {
                                $hoaNames[] = $hoa['name'];
                            }
                            $implodedNames = implode(', ', $hoaNames);
                        @endphp
                        <h4>Reports</h4>
                        <h5 class="card-title">HOA: {{ $implodedNames }}</h5>
                        <hr>
                        <br>

                        <table class="table report-table table-bordered">
                            <thead>
                                <tr>
                                    <th>Report Name</th>
                                    <th>Period</th>
                                    <th>Filters</th>
                                    <th>Download</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Transmittal Reports</td>
                                    <form action="{{ route('export.transmittal') }}" method="GET">
                                        @csrf
                                        <td>
                                            <label>From:</label>
                                            <input type="date" name="start_date" class="form-control" required>
                                            <label class="mt-2">To:</label>
                                            <input type="date" name="end_date" class="form-control" required>
                                        </td>
                                        <td>
                                          <select class="form-select" name="hoa_id" required >
                                             @if(count(auth()->user()->hoa) > 0)
                                                 @foreach(auth()->user()->hoa as $hoa)
                                                     <option value="{{ $hoa['id'] }}" 
                                                         {{ (auth()->user()->hoa_id == $hoa['id']) ? 'selected' : '' }}>
                                                         {{ $hoa['name'] }}
                                                     </option>
                                                 @endforeach
                                             @else
                                                 <option value="" disabled>No HOA available</option>
                                             @endif
                                         </select>
                                        </td>
                                        <td class="text-center">
                                            <button type="submit" class="icon-btn">
                                                <i class="bi bi-file-earmark-arrow-down"></i>
                                            </button>
                                        </td>
                                    </form>
                                </tr>
                            </tbody>
                        </table>
                    @else
                        <h5 class="card-title text-danger">You are not assigned to any HOA. Please contact the
                            administrator.</h5>
                    @endif
                </div>



            </div>
        </div>
    @endsection

    @section('links_js')
        <script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
        <script src="https://cdn.datatables.net/select/1.7.0/js/dataTables.select.min.js"></script>
        <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        {{-- <script src="{{ asset('js/11hpi3.js') }}"></script> --}}

        @if (session('success'))
            <script>
                Swal.fire({
                    title: '{{ session('success') }}',
                    icon: 'success',
                    showCancelButton: false,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Okay'
                });
            </script>
        @endif
    @endsection
