@extends('layouts.main-app')

@section('title', 'CRMX - BFFHAI')

@section('links_css')
   <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" />
   <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
   <link rel="stylesheet" href="https://cdn.datatables.net/select/1.7.0/css/select.bootstrap5.min.css">
   <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">
   <style>
      @import url('https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap');

      body {
         font-family: 'Nunito', sans-serif;
      }
   </style>
@endsection

@section('content')

   <div class="container mt-4">
      <div class="card">
         <div class="card-body p-5">
            <div class="d-flex justify-content-start ">
               <a href="/crm" class="btn btn-success btn-md"><i class="fas fa-arrow-circle-left"></i> Back</a>
            </div>

            <div class="row justify-content-center align-items-start">
               <div class="col-7">
                  <div class="mt-5 h5">
                     <span class="text-muted fw-bold h5">Customer ID: </span> <span
                        class="text-muted">{{ $crm->customer_id }}</span>
                  </div>
                  <div>
                     <span class="text-muted fw-bold  h1">{{ $crm->firstname }} {{ $crm->middlename }}
                        {{ $crm->lastname }}</span>
                  </div>
                  <div class="h6">
                     <span class="fw-bold text-muted">Account Status:
      
                        <?php
                       if ($crm->status == 1) {
                       ?>
                        <span class="badge text-bg-success">Active</span>
                        <?php
                       } elseif ($crm->status == 2) {
                       ?>
                        <span class="badge text-bg-warning">Inactive</span>
                        <?php
                       } elseif ($crm->status == 3) {
                       ?>
                        <span class="badge text-bg-danger">Suspended</span>
                        <?php
                       } elseif ($crm->status == 4) {
                       ?>
                        <span class="badge text-bg-danger">Banned</span>
                        <?php
                       }
                       ?>
                     </span>
                  </div>
                  <div class="h6   text-muted">
                     <span class="fw-bold">Date Registered:</span><?= ' [' . date('d M, Y (D) h:i a',
                        strtotime($crm->created_at)) . ']' ?>
                  </div>
                  <div class="h6  text-muted">
                     <?php
                     if ($crm->address != NULL) {
                     ?>
                     <span class="fw-bold"> Address:</span> [ {{ $crm->address }} ]
                     <?php
                     } else {
                     ?>
                     <span class="fw-bold"> Address:</span> [ {{ $crm->blk_lot }} <?= ',' ?> {{ $crm->street }}
                     <?= ',' ?> {{ $crm->building_name }} <?= ',' ?>{{ $crm->subdivision_village }} <?= ','
                     ?> {{ $crm->hoa }} <?= ',' ?> {{ $crm->city }} <?= ',' ?> {{ $crm->zipcode }} ]
                     <?php
                     }
                     ?>
                  </div>
                  
                  <div class="d-flex">
                     <div class="h6 text-muted me-2">
                        <span class="fw-bold">Category: </span>
                        @if ($category)
                           {{ $category->name }}
                        @else
                           <span class="bg-danger text-white p-1 rounded">Not Found</span>
                        @endif
                     </div>
      
                     <div class="h6 text-muted d-flex">
                        <span class="fw-bold">Sub Category: </span>
                        @if ($sub_cat)
                           {{ $sub_cat->name }}
                        @else
                           <span class="bg-danger text-white p-1 rounded">Not Found</span>
                        @endif
                     </div>
                  </div>
      
                  <div class="form-check">
                     <label class="form-check-label fw-bold text-info" for="set_as_parent">
                        Set as Parent
                     </label>
                     <input class="form-check-input" type="checkbox" name="is_parent" id="set_as_parent" @if($crm->is_parent == 1) checked @endif>
                  </div>
               </div>
               <div class="col-5">
                  <div class="mt-5">
                  </div>
               </div>
            </div>

            @if(!isset($category) || !isset($sub_cat))
               <div class="mt-2 alert alert-danger d-flex align-items-center" role="alert">
               <i class="fas fa-exclamation-triangle me-2"></i>
                  <div class="d-flex justify-content-between w-100">
                     <p class="p-0 m-0">Looks like this customer has an outdated category or sub-category.</p>
                     <a href="/crm/edit-details-crm/{{ $crm->crm_id }}" class="p-0 m-0">Click here to update</a>
                  </div>
               </div>
            @endif

            <hr>
            
            @if($crm->is_parent)
               <div class="d-flex justify-content-between mb-3">
                  <h3 class="fw-bold">Children List</h3>
                  <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add_child">
                     <i class="fas fa-plus-circle me-2"></i>
                     Add Child
                  </button>
               </div>

               <table class="table table-bordered border table-hover w-100" id="children_table">
                  <thead>
                     <tr>
                        <th scope="col" style="width: 0;">Customer ID</th>
                        <th scope="col" style="width: 0;">Name</th>
                        <th scope="col" style="width: 0;">Email</th>
                        <th scope="col" style="width: 0;">Address</th>
                        <th scope="col" style="width: 0;">Category</th>
                        <th scope="col" style="width: 0;">Sub Category</th>
                        <th scope="col" style="width: 0;">HOA</th>
                        <th scope="col" style="width: 0;">Actions</th>
                     </tr>
                  </thead>
                  <tbody>
                     @foreach($children as $index => $child)
                        <tr>
                           <td class="text-nowrap">{{ $child->customer_id }}</td>
                           <td>{{ $child->firstname }} {{ $child->middlename }} {{ $child->lastname }}</td>
                           <td>
                              @php
                                 $email = $child->email;

                                 // remove white space
                                 $email = str_replace(' ', '', $email);

                                 // split @ from email
                                 $emailParts = explode('@', $email);

                                 // Check if the email had an "@" symbol
                                 if (count($emailParts) > 1) {
                                    $username = $emailParts[0];
                                    $domain = $emailParts[1];
                                 } else {
                                    // Handle the case where "@" symbol was not present
                                    $username = $emailParts[0];
                                    $domain = ''; // Or set it to null or handle accordingly
                                 }
                              @endphp

                              <div class="text-wrap">{{ $username }}</div>
                              <div class="text-wrap">@</div>
                              <div class="text-wrap">{{ $domain }}</div>
                           </td>
                           <td>
                              @if($child->address != NULL)
                                 {{ $child->address }}
                              @else
                                 {{ $child->blk_lot }} <?= ',' ?> {{ $child->street }} <?= ',' ?> {{ $child->building_name }} <?= ',' ?>{{ $child->subdivision_village }} <?= ',' ?> {{ $child->city }} <?= ',' ?> {{ $child->zipcode }}
                              @endif
                           </td>
                           <td>{{ $child->category_name }}</td>
                           <td>{{ $child->sub_category_name }}</td>
                           <td>{{ $child->hoa }}</td>
                           <td>
                              <div class="d-flex justify-content-center align-items center">
                                 <button class="btn btn-outline-danger btn-sm delete_child">
                                    <input type="hidden" class="child_list_item_{{ $index }}" data-child-item="{{ $child->p2c_id }}" data-child-name="{{ $child->firstname }} {{ $child->middlename }} {{ $child->lastname }}">
                                    <i class="fas fa-trash-alt"></i>
                                 </button>
                              </div>
                           </td>
                        </tr>
                     @endforeach
                  </tbody>
               </table>

               <hr>

               <div>
                  <h3 class="fw-bold">Vehicle Count</h3>
                  <table class="table table-bordered table-hover">
                     <thead>
                        <tr>
                           <th scope="col" style="width: 0;">Category</th>
                           <th scope="col" style="width: 0;">Sub-category</th>
                           <th scope="col" style="width: 0;">Type</th>
                           <th scope="col" style="width: 0;">Count</th>
                        </tr>
                     </thead>
                     <tbody>
                        @foreach($distinctCounts as $vehicle)
                           <tr>
                              <td>{{ $vehicle->category_name }}</td>
                              <td>{{ $vehicle->sub_category_name }}</td>
                              <td>{{ $vehicle->type }}</td>
                              <td class="text-center">{{ $vehicle->total_vehicle_count }}</td>
                           </tr>
                        @endforeach

                        <tr>
                           <td colspan="3" class="text-end fw-bold">Total</td>
                           <td class="text-center fw-bold">{{ $distinctCounts->sum('total_vehicle_count') }}</td>
                        </tr>
                  </table>
               </div>
            @endif
         </div>
      </div>
      <div class="modal modal-xl fade" id="add_child" tabindex="-1" aria-labelledby="add_child_modal" aria-hidden="true">
         <div class="modal-dialog">
           <div class="modal-content">
             <div class="modal-header">
               <h1 class="modal-title fs-5" id="add_child_modal">Add Child</h1>
               <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
             </div>
             <form action="{{ route('crm_p2c.set_child') }}" method="POST">
               @csrf
               <input type="hidden" name="parent_id" value="{{ $crm->crm_id }}">

               <div class="modal-body">
                  <table class="table table-hover table-bordered border w-100" id="child_selection">
                     <thead>
                        <tr>
                           <th></th>
                           <th scope="col" class="text-nowrap" style="width: 0;">Customer ID</th>
                           <th scope="col" style="width: 0;">Name</th>
                           <th scope="col" class="text-nowrap" style="width: 0;">Email</th>
                           <th scope="col" style="width: 0;">Address</th>
                           <th scope="col" style="width: 0;">Category</th>
                           <th scope="col" style="width: 0;">Sub-category</th>
                        </tr>
                     </thead>
                     <tbody>
                        
                     </tbody>
                  </table>
               </div>
               <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                  <button type="submit" class="btn btn-primary">Add</button>
               </div>
            </form>
           </div>
         </div>
      </div>
   </div>
