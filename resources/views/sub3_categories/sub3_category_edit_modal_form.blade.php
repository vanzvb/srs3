<div class="modal fade" id="purchaseOrderDetailModal{{ isset($subcat) ? $subcat->id : null }}" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Update</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                
            </div>
            <form action="{{ route('v3.sub-categories.edit', $subcat->id) }}" method="POST">
            @csrf
            @method('PUT')
                <div class="modal-body">
                    @include('sub3_categories.sub3_category_edit_modal_form_detail')
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-sm btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>