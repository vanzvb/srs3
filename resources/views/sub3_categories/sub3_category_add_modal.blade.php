<!-- Add Item Modal -->
<div class="modal fade" id="addItemModal" tabindex="-1" role="dialog" aria-labelledby="addItemModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addItemModalLabel">Add Item</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            {{-- <form action="{{ route('your_store_route') }}" method="POST" id="addItemForm"> --}}
            <form action="{{ route('v3.sub-categories.store') }}" method="POST" id="addItemForm">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="category_modal">Category</label>
                                <select name="category_modal" id="category_modal" class="form-select" required>
                                    @foreach ($CRMXICategories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-8">
                                <label for="subcat_category">Sub Category</label>
                                <select name="subcat_category" id="subcat_category" class="form-select">
                                    <option value="" selected>Select Sub Category</option>
                                    @foreach ($CRMXISubCategories as $subcat)
                                        <option value="{{ $subcat->id }}">{{ $subcat->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-5">
                                <label for="subcat_category">Membership Type</label>
                                <select name="hoa_type" id="hoa_type" class="form-select">
                                    <option value="" selected>Select Membership Type</option>
                                    @foreach ($CRMXIHoaTypes as $hoaType)
                                        <option value="{{ $hoaType->id }}">{{ $hoaType->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-7">
                                <label for="subcat_category">HOA</label>
                                <select name="hoas" id="hoas" class="form-select">
                                    <option value="" selected>Select HOA</option>
                                    @foreach ($CRMXIHoas as $hoa)
                                        <option value="{{ $hoa->id }}">{{ $hoa->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="name_modal" class="col-sm-2 col-form-label">Name</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="name_modal" name="name_modal" required>
                            </div>
                        </div>

                        <fieldset class="row mb-3">
                            <legend class="col-form-label col-sm-2 pt-0">Status</legend>
                            <div class="col-sm-10">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="add_subcat_status"
                                        id="add_subcat_name_1" value="1" checked>
                                    <label class="form-check-label" for="add_subcat_name_1">
                                        Active
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="add_subcat_status"
                                        id="add_subcat_name_0" value="0">
                                    <label class="form-check-label" for="add_subcat_name_2">
                                        Inactive
                                    </label>
                                </div>
                            </div>
                        </fieldset>
                        <div class="mb-3">
                            <label>Required Files</label>
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th scope="col"></th>
                                        <th scope="col">File Name</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($required_files as $file)
                                        <tr>
                                            <td class="text-center">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox"
                                                        name="required_files[]" value="{{ $file->id }}"
                                                        id="required_file_{{ $file->id }}">
                                                </div>
                                            </td>
                                            <td>
                                                <label class="form-check-label"
                                                    for="required_file_{{ $file->id }}">
                                                    {{ $file->description }}
                                                </label>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Add Item</button>
                </div>
            </form>
        </div>
    </div>
</div>