@endsection

@section('links_js')
<script src="https://code.jquery.com/jquery-3.6.1.min.js"
   integrity="sha256-o88AwQnZB+VDvE9tvIXrMQaPlFFSUTR+nldQm1LuPXQ=" crossorigin="anonymous"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/select/1.7.0/js/dataTables.select.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>

<script>
   $('#set_as_parent').on('change', function() {
      if ($(this).is(':checked')) {
         msg = 'set';
         next_sentence = '';
      } else {
         msg = 'unset';
         next_sentence = ' This will also remove all children from the list.';
      }

      Swal.fire({
         title: 'Are you sure?',
         text: 'You are about to ' + msg + ' this customer as parent.' + next_sentence,
         icon: 'warning',
         showCancelButton: true,
         confirmButtonColor: '#3085d6',
         cancelButtonColor: '#d33',
         confirmButtonText: 'Proceed'
      }).then((result) => {
         if (result.isConfirmed) {
            $.ajax({
               url: '/crm_p2c/set_parent/' + <?= $crm->crm_id ?>,
               data: {
                  _token: '{{ csrf_token() }}',
                  is_parent: $(this).is(':checked')
               },
               type: 'POST',
               success: function(data) {
                  Swal.fire({
                     title: 'Success!',
                     text: data.message,
                     icon: 'success',
                     showCancelButton: false,
                     confirmButtonColor: '#3085d6',
                     cancelButtonColor: '#d33',
                     confirmButtonText: 'Okay'
                  }).then((result) => {
                     if (result.isConfirmed) {
                        location.reload();
                     }
                  })
               },
               error: function(data) {
                  Swal.fire({
                     title: 'Error!',
                     text: data.responseJSON.message,
                     icon: 'error',
                     showCancelButton: false,
                     confirmButtonColor: '#3085d6',
                     cancelButtonColor: '#d33',
                     confirmButtonText: 'Okay'
                  }).then((result) => {
                     if (result.isConfirmed) {
                        location.reload();
                     }
                  })
               }
            });
         } else {
            location.reload();
         }
      })
   })
