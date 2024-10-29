@extends('layouts.main-app')

@section('title', 'Sub Categories')

@section('links_css')
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"
      integrity="sha512-9usAa10IRO0HhonpyAIVpjrylPvoDwiPUiKdWk5t3PyolY1cOd4DSE0Ga+ri4AuTroPR5aQvXU9xC6qOPnzFeg=="
      crossorigin="anonymous" referrerpolicy="no-referrer" />
   <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
@endsection

@section('content')
   <div class="container-fluid mt-4">
      <div class="card">
         <div class="card-body p-5">
            <div class="text-muted h1 fw-bold">
               <i class='bx bx-category icon'></i>
               Sub Categories
            </div>
            <hr>
            
            @can('create', \App\Models\SPCSubCat::class)
            <div class="d-flex justify-content-end">
               <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#add_subcat_modal">
                  <i class="fa-solid fa-plus"></i>
                  Add Sub Category
               </button>
            </div>
            @endcan
            
            <div class="mt-4 table-responsive">
               <table id="sub_cat_table" class="table table-bordered border w-100">
                  <thead>
                     <tr>
                        <th class="text-center">
                           <small>#</small>
                        </th>
                        <th class="text-center">
                           <small>Category</small>
                        </th>
                        <th class="text-center">
                           <small>Sub-Category</small>
                        </th>
                        <th class="text-center">
                           <small>Status</small>
                        </th>
                        <th class="text-center">
                           <small>Required Files</small>
                        </th>
                        <th class="text-center">
                           <small></small>
                        </th>
                     </tr>
                  </thead>
                  <tbody>
                    
                  </tbody>
               </table>
            </div>
         </div>
      </div>
   </div>

   @include('sub_categories.sub_category_add_modal')
   @include('sub_categories.sub_category_edit_modal')
@endsection

@section('links_js')
   <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
   <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
   <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
   <script>
      $(document).ready(function() {
         $('#sub_cat_table').DataTable({
            ajax: {
               url: "{{ route('sub-categories.list') }}",
               type: 'GET',
            },
            createdRow: function(row, data, dataIndex, cells) {
               $(cells[3]).addClass('text-center');
               $(cells[5]).addClass('text-center');
            },
            columns: [
               {
                  render: function(data, type, row, meta) {
                     return meta.row + 1;
                  }
               },
               {
                  data: 'category.name'
               },
               {
                  data: 'name'
               },
               {
                  render: function(data, type, row) {
                     let status = row.status;
                     
                     if(status == 1) {
                        return `<span class="badge bg-success">Active</span>`;
                     } else {
                        return `<span class="badge bg-danger">Inactive</span>`;
                     }
                  }
               },
               {
                  render: function(data, type, row) {
                     let required_files = '';

                     row.required_files.forEach(function(file) {
                           required_files += '<li>' + file.description + '</li>';
                     });

                     return '<ul>' + required_files + '</ul>';
                  },
                  sortable: false
               },
               {
                  render: function(data, type, row) {
                     return `
                        <div class="d-flex gap-1 justify-content-center">
                           @can('update', \App\Models\SPCSubCat::class)
                           <button type="button" class="btn btn-outline-primary btn-sm"  data-bs-toggle="modal" data-bs-target="#edit_subcat_modal" data-id="${row.id}">
                              <i class="fa-solid fa-pen"></i>
                           </button>
                           @endcan

                           @can('delete', \App\Models\SPCSubCat::class)
                           <button type="button" class="btn btn-outline-danger btn-sm destroy-btn" data-id="${row.id}">
                              <i class="fa-solid fa-trash"></i>
                           </button>
                           @endcan
                        </div>
                     `;
                  },
                  sortable: false,
                  searchable: false
               }
            ],
            order: [
               [0, "desc"]
            ],
            responsive: true,
            pageLength: 15,
            lengthMenu: [10, 15, 20, 25, 50, 100],
         });
         
         $('#sub_cat_table_filter').css({
            'margin-bottom': '1rem'
         });
      });
   </script>

   <script>
      // on click of .destroy-btn
      $(document).on('click', '.destroy-btn', function() {
         let id = $(this).data('id');

         Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
         }).then((result) => {
            if (result.isConfirmed) {
               $.ajax({
                  url: "/sub-categories/" + id,
                  data: {
                     _token: "{{ csrf_token() }}",
                  },
                  type: 'DELETE',
                  success: function(response) {
                     Swal.fire({
                        title: 'Success!',
                        icon: 'success',
                        text: response.message,
                        showCancelButton: false,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Okay'
                     }).then((result) => {
                        $('#sub_cat_table').DataTable().ajax.reload();
                     });
                  }
               });
            }
         });
      });
   </script>
@endsection