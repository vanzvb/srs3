<div class="modal fade" id="add_subcat_modal" tabindex="-1" aria-labelledby="add_subcat_label" aria-hidden="true">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header">
         <h1 class="modal-title fs-5" id="add_subcat_label">Add Sub Category 3.0</h1>
         <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
         </div>
         <form id="add_subcat_form">
            <div class="modal-body">
               <input type="hidden" name="id" id="add_subcat_id">

               <div class="row mb-3">
                  <label for="add_subcat_category" class="col-sm-2 col-form-label">Category</label>
                  <div class="col-sm-10">
                     <select name="add_subcat_category" id="add_subcat_category" class="form-select">
                        <option value="" disabled selected>Select Category</option>
                        @foreach($categories as $category)
                           <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                     </select>
                  </div>
               </div>

               <div class="row mb-3">
                  <label for="name_modal" class="col-sm-2 col-form-label">Name</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control" id="name_modal">
                  </div>
               </div>

               <div class="row mb-3">
                  <div class="col-md-12">
                      <label>Status</label><br>
                      <div class="form-check form-check-inline">
                          <input class="form-check-input" type="radio" name="status" id="status_active" value="active" checked>
                          <label class="form-check-label" for="status_active">Active</label>
                      </div>
                      <div class="form-check form-check-inline">
                          <input class="form-check-input" type="radio" name="status" id="status_inactive" value="inactive">
                          <label class="form-check-label" for="status_inactive">Inactive</label>
                      </div>
                  </div>
              </div>

               <div class="row mb-3">
                  <div class="col-12 table-responsive">
                     <table id="add_requirement_table" class="table table-bordered">
                        <thead>
                           <tr>
                              <th></th>
                              <th>Required Files</th>
                           </tr>
                        </thead>
                        <tbody>
                           @foreach($required_files as $required_file)
                              <tr>
                                 <td>
                                    <div class="form-check">
                                       <input class="form-check-input" type="checkbox" name="required_files[]" value="{{ $required_file->id }}" data-file={{ $required_file->id }}>
                                    </div>
                                 </td>
                                 <td>{{ $required_file->description }}</td>
                              </tr>
                           @endforeach
                        </tbody>
                     </table>
                  </div>
               </div>
            </div>
            <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Submit</button>
            </div>
         </form>
      </div>
   </div>
</div>

@push('scripts')
<script>
   $('#add_subcat_form').on('submit', function(event) {
      event.preventDefault();

      let id = $('#add_subcat_id').val();
      let category = $('#add_subcat_category').val();
      let name = $('#add_subcat_name').val();
      let status = $('input[name="add_subcat_status"]:checked').val();
      let required_files = [];

      // loops through each checked checkbox and adds the value to the required_files array
      $('#add_requirement_table').find('input[type="checkbox"]:checked').each(function() {
         required_files.push($(this).val());
      });

      $.ajax({
         url: "/v3/sub-categories/create",
         type: 'POST',
         data: {
            _token: "{{ csrf_token() }}",
            id: id,
            category: category,
            name: name,
            status: status,
            required_files: required_files
         },
         beforeSend: function() {
            $('button[type="submit"]').html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Submitting...');
         },
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
               $('#add_subcat_modal').modal('hide');
               $('#sub_cat_table').DataTable().ajax.reload();
               $('button[type="submit"]').html('Submit');
            });
         },
         error: function(response, status, error) {
            if(response.status == 422) {
               let errors = response.responseJSON.errors;
               let errorsHtml = '';

               $.each(errors, function(key, value) {
                  errorsHtml += '<li>' + value[0] + '</li>';
               });

               Swal.fire({
                  title: 'Error!',
                  icon: 'error',
                  html: '<ul>' + errorsHtml + '</ul>',
                  showCancelButton: false,
                  confirmButtonColor: '#3085d6',
                  cancelButtonColor: '#d33',
                  confirmButtonText: 'Okay'
               });
            } else {
               Swal.fire({
                  title: 'Error!',
                  icon: 'error',
                  text: 'An error occurred. Please try again.',
                  showCancelButton: false,
                  confirmButtonColor: '#3085d6',
                  cancelButtonColor: '#d33',
                  confirmButtonText: 'Okay'
               });
            }

            $('button[type="submit"]').html('Submit');
         },
      });
   });

   $('#add_subcat_modal').on('hidden.bs.modal', function() {
      $('#add_subcat_form').trigger('reset');
   });
</script>
@endpush