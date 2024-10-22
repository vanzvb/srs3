document.addEventListener('DOMContentLoaded', function() {
    let vehiclesArray = []; // Array to hold vehicles
    let vehicleCount = 0;
    let editVehicleIndex = null; // Track the index for editing

    const vehicleModal = document.getElementById('vehicleModal');
    const saveVehicleBtn = document.getElementById('saveVehicleBtn');
    const vehiclesTable = document.getElementById('vehiclesTable');
    const vehiclesArrayInput = document.getElementById('vehiclesArrayInput');

    const plateNoInput = document.getElementById('plateNo');
    const brandInput = document.getElementById('brand');
    const seriesInput = document.getElementById('series');

    // Function to update the table based on vehiclesArray
    function updateVehicleTable() {
        vehiclesTable.innerHTML = ''; // Clear the table body

        // Check if vehiclesArray is empty
        if (vehiclesArray.length === 0) {
            vehiclesTable.innerHTML = `
                <tr>
                    <td colspan="5" class="text-center">No Vehicle</td>
                </tr>
            `;
        } else {
            vehiclesArray.forEach((vehicle, index) => {
                addVehicleRow(vehicle, index); // Add each vehicle to the table
            });
        }
    }

    saveVehicleBtn.addEventListener('click', function() {
        // Validate form fields
        if (validateVehicleForm()) {
            const newVehicle = {
                plateNo: plateNoInput.value.trim(),
                brand: brandInput.value.trim(),
                series: seriesInput.value.trim(),
                addressIndex: addressDropdown.value
            };

            if (editVehicleIndex === null) {
                // Adding a new vehicle
                vehiclesArray.push(newVehicle);
            } else {
                // Editing an existing vehicle
                vehiclesArray[editVehicleIndex] = newVehicle;
            }

            // Serialize the vehiclesArray to a JSON string and set the value in the hidden input field
            vehiclesArrayInput.value = JSON.stringify(vehiclesArray);

            resetVehicleForm();
            closeVehicleModal();
            updateVehicleTable(); // Update the table to reflect changes
        }
    });

    // Function to add a new row to the vehicle table
    function addVehicleRow(vehicle, index) {
        const newRow = document.createElement('tr');
        newRow.setAttribute('data-index', index);

        newRow.innerHTML = `
            <td>${index + 1}</td>
            <td>${vehicle.plateNo}</td>
            <td>${vehicle.brand}</td>
            <td>${vehicle.series}</td>
            <td>
                <button class="btn btn-sm btn-warning editVehicleBtn">Edit</button>
                <button class="btn btn-sm btn-danger deleteVehicleBtn">Delete</button>
            </td>
        `;
        vehiclesTable.appendChild(newRow);

        newRow.querySelector('.editVehicleBtn').addEventListener('click', function() {
            loadVehicleForEditing(index);
        });

        newRow.querySelector('.deleteVehicleBtn').addEventListener('click', function() {
            deleteVehicle(index);
        });
    }

    // Function to delete a vehicle
    function deleteVehicle(index) {
        vehiclesArray.splice(index, 1); // Remove the vehicle from the array
        updateVehicleTable(); // Update the table after deletion
    }

    // Function to load a vehicle into the modal for editing
    function loadVehicleForEditing(index) {
        const vehicle = vehiclesArray[index];

        if (vehicle) {
            editVehicleIndex = index; // Set the current index to edit
            plateNoInput.value = vehicle.plateNo;
            brandInput.value = vehicle.brand;
            seriesInput.value = vehicle.series;

            const modalInstance = bootstrap.Modal.getOrCreateInstance(vehicleModal);
            modalInstance.show();
        }
    }

    // Function to validate vehicle form
    function validateVehicleForm() {
        let isValid = true;
        plateNoInput.classList.remove('is-invalid');
        brandInput.classList.remove('is-invalid');
        seriesInput.classList.remove('is-invalid');

        if (!plateNoInput.value.trim()) {
            plateNoInput.classList.add('is-invalid');
            isValid = false;
        }
        if (!brandInput.value) {
            brandInput.classList.add('is-invalid');
            isValid = false;
        }
        if (!seriesInput.value.trim()) {
            seriesInput.classList.add('is-invalid');
            isValid = false;
        }

        return isValid;
    }

    // Function to reset the vehicle form
    function resetVehicleForm() {
        plateNoInput.value = '';
        brandInput.value = '';
        seriesInput.value = '';
        editVehicleIndex = null; // Reset the edit index
    }

    // Function to close the vehicle modal
    function closeVehicleModal() {
        const modalInstance = bootstrap.Modal.getInstance(vehicleModal);
        if (modalInstance) {
            modalInstance.hide();
        }
    }

    // Call to update the table when the page first loads
    updateVehicleTable();
});
