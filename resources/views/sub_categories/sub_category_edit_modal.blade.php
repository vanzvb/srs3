<div class="modal fade" id="edit_subcat_modal" tabindex="-1" aria-labelledby="edit_subcat_label" aria-hidden="true">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header">
         <h1 class="modal-title fs-5" id="edit_subcat_label">Edit Sub Category</h1>
         <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
         </div>
         <form id="edit_subcat_form">
            <div class="modal-body">
               <input type="hidden" name="id" id="edit_subcat_id">

               <div class="row mb-3">
                  <label for="edit_subcat_category" class="col-sm-2 col-form-label">Category</label>
                  <div class="col-sm-10">
                     <select name="edit_subcat_category" id="edit_subcat_category" class="form-select">
                        @foreach($categories as $category)
                           <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                     </select>
                  </div>
               </div>

               <div class="row mb-3">
                  <label for="edit_subcat_name" class="col-sm-2 col-form-label">Name</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control" id="edit_subcat_name">
                  </div>
               </div>

               <fieldset class="row mb-3">
                  <legend class="col-form-label col-sm-2 pt-0">Status</legend>
                  <div class="col-sm-10">
                     <div class="form-check">
                        <input class="form-check-input" type="radio" name="edit_subcat_status" id="edit_subcat_name_1" value="1">
                        <label class="form-check-label" for="edit_subcat_name_1">
                           Active
                        </label>
                     </div>
                     <div class="form-check">
                        <input class="form-check-input" type="radio" name="edit_subcat_status" id="edit_subcat_name_0" value="0">
                        <label class="form-check-label" for="edit_subcat_name_2">
                           Inactive
                        </label>
                     </div>
                  </div>
               </fieldset>

               <div class="row mb-3">
                  <div class="col-12 table-responsive">
                     <table id="edit_requirement_table" class="table table-bordered">
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
            <button type="submit" class="btn btn-primary">Update</button>
            </div>
         </form>
      </div>
   </div>
</div>

@push('scripts')
   <script>
      $('#edit_subcat_modal').on('show.bs.modal', function(event) {
         // get the related data-id attribute of the button
         let id = $(event.relatedTarget).data('id');
        
         $.ajax({
            url: "/sub-categories/" + id,
            type: 'GET',
            success: function(response) {
               $('#edit_subcat_id').val(response.data.id);
               $('#edit_subcat_category').val(response.data.category.id);
               $('#edit_subcat_name').val(response.data.name);
               $('#edit_subcat_name_' + response.data.status).prop('checked', true);

               response.data.required_files.forEach(function(file) {
                  $('#edit_requirement_table').find('input[data-file="' + file.id + '"]').prop('checked', true);
               });
            }
         })
      });

      $('#edit_subcat_modal').on('hidden.bs.modal', function() {
         $('#edit_subcat_form').trigger('reset');
      });
   </script>

   <script>
      $('#edit_subcat_form').on('submit', function(event) {
         event.preventDefault();

         //get values
         let id = $('#edit_subcat_id').val();
         let category = $('#edit_subcat_category').val();
         let name = $('#edit_subcat_name').val();
         let status = $('input[name="edit_subcat_status"]:checked').val();
         let required_files = [];

         $('#edit_requirement_table').find('input[type="checkbox"]:checked').each(function() {
            required_files.push($(this).val());
         });

         $.ajax({
            url: "/sub-categories/" + id,
            type: 'PUT',
            data: {
               _token: "{{ csrf_token() }}",
               id: id,
               category: category,
               name: name,
               status: status,
               required_files: required_files
            },
            beforeSend: function() {
               $('button[type="submit"]').html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Updating...');
            },
            success: function(response) {
               Swal.fire({
                  title: 'Success!',
                  icon: 'success',
                  text: 'Sub Category has been updated.',
                  showCancelButton: false,
                  confirmButtonColor: '#3085d6',
                  cancelButtonColor: '#d33',
                  confirmButtonText: 'Okay'
               }).then((result) => {
                  $('#edit_subcat_modal').modal('hide');
                  $('#sub_cat_table').DataTable().ajax.reload();
                  $('button[type="submit"]').html('Update');
               });
            },
            error: function(response, status, error) {
               Swal.fire({
                  title: 'Error!',
                  icon: 'error',
                  text: 'An error occurred. Please try again.',
                  showCancelButton: false,
                  confirmButtonColor: '#3085d6',
                  cancelButtonColor: '#d33',
                  confirmButtonText: 'Okay'
               });

               $('button[type="submit"]').html('Update');
            },
         });
      });
   </script>
@endpush