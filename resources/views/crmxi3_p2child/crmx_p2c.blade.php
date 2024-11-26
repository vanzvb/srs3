@extends('layouts.main-app')

@section('title', 'CRMx')

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

   <div class="container-fluid mt-4">
      <div class="card myCard">
         <div class="card-body p-5">
            <div class="text-muted h1  fw-bold">
               <i class="fas fa-users"></i> CRM - Parent to Child
            </div>
            <hr>


            @foreach ($errors->all() as $message)
               {{ $message }}
            @endforeach
            
            <div class="table-responsive p-4 mt-4">
               {{-- <div class="row">
                    <div class="col-md-4"></div>
                    <div class="col-md-4"></div>
                    <div class="col-md-4">
                        <div class="d-flex justify-content-end mb-4">
                            <input type="text" class="form-control" placeholder="Search">
                        </div>
                    </div>
                </div> --}}
               <table id="crms_table" class="table table-bordered" style="width:100%">
                  <thead>
                     <tr>
                        <th>Customer ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Address</th>
                        <th>Owned Vehicles</th>
                        <th>Vehicles</th>
                        <th>Stickers Registered</th>
                        <th>Status</th>
                        <th>Created by</th>
                        <th>Actions</th>
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
   <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
   <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.js"></script>
   <script>
      $(document).ready(function() {
         $('#table_id').DataTable();
      });
      $(document).ready(function() {
         $('#table_id2').DataTable();
      });

      function loadCrms() {
         var table = $('#crms_table').DataTable({
            processing: true,
            serverSide: true,
            pageLength: 15,
            destroy: true,
            lengthMenu: [
               [15, 30, 50, 100],
               ['15', '30', '50', '100']
            ],
            ajax: {
               url: "{{ route('crm_p2c.list') }}",
            },
            columns: [{
                  data: 'customer_id'
               },
               {
                  data: 'cname'
               },
               {
                  data: 'email'
               },
               {
                  data: 'address'
               },
               {
                  data: 'owned_vehicles'
               },
               {
                  data: 'vehicles',
                  name: 'vehicles.plate_no',

               },
               {
                  data: 'stickers',
                  visible: false

               },
               {
                  data: 'status',
               },
               {
                  data: 'creator',
                  name: 'creator.name'
               },
               {
                  data: 'crm_actions',
                  orderable: false,
                  searchable: false
               }
            ],

            order: [
               [3, 'desc']
            ]
         });
      }

      loadCrms();
   </script>
   <?php
if (session()->has('success')) {
?>

   <script>
      Swal.fire({
         title: '<?php echo session()->get('success'); ?>',
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

   <?php
if (session()->has('error')) {
?>

   <script>
      Swal.fire({
         title: '<?php echo session()->get('error'); ?>',
         icon: 'error',
         showCancelButton: false,
         confirmButtonColor: '#3085d6',
         cancelButtonColor: '#d33',
         confirmButtonText: 'Okay'
      })
   </script>
   <?php
}


?>

@endsection
