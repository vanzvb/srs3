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
                    SRS File Attachment
                </div>
                <hr>

                {{-- @can('create', \App\Models\SPCSubCat::class) --}}
                <div class="d-flex justify-content-end">
                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                        data-bs-target="#addItemModal">
                        <i class="fa-solid fa-plus"></i>
                        Add Item
                    </button>
                </div>
                <br>
                {{-- @endcan --}}

                <table class="table table-bordered table-striped" id="subcat-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Category / Sub-Category</th>
                            {{-- Member type is hoa list --}}
                            <th>HOA / Member Type</th>
                            <th>Name</th>
                            <th>Required Files</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($spc3_subcat as $subcat)
                            <tr>
                                <td>{{ $subcat->id }}</td>
                                <td>{{ $subcat->category->name }} / {{ $subcat->subCat->name ?? 'N/A' }}</td>
                                <td>{{ $subcat->hoa->name ?? 'N/A' }} / {{ $subcat->hoaType->name ?? 'N/A' }}</td>
                                <td>{{ $subcat->name }}</td>
                                <td>
                                    @foreach ($subcat->requiredFiles as $file)
                                        <strong>•</strong>
                                        {{ $file->description }}@if (!$loop->last)
                                        @endif
                                        <br>
                                    @endforeach
                                </td>
                                <td>
                                    @if ($subcat->status == 1)
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-danger">Inactive</span>
                                    @endif
                                </td>
                                <!-- Add other fields as necessary -->
                                <td>
                                    {{-- @can('update', \App\Models\SPCSubCat::class) --}}
                                    <button type="button" data-bs-toggle="modal"
                                        data-bs-target="#purchaseOrderDetailModal{{ isset($subcat) ? $subcat->id : null }}"
                                        class="btn btn-outline-primary btn-sm">
                                        <i class="fa-solid fa-pen"></i>
                                    </button>
                                    {{-- @endcan --}}
                                    @include('sub3_categories.sub3_category_edit_modal_form')

                                    <!-- Delete Form -->
                                    {{-- @can('delete', \App\Models\SPCSubCat::class) --}}
                                    <form action="{{ route('v3.sub-categories.destroy', $subcat->id) }}" method="POST"
                                        class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger btn-sm"
                                            onclick="return confirm('Are you sure you want to delete this item?');">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>
                                    {{-- @endcan --}}
                                    
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>
        </div>
    </div>
    {{-- 3.0 Modal --}}
    @include('sub3_categories.sub3_category_add_modal')

    {{-- Old Modal --}}
    {{-- @include('sub3_categories.sub_category_add_modal')
    @include('sub3_categories.sub_category_edit_modal') --}}
@endsection

@section('links_js')
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    {{-- Version 3 --}}
    <script>
        $(document).ready(function() {
            $('#subcat-table').DataTable({
                paging: true,
                searching: true,
                ordering: true,
                order: [
                    [0, 'desc']
                ],
                responsive: true, // Optional for responsive design
                autoWidth: false // Adjusts columns to fit within AdminLTE design
            });


            // SweetAlert2 for success messages
            @if (session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: '{{ session('success') }}',
                    showCloseButton: true,
                });
            @endif

            // SweetAlert2 for error messages
            @if (session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: '{{ session('error') }}',
                    showCloseButton: true,
                });
            @endif
        });
    </script>
@endsection