</script>

<script>
   $(document).ready(function() {
      $('#add_child').on('show.bs.modal', function (event) {
         let child_table = $('#child_selection').DataTable({
            serverSide: true,
            ajax: {
               url: "{{ route('crm_p2c.children_list') }}",
               type: 'POST',
               beforeSend: function(request) {
                  request.setRequestHeader("X-CSRF-TOKEN", '{{ csrf_token() }}');
               },
               data: function (d) {
                  d.crm_id = "{{ $crm->crm_id }}";
               }
            },
            columns: [
               {
                  render: function (data, type, row) {
                     return '<div class="d-flex justify-content-center"><input class="form-check-input" type="radio" name="child_id" value="' + row.crm_id + '" data-value="' + row.crm_id + '"></div>';
                  }
               },
               {  
                  data: 'customer_id', 
                  name: 'customer_id',
               },
               {
                  name: 'name',
                  render: function (data, type, row) {
                     // Combine name with line break
                     let fullname = row.firstname + '\n' + row.middlename + '\n' + row.lastname;
                     return `<div class="text-wrap">${fullname}</div>`;
                  }
               },
               {  
                  name: 'email',
                  render: function (data, type, row) {
                     // remove white space
                     if(row.email == null) {
                        return '';
                     }

                     let email = row.email.replace(/\s/g, '');

                     return `<div class="text-wrap">${email}</div>`;
                  }
               },
               {
                  name: 'address',
                  render: function (data, type, row) {
                     let address = row.blk_lot + ', ' + row.street + ', ' + row.building_name + ', ' + row.subdivision_village + ', ' + row.hoa + ', ' + row.city + ', ' + row.zipcode;

                     return address;
                  }
               },
               {
                  name: 'category',
                  render: function (data, type, row) {
                     if(row.spc_category == null) {
                        return '';
                     }

                     return row.spc_category.name;
                  }
               },
               {
                  name: 'sub_category',
                  render: function (data, type, row) {
                     if(row.spc_subcat == null) {
                        return '';
                     }

                     return row.spc_subcat.name;
                  }
               },
            ],
            ordering: false,
         });

         $('#child_selection_filter').css({
            'margin-bottom': '1rem',
         });
      });

      $('#add_child').on('hidden.bs.modal', function (event) {
         // destroy table
         $('#child_selection').DataTable().destroy();
      });
   });
