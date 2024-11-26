{{-- For Patch 11/19/24 --}}
<div class="card mt-4">
    <div class="card-header p-3 d-flex justify-content-between" style="background-color: #1e81b0;">
        <div class="h6 fw-bold text-white">Vehicle Summary</div>
    </div>

    <div class="card-body" id="is_parent_active">
        <hr>

        <div>
            <h3 class="fw-bold">Vehicle Count</h3>
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th scope="col" style="width: 0;">Category</th>
                        <th scope="col" style="width: 0;">Sub-category</th>
                        <th scope="col" style="width: 0;">Vehicle Ownership Type (VOT)</th>

                        <th scope="col" style="width: 0;">Type</th>
                        <th scope="col" style="width: 0;">HOA</th>
                        <th scope="col" style="width: 0;">Count</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($distinctCounts as $index => $vehicle)
                        <tr>
                            <td>{{ $vehicle->category_name }}</td>
                            <td>{{ $vehicle->sub_category_name }}</td>
                            <td>{{ $vehicle->vehicle_ownership_status ?? 'N/A' }}</td>
                            <td>{{ $vehicle->type }}</td>
                            <td>{{ $vehicle->hoa_name ?? 'N/A' }}</td>
                            <td class="text-center" data-category="{{ $vehicle->category_name }}"
                                data-sub-category="{{ $vehicle->sub_category_name }}"
                                data-vehicle-type={{ $vehicle->type }}
                                data-vot="{{ $vehicle->vehicle_ownership_status }}" data-hoa="{{ $vehicle->hoa_name }}">
                                <a class="vehicle" type="button" data-bs-toggle="modal" data-bs-target="#plate_nums"
                                    data-index={{ $index }}>{{ $vehicle->current_year_vehicle_count }} /
                                    {{ $vehicle->total_vehicle_count }}</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">No data available</td>
                        </tr>
                    @endforelse

                    <tr>
                        <td colspan="5" class="text-end fw-bold">Total</td>
                        <td class="text-center fw-bold">
                            {{ $distinctCounts->sum('current_year_vehicle_count') }} /
                            {{ $distinctCounts->sum('total_vehicle_count') }}
                        </td>
                    </tr>
            </table>
        </div>
    </div>

    {{-- plate nos modal --}}
    <div class="modal fade" id="plate_nums" tabindex="-1" aria-labelledby="plate_nums_modal" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="plate_nums_modal">Plate Numbers (excluded duplicate)</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <table class="table table-hover table-bordered border w-100" id="plate_nums_table">
                        <thead id="plate_numbers_table">
                            <tr>
                                <th scope="col" style="width: 0;">Plate No.</th>
                                <th scope="col" style="width: 0;">Sticker Year</th>
                                <th scope="col" style="width: 0;">Owner</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
                <div class="modal-footer" id="plate_nums_buttons">
                    <button type="button" class="btn btn-outline-secondary btn-sm"
                        data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#plate_nums').on('show.bs.modal', function(event) {
                let index = $(event.relatedTarget).data('index');
                let category = $(event.relatedTarget).parent().data('category');
                let sub_category = $(event.relatedTarget).parent().data('sub-category');
                let vehicle_type = $(event.relatedTarget).parent().data('vehicle-type');
                let vot = $(event.relatedTarget).parent().data('vot');
                let hoa = $(event.relatedTarget).parent().data('hoa');
                let parent_id = "{{ $crms_account[0]->account_id }}";

                console.log(category, sub_category, vehicle_type, vot, hoa, parent_id);

                let table = $('#plate_nums_table').DataTable().destroy();

                table = $('#plate_nums_table').DataTable({
                    ajax: {
                        url: "/crm3/crm_p2c/get_plate_numbers",
                        type: 'POST',
                        beforeSend: function(request) {
                            request.setRequestHeader("X-CSRF-TOKEN", '{{ csrf_token() }}');
                        },
                        data: {
                            category: category,
                            sub_category: sub_category,
                            vehicle_type: vehicle_type,
                            vot,
                            hoa,
                            parent_id: parent_id,
                        }
                    },
                    columns: [{
                            render: function(data, type, row) {
                                return row.plate_no;
                            }
                        },
                        {
                            render: function(data, type, row) {
                                if (row.sticker_date == null || row.sticker_date == '') {
                                    return '';
                                }

                                return row.sticker_date;
                            }
                        },
                        {
                            render: function(data, type, row) {
                                return `${row.firstname} ${row.lastname}`;
                            }
                        }
                    ],
                    ordering: false,
                    searching: false,
                    pagingType: 'simple',
                    pageLength: 10,
                    info: false,
                });
            });

            $('#plate_nums').on('show.bs.modal', function(event) {
                let table = $('#plate_nums_table').DataTable().destroy();
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
@endpush
