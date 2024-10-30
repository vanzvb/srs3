<div class="form-group">
    <input type="hidden" name="id" value="{{isset($subcat) ? $subcat->id : null }}">
    <div class="row mb-3">
        <div class="col-md-4">
            <label for="category_modal">Category</label>
            <select name="category_modal" id="category_modal{{ isset($subcat) ? $subcat->id : null }}" class="form-select"
                required>
                @foreach ($CRMXICategories as $category)
                    <option value="{{ $category->id }}"
                        {{ isset($subcat) ? (!is_null($subcat->id) && $subcat->category_id == $category->id ? 'selected' : '') : null }}>
                        {{ $category->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-8">
            <label for="subcat_category">Sub Category</label>
            <select name="subcat_category" id="subcat_category{{ isset($subcat) ? $subcat->id : null }}"
                class="form-select">
                <option value="" selected>Select Sub Category</option>
                @foreach ($CRMXISubCategories as $subcategory)
                    <option value="{{ $subcategory->id }}"
                        {{ isset($subcat) ? (!is_null($subcat->id) && $subcat->sub_category_id == $subcategory->id ? 'selected' : '') : null }}>
                        {{ $subcategory->name }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-md-5">
            <label for="subcat_category">Membership Type</label>
            <select name="hoa_type" id="hoa_type{{ isset($subcat) ? $subcat->id : null }}" class="form-select">
                <option value="" selected>Select Membership Type</option>
                @foreach ($CRMXIHoaTypes as $hoaType)
                    <option value="{{ $hoaType->id }}"
                        {{ isset($subcat) ? (!is_null($subcat->id) && $subcat->hoa_type_id == $hoaType->id ? 'selected' : '') : null }}>
                        {{ $hoaType->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-7">
            <label for="subcat_category">HOA</label>
            <select name="hoas" id="hoas{{ isset($subcat) ? $subcat->id : null }}" class="form-select">
                <option value="" selected>Select HOA</option>
                @foreach ($CRMXIHoas as $hoa)
                    <option value="{{ $hoa->id }}"
                        {{ isset($subcat) ? (!is_null($subcat->id) && $subcat->hoa_id == $hoa->id ? 'selected' : '') : null }}>
                        {{ $hoa->name }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="row mb-3">
        <label for="name_modal" class="col-sm-2 col-form-label">Name</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" id="name_modal{{ isset($subcat) ? $subcat->id : null }}"
                name="name_modal" value="{{ isset($subcat) ? $subcat->name : '' }}" required>
        </div>
    </div>

    <!-- Status Fieldset -->
    {{-- <form> --}}
        <fieldset class="row mb-3">
            <legend class="col-form-label col-sm-2 pt-0">Status</legend>
            <div class="col-sm-10">
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="sub_status" value="1"
                        @if (isset($subcat) && $subcat->status == 1) checked @endif>
                    <label class="form-check-label" for="add_subcat_status_1">Active</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="sub_status" value="0"
                        @if (isset($subcat) && $subcat->status == 0) checked @endif>
                    <label class="form-check-label" for="add_subcat_status_0">Inactive</label>
                </div>
            </div>
        </fieldset>
    {{-- </form> --}}
    

    <!-- Required Files Section -->
    <div class="mb-3">
        <label>Required Files</label>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th scope="col" class="text-center"></th>
                    <th scope="col">File Name</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($required_files as $file)
                    <tr>
                        <td class="text-center">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="required_files[]"
                                    value="{{ $file->id }}" id="required_file_{{ $file->id }}"
                                    {{ isset($subcat) && in_array($file->id, $subcat->requiredFiles->pluck('id')->toArray()) ? 'checked' : '' }}>
                            </div>
                        </td>
                        <td>
                            <label class="form-check-label" for="required_file_{{ $file->id }}">
                                {{ $file->description }}
                            </label>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>