</script>

<script>
   $('.delete_child').on('click', function() {
      let child_id = $(this).find('input[type="hidden"]').data('child-item');
      let child_name = $(this).find('input[type="hidden"]').data('child-name');

      Swal.fire({
         title: 'Are you sure?',
         text: 'You are about to remove ' + child_name + ' from the parent list.',
         icon: 'warning',
         showCancelButton: true,
         confirmButtonColor: '#3085d6',
         cancelButtonColor: '#d33',
         confirmButtonText: 'Proceed'
      }).then((result) => {
         if (result.isConfirmed) {
            $.ajax({
               url: '/crm_p2c/delete_child',
               data: {
                  _token: '{{ csrf_token() }}',
                  child_id: child_id
               },
               type: 'POST',
               success: function(data) {
                  Swal.fire({
                     title: 'Success!',
                     text: data.message,
                     icon: 'success',
                     showCancelButton: false,
                     confirmButtonColor: '#3085d6',
                     cancelButtonColor: '#d33',
                     confirmButtonText: 'Okay'
                  }).then((result) => {
                     if (result.isConfirmed) {
                        location.reload();
                     }
                  })
               },
               error: function(data) {
                  Swal.fire({
                     title: 'Error!',
                     text: data.message,
                     icon: 'error',
                     showCancelButton: false,
                     confirmButtonColor: '#3085d6',
                     cancelButtonColor: '#d33',
                     confirmButtonText: 'Okay'
                  }).then((result) => {
                     if (result.isConfirmed) {
                        location.reload();
                     }
                  })
               }
            });
         }
      })
   });
</script>
   
<script>
   $(document).ready(function() {
      $('#children_table').DataTable({
         responsive: true,
         order: [[1, 'asc']],
         pageLength: 3,
      });
   });
</script>

@if (session('danger'))
   <script>
      Swal.fire({
         title: "{{ session('danger') }}",
         icon: 'error',
         showCancelButton: false,
         confirmButtonColor: '#3085d6',
         cancelButtonColor: '#d33',
         confirmButtonText: 'Okay'
      });
   </script>
@endif

@if (session('success'))
   <script>
      Swal.fire({
         title: "{{ session('success') }}",
         icon: 'success',
         showCancelButton: false,
         confirmButtonColor: '#3085d6',
         cancelButtonColor: '#d33',
         confirmButtonText: 'Okay'
      });
   </script>
@endif


@endsection
