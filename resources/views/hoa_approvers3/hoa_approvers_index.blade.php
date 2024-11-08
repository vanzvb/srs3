@extends('layouts.main-app')

@section('links_css')
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"
      integrity="sha512-9usAa10IRO0HhonpyAIVpjrylPvoDwiPUiKdWk5t3PyolY1cOd4DSE0Ga+ri4AuTroPR5aQvXU9xC6qOPnzFeg=="
      crossorigin="anonymous" referrerpolicy="no-referrer" />
   <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
   <link rel="stylesheet" href="https://cdn.datatables.net/select/1.7.0/css/select.bootstrap5.min.css">
@endsection

@section('content')
   <div class="container-fluid mt-4">
      <div class="card mt-3">
         <div class="card-body p-5">
            <div class="card card-body mb-3">
               @if(count(auth()->user()->hoa) > 0)
                  @php
                     $hoaNames = [];
                     foreach(auth()->user()->hoa as $hoa) {
                           $hoaNames[] = $hoa['name'];
                     }
                     $implodedNames = implode(', ', $hoaNames);
                  @endphp
                  <h5 class="card-title">HOA: {{ $implodedNames }}</h5>
               @else
                  <h5 class="card-title text-danger">You are not assigned to any HOA. Please contact the administrator.</h5>
               @endif
               @if(auth()->user()->role_id != 7)
               <h6 class="mt-3">Filters:</h6>
               <div class="row gx-2">
                  <div class="col-3">
                     <select class="form-select" aria-label="Default select example" id="srs_select_inbox">
                        <option value="0">All</option>
                        <option value="1" selected>For Approval</option>
                        <option value="2">For Admin Approval</option>
                        <option value="3">Rejected</option>
                        <option value="4">Closed</option>
                     </select>
                  </div>
               </div>
               @endif
            </div>

            <div class="table-responsive">
               <table id="requests_table" class="table table-bordered w-100">
                  <thead>
                     <tr>
                        <th>SRS #</th>
                        <th>Requestor</th>
                        <th>Request Date</th>
                        <th>Status</th>
                     </tr>
                  </thead>
                  <tbody>

                  </tbody>
               </table>
            </div>
         </div>
      </div>
   </div>
@endsection

@section('links_js')
<script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/select/1.7.0/js/dataTables.select.min.js"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script src="{{ asset('js/11hpi3.js') }}"></script>

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
