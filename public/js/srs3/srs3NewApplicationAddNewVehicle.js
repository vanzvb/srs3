document.addEventListener('DOMContentLoaded', function() {
    let vehiclesArray = []; // Array to hold vehicles
    let vehicleCount = 0;
    let editVehicleIndex = null; // Track the index for editing

    const vehicleModal = document.getElementById('vehicleModal');
    const saveVehicleBtn = document.getElementById('saveVehicleBtn');
    const vehiclesTable = document.getElementById('vehiclesTable');
    const vehiclesArrayInput = document.getElementById('vehiclesArrayInput');
    const addressDropdown = document.getElementById('addressDropdown');

    const plateNoInput = document.getElementById('plateNo');
    const brandInput = document.getElementById('brand');
    const seriesInput = document.getElementById('series');
    const yearModelInput = document.getElementById('year_model'); // Added
    const colorInput = document.getElementById('color'); // Added
    const vehicleTypeInput = document.getElementById('vehicle_type'); // Added

    // Owner Info
    const firstNameInput = document.getElementById('first_name_modal');
    const middleNameInput = document.getElementById('middle_name_modal');
    const lastNameInput = document.getElementById('last_name_modal');
    const mainContactInput = document.getElementById('main_contact_no_modal');
    const secondaryContactInput = document.getElementById('secondary_contact_no_modal');
    const tertiaryContactInput = document.getElementById('tertiary_contact_no_modal');

    // File inputs for OR and CR
    const orAttachmentInput = document.getElementById('orAttachment');
    const crAttachmentInput = document.getElementById('crAttachment');

    const populateFromIndividualCheckbox = document.getElementById('populateFromIndividual');
    const useIndividualFieldsInput = document.getElementById('useIndividualFields');

    // Event listener for the checkbox
    populateFromIndividualCheckbox.addEventListener('change', function() {
        if (this.checked) {
            // Populate modal fields with values from individual fields
            firstNameInput.value = document.getElementById('first_name').value;
            middleNameInput.value = document.getElementById('middle_name').value;
            lastNameInput.value = document.getElementById('last_name').value;
            mainContactInput.value = document.getElementById('contact_no').value;
            secondaryContactInput.value = document.getElementById('secondary_contact_no').value;
            tertiaryContactInput.value = document.getElementById('tertiary_contact_no').value;
            // Optionally clear the secondary and tertiary contacts if desired
            // document.getElementById('secondary_contact_no').value = '';
            // document.getElementById('tertiary_contact_no').value = '';
            useIndividualFieldsInput.value = '1';
        } else {
            // Clear modal fields
            firstNameInput.value = '';
            middleNameInput.value = '';
            lastNameInput.value = '';
            mainContactInput.value = '';
            secondaryContactInput = '';
            tertiaryContactInput = '';
            useIndividualFieldsInput.value = '0';
        }
    });

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
                year_model: yearModelInput.value, // Get selected year model
                color: colorInput.value, // Get selected color
                vehicle_type: vehicleTypeInput.value, // Get selected vehicle type
                addressIndex: addressDropdown.value, // Get selected address index

                // Owner Info
                firstName: firstNameInput.value.trim(),
                middleName: middleNameInput.value.trim(),
                lastName: lastNameInput.value.trim(),
                mainContact: mainContactInput.value.trim(),
                secondaryContact: secondaryContactInput.value.trim(),
                tertiaryContact: tertiaryContactInput.value.trim(),

                useIndividualFields: populateFromIndividualCheckbox.checked,

                orAttachment: orAttachmentInput.files[0] ? orAttachmentInput.files[0].name : '', // Store file name
                crAttachment: crAttachmentInput.files[0] ? crAttachmentInput.files[0].name : ''  // Store file name
            };

            console.log("Selected Address Index: ", addressDropdown.value);

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
                <button type="button" class="btn btn-sm btn-warning editVehicleBtn">Edit</button>
                <button type="button" class="btn btn-sm btn-danger deleteVehicleBtn">Delete</button>
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
            yearModelInput.value = vehicle.year_model; // Load year model
            colorInput.value = vehicle.color; // Load color
            vehicleTypeInput.value = vehicle.vehicle_type; // Load vehicle type
            addressDropdown.value = vehicle.addressIndex;

            populateFromIndividualCheckbox.checked = vehicle.useIndividualFields;

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
        yearModelInput.classList.remove('is-invalid'); // Added
        colorInput.classList.remove('is-invalid'); // Added
        vehicleTypeInput.classList.remove('is-invalid'); // Added
        firstNameInput.classList.remove('is-invalid');
        lastNameInput.classList.remove('is-invalid');
        mainContactInput.classList.remove('is-invalid');

        addressDropdown.classList.remove('is-invalid');

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
        if (!yearModelInput.value) { // Added validation for year model
            yearModelInput.classList.add('is-invalid');
            isValid = false;
        }
        if (!colorInput.value) { // Added validation for color
            colorInput.classList.add('is-invalid');
            isValid = false;
        }
        if (!vehicleTypeInput.value) { // Added validation for vehicle type
            vehicleTypeInput.classList.add('is-invalid');
            isValid = false;
        }
        if (!addressDropdown.value) {
            addressDropdown.classList.add('is-invalid');
            isValid = false;
        }

        if (!firstNameInput.value.trim()) {
            firstNameInput.classList.add('is-invalid');
            isValid = false;
        }
        if (!lastNameInput.value.trim()) {
            lastNameInput.classList.add('is-invalid');
            isValid = false;
        }
        if (!mainContactInput.value.trim()) {
            mainContactInput.classList.add('is-invalid');
            isValid = false;
        }

        return isValid;
    }

    // Function to reset the vehicle form
    function resetVehicleForm() {
        plateNoInput.value = '';
        brandInput.value = '';
        seriesInput.value = '';
        yearModelInput.value = ''; // Reset year model
        colorInput.value = ''; // Reset color
        vehicleTypeInput.value = ''; // Reset vehicle type
        addressDropdown.value = '';
        editVehicleIndex = null; // Reset the edit index

        firstNameInput.value = '';
        middleNameInput.value = '';
        lastNameInput.value = '';
        mainContactInput.value = '';
        secondaryContactInput.value = '';
        tertiaryContactInput.value = '';

        orAttachmentInput.value = '';
        crAttachmentInput.value = '';
        populateFromIndividualCheckbox.checked = false;
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
