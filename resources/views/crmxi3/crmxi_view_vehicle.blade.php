<link rel="stylesheet" href="{{ asset('css/crmxi-modal-style.css') }}">

<div class="modal fade" id="viewVehicleModal" tabindex="-1" aria-labelledby="viewVehicleModalLabel" aria-hidden="true" >
    <div class="modal-dialog modal-xl" >
        <div class="modal-content modalContent" >
            <div class="modal-header py-2 modalHeader">
                <div style="display:flex; justify-content:space-between;width: 100%;">
                    <h1 class="modal-title fs-3 " id="viewVehicleModalLabel" > VIEW VEHICLE DETAIL</h1>
                    <button type="button" class="btn btn-lg " data-bs-dismiss="modal" aria-label="Close" style="color:white;">
                        <i class="fa-solid fa-times fa-xl" ></i>
                    </button>
                </div>
            </div>
            <div class="modal-body vehicleDetailModalBody">
                <div class="mt-3 ms-4 mb-3">
                    <table id="vehicleDetailTable">
                        <thead>
                            <tr>
                                <th>Plate no.</th>
                                <th>Brand</th>
                                <th>Series</th>
                                <th>Year/Model</th>
                                <th>Color</th>
                                <th>Type</th>
                                <th>Old Sticker no.</th>
                                <th>New Sticker no.</th>
                                <th>OR</th>
                                <th>CR</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- td is added dynamically --}}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
    </div>
</div>
<script>
    
</script>