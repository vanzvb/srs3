@extends('layouts.main-app')

@section('title', 'Prices - BFFHAI')

@section('links_css')
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" />
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.css">
<style>
    @import url('https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap');

    body {
        font-family: 'Nunito', sans-serif;
    }
</style>
@endsection

@section('content')

<div class="container mt-4 mb-3" style="max-width: 1300px;">
    <div class="card shadow shadow-1" style="border-radius: 15px;">
        <div class="card-body p-5">

            <nav>
                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                    <button class="nav-link active" id="nav-home-tab" data-bs-toggle="tab" data-bs-target="#nav-home" type="button" role="tab" aria-controls="nav-home" aria-selected="true"><i class="fas fa-hand-holding-usd"></i> Pricing</button>
                    <button class="nav-link" id="nav-profile-tab" data-bs-toggle="tab" data-bs-target="#nav-profile" type="button" role="tab" aria-controls="nav-profile" aria-selected="false"><i class="fas fa-cog"></i> Rates Settings</button>

                </div>
            </nav>
            <div class="tab-content" id="nav-tabContent">
                <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab" tabindex="0">

                    <div class="d-flex justify-content-between mb-5">


                        <div>
                            <div class="h2 fw-bold text-primary mt-5">
                                <i class="fas fa-hand-holding-usd"></i> Pricing List
                            </div>

                            <div class="d-flex align-items-center mt-5">
                                <div>
                                    <i class="fas fa-filter fa-2x text-muted"></i>
                                </div>
                                <div>
                                    <select id="filter" onchange="filterChange($(this).val())" class="form-select form-select-lg mb-3 ms-3" aria-label=".form-select-lg example">
                                        <option value="0">All</option>
                                        <option value="1">Resident</option>
                                        <option value="2">Non-Resident</option>
                                        <option value="3">Commercial</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Button trigger modal -->
                        <div>
                            <button type="button" class="btn btn-success mt-3" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                <i class="fas fa-plus-circle"></i> Add Prices
                            </button>

                            <!-- Modal -->
                            <form action="/insertPrice" method="POST">
                                @csrf
                                <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h1 class="modal-title fs-5" id="exampleModalLabel"><i class="fas fa-plus-circle"></i> Add Prices</h1>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="mb-4">
                                                    <label for="" class="form-label">Category: </label>
                                                    <select name="category_id" onchange="categoryChange($(this).val())" class="form-select form-select-lg mb-3" aria-label=".form-select-lg example" required>
                                                        <option value="">---</option>
                                                        @foreach ($categories as $category)
                                                        <option value="<?= $category->id ?>">{{ $category->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="mb-4">

                                                    <div class="form-group">
                                                        <label for="Sub Category">Sub Category</label>
                                                        <select name="sub_category_id" onchange="sub_categoryChange($(this).val())" id="sub_category" class="form-select " aria-label="Default select example" required>
                                                            <option value="">---</option>

                                                        </select>

                                                    </div>
                                                </div>

                                                <div class="mb-4">

                                                    <div class="form-group">
                                                        <label for="No. of Cars/Motorcycle">No. of Cars/Motorcycle / Description</label>
                                                        <select name="no_cars" id="no_cars" class="form-select " aria-label="Default select example" required>


                                                        </select>

                                                    </div>
                                                </div>

                                                <div class="mb-4">

                                                    <div class="form-group">
                                                        <label for="No. of Cars/Motorcycle">Vehicle Type</label>
                                                        <select name="vehicle_type" class="form-select " aria-label="Default select example" required>
                                                            <option value="Car">Car/Bus/Trucks</option>
                                                            <option value="Motorcycle">Motorcycle</option>

                                                        </select>

                                                    </div>
                                                </div>
                                                <div class="mb-4">
                                                    <label for="" class="form-label">Price: </label>
                                                    <input type="number" name="prices" class="form-control" required>
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="mb-4">
                                                            <label for="" class="form-label">Min Vehicles: </label>
                                                            <input type="number" name="min" class="form-control" required>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="mb-4">
                                                            <label for="" class="form-label">Max Vehicles: </label>
                                                            <input type="number" name="max" class="form-control">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                <button type="submit" class="btn btn-primary">Submit</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <table class="table table-hover table-bordered">
                        <thead>
                            <tr>
                                <!-- <th class="text-nowrap">Price Code</th> -->
                                <th>Category</th>
                                <th>Sub-Category</th>
                                <th>Vehicle Type</th>
                                <th>No. of Cars/Motorcycles</th>
                                <th>Rate</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="table-1-rows">



                        </tbody>
                    </table>


                </div>
                <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab" tabindex="0">
                    <div class="mt-5">
                        <div class="mt-3 mb-5">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <div class="h2 fw-bold text-primary ">
                                        <i class="fas fa-cog"></i> Rates Settings
                                    </div>
                                    <div class="d-flex align-items-center mt-5">
                                        <div>
                                            <i class="fas fa-filter fa-2x text-muted"></i>
                                        </div>
                                        <div>
                                            <select id="filter_rate" onchange="filterChangeRate($(this).val())" class="form-select form-select-lg mb-3 ms-3" aria-label=".form-select-lg example">
                                                <option value="0">All</option>
                                                <option value="1">Resident</option>
                                                <option value="2">Non-Resident</option>
                                                <option value="3">Commercial</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#rateName">
                                        <i class="fas fa-plus-circle"></i> Add Rate Name
                                    </button>

                                </div>
                            </div>
                        </div>
                        <table class="table table-hover table-bordered">
                            <thead>
                                <tr>

                                    <th>Category</th>
                                    <th>Sub-Category</th>
                                    <th class="text-nowrap">Rate Name</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="table-2-rows">

                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>



</div>

<!-- Modal -->
<div class="modal fade" id="editPrice" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog ">
        <form action="/update-price" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Edit Price</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="price_id" name="price_id">

                    <div class="mb-4" id="car_price_div">
                        <label for="" class="form-label">Price: </label>
                        <input type="number" id="car_price1" name="car_prices" class="form-control">
                    </div>
                    <div class="mb-4" id="motor_price_div">
                        <label for="" class="form-label">Price: </label>
                        <input type="number" id="motor_price1" name="motor_prices" class="form-control">
                    </div>
                    <input type="hidden" id="motor_price_old1" name="motor_prices_old" class="form-control">
                    <input type="hidden" id="car_price_old1" name="car_prices_old" class="form-control">

                    <input type="hidden" id="category_id" name="category_id" class="form-control">
                    <input type="hidden" id="sub_category_id" name="sub_category_id" class="form-control">


                    <div class="row">
                        <div class="col-md-6">
                            <label for="" class="form-label">Min of Vehicles: </label>
                            <input type="number" id="min_vehicles" name="min_vehicles" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label for="" class="form-label">Max of Vehicles: </label>
                            <input type="number" id="max_vehicles" name="max_vehicles" class="form-control">
                        </div>
                        <input type="hidden" id="old_min_vehicles" name="old_min_vehicles">
                        <input type="hidden" id="old_max_vehicles" name="old_max_vehicles">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="rateName" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Rate Settings</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="/add_rate_settings" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-4">
                        <label for="" class="form-label">Category: </label>
                        <select id="category_id2" name="category_id2" onchange="categoryChange2($(this).val())" class="form-select form-select-lg mb-3" aria-label=".form-select-lg example" required>
                            <option value="">---</option>
                            @foreach ($categories as $category)
                            <option value="<?= $category->id ?>">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-4">

                        <div class="form-group">
                            <label for="Sub Category">Sub Category</label>
                            <select name="sub_category_id2" id="sub_category2" class="form-select " aria-label="Default select example" required>
                                <option value="">---</option>

                            </select>

                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="" class="form-label">Rate name: </label>
                        <input type="text" name="rate_name" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('links_js')
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.js"></script>
<script>
    $(document).ready(function() {
        $('#table_id').DataTable();
    });
    $(document).ready(function() {
        $('#table_id2').DataTable();
    });

    function deleteMe(id) {
        Swal.fire({
            title: 'Are you sure to delete this details?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'

        }).then((result) => {
            if (result.isConfirmed) {
                $.get("/delete_price/" + id, {
                        id: id
                    },
                    function() {
                        Swal.fire({
                            title: 'Deleted Successfully',
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
                    });
            }
        })
    }

    function deleteRate(id) {
        Swal.fire({
            title: 'Are you sure to delete this details?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'

        }).then((result) => {
            if (result.isConfirmed) {
                $.get("/delete_rate/" + id, {
                        id: id
                    },
                    function() {
                        Swal.fire({
                            title: 'Deleted Successfully',
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
                    });
            }
        })
    }

    function categoryChange(id) {
        $('#sub_category').html('');
        $('#sub_category').append(`<option value="">--- </option>`);
        $.get('/getSubCategories/' + id,
            function(response) {
                response.forEach(function(a) {
                    var e = `<option value="${a.id}">${a.name}</option>`;
                    $('#sub_category').append(e);

                });


            });

    }

    function categoryChange1(id) {
        $('#sub_category1').html('');
        $('#sub_category1').append(`<option value="">--- </option>`);
        $.get('/getSubCategories/' + id,
            function(response) {
                response.forEach(function(a) {
                    var e = `<option value="${a.id}">${a.name}</option>`;
                    $('#sub_category1').append(e);

                });


            });

    }

    function categoryChange2(id) {
        $('#sub_category2').html('');
        $('#sub_category2').append(`<option value="">--- </option>`);
        $.get('/getSubCategories/' + id,
            function(response) {
                response.forEach(function(a) {
                    var e = `<option value="${a.id}">${a.name}</option>`;
                    $('#sub_category2').append(e);

                });


            });

    }

    function getPrice(id) {
        $.get('/add-price/' + id, function(price) {
            $('#price_id').val(price.id);
            $('#category_id1').val(price.category_id);
            $('#sub_category1').val(price.sub_category_id);
            $('#car_price1').val(price.car_price);
            $('#motor_price1').val(price.motor_price);
            $('#car_price_old1').val(price.car_price);
            $('#motor_price_old1').val(price.motor_price);
            $('#category_id').val(price.category_id);
            $('#sub_category_id').val(price.sub_category_id);
            $('#min_vehicles').val(price.min);
            $('#max_vehicles').val(price.max);

            $('#old_min_vehicles').val(price.min);
            $('#old_max_vehicles').val(price.max);


            document.getElementById('car_price_div').style.display = price.car_price ? 'block' : 'none';
            document.getElementById('motor_price_div').style.display = price.motor_price ? 'block' : 'none';
        })
    }

    function sub_categoryChange(id) {
        $('#no_cars').html('');
        $('#no_cars').append(`<option value="">--- </option>`);
        var e = '';
        $.get('/getSubCategoriesChange/' + id,
            function(response) {
                response.forEach(function(a) {
                    var e = `<option value="${a.id}">${a.rate_name} </option>`;
                    $('#no_cars').append(e);

                });
            });
    }

    filterChange($('#filter').val());

    function filterChange(id) {
        $('#table-1-rows').html('');
        $.get('/filterChange/' + id,
            function(response) {
                console.log(response)

                response.forEach(function(e) {
                    var price = e.car_price || e.motor_price;
                    var a = `
                                    <tr>
                                        <td class="text-nowrap">${e.category_name}</td>
                                        <td>${e.sub_category_name}</td>
                                        <td>${e.vehicle_type}</td>
                                        <td>${e.rate_name}</td>
                                        <td class="text-nowrap">₱ ${price}</td>
                                         <td class="d-flex">
                                        <div class="me-2">

                                            <input type="hidden" name="delete_id">
                                         

                                        </div>
                                        <div>
                                            <a data-bs-toggle="modal" onclick="getPrice(${e.price_id})" data-bs-target="#editPrice" href="javascript:void(0)" class="btn btn-success btn-md">
                                                <i class="far fa-edit"></i>
                                            </a>
                                        </div>
                                    </td>
                                    </tr>`;

                    $('#table-1-rows').append(a);
                });

            });
    }

    filterChangeRate($('#filter_rate').val());

    function filterChangeRate(id) {
        $('#table-2-rows').html('');
        $.get('/filterChangeRate/' + id, function(response) {

            response.forEach(function(e) {
                var a = `<tr>

                <td class="text-nowrap">${e.category_name}</td>
                <td>${e.sub_category_name}</td>
                <td>${e.rate_name}</td>


                <td class="d-flex">
                    <div class="me-2">

                        <input type="hidden" name="delete_id">
                        <button onclick="deleteRate(${e.rate_id})" class="btn btn-danger btn-md" type="submit">
                            <i class="far fa-trash-alt"></i>
                        </button>

                    </div>
                 
                </td>
                </tr>`;
                $('#table-2-rows').append(a);
            });
        })
    }
</script>
<?php
if (session()->has('success')) {
?>

    <script>
        Swal.fire({
            title: '<?php echo  session()->get('success');  ?>',
            icon: 'success',
            showCancelButton: false,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Okay'
        })
    </script>
<?php
}


?>

<?php
if (session()->has('error')) {
?>

    <script>
        Swal.fire({
            title: '<?php echo  session()->get('error');  ?>',
            icon: 'error',
            showCancelButton: false,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Okay'
        })
    </script>
<?php
}


?>
<!-- <button onclick="deleteMe(${e.price_id})" class="btn btn-danger btn-md" type="submit">
                                                <i class="far fa-trash-alt"></i>
                                            </button> -->
@endsection