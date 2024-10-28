let addressesArray = []; // Array to hold addresses

document.addEventListener('DOMContentLoaded', function() {

    let addressCount = 0;
    let editIndex = null; // Track the index for editing

    const addressModal = document.getElementById('addressModal');
    const saveAddressBtn = document.getElementById('saveAddressBtn');
    const addressesTable = document.getElementById('addressesTable');
    const addressesArrayInput = document.getElementById('addressesArray');

    const blockInput = document.getElementById('block');
    const lotInput = document.getElementById('lot');
    const houseNumberInput = document.getElementById('houseNumber');
    const categoryInput = document.getElementById('category_modal');
    const subCategoryInput = document.getElementById('sub_category_modal');
    const hoaInput = document.getElementById('HOA_modal');
    const memberTypeInput = document.getElementById('member_type_modal');

    const streetInput = document.getElementById('street_modal');
    const buildingNameInput = document.getElementById('building_name_modal');
    const subdivisionVillageInput = document.getElementById('subdivision_village_modal');

    const cityInput = document.getElementById('city_modal');
    const zipcodeInput = document.getElementById('zipcode_modal');

    saveAddressBtn.addEventListener('click', function() {
        let isValid = validateForm();

        if (isValid) {
            const newAddress = {
                // addressName: addressInput.value.trim(),
                block: blockInput.value.trim(),
                lot: lotInput.value.trim(),
                houseNumber: houseNumberInput.value.trim(),

                category_modal: categoryInput.value.trim(),
                category_name: categoryInput.options[categoryInput.selectedIndex].getAttribute('data-name'), 

                sub_category_modal: subCategoryInput.value.trim(),
                sub_category_name: subCategoryInput.options[subCategoryInput.selectedIndex].getAttribute('data-name'), 

                HOA_modal: hoaInput.value.trim(),
                // HOA_modal_name: hoaInput.options[hoaInput.selectedIndex].getAttribute('data-name'),
                HOA_modal_name: (!hoaInput.disabled && hoaInput.selectedIndex !== -1) 
                ? hoaInput.options[hoaInput.selectedIndex].getAttribute('data-name') 
                : '', // Set to 0 if disabled or no option selected

                member_type_modal: memberTypeInput.value.trim(),
                member_type_modal_name: memberTypeInput.options[memberTypeInput.selectedIndex].getAttribute('data-name'), 

                street_modal: streetInput.value.trim(),
                building_name_modal: buildingNameInput.value.trim(),
                subdivision_village_modal: subdivisionVillageInput.value.trim(),

                city_modal: cityInput.value.trim(),
                zipcode_modal: zipcodeInput.value.trim(),

            };

            if (editIndex === null) {
                // Adding a new address
                addressesArray.push(newAddress);
                addAddressRow(newAddress, addressesArray.length - 1);
            } else {
                // Editing an existing address
                addressesArray[editIndex] = newAddress;
                updateAddressRow(newAddress, editIndex);
            }

            addressesArrayInput.value = JSON.stringify(addressesArray);
            resetForm();
            closeModal();
            checkIfTableIsEmpty();
        }
    });

    function checkIfTableIsEmpty() {
        addressesTable.innerHTML = ''; // Clear the table
        if (addressesArray.length === 0) {
            addressesTable.innerHTML = `
                <tr>
                    <td colspan="6" class="text-center">No Address</td>
                </tr>
            `;
        } else {
            addressesArray.forEach((address, index) => {
                addAddressRow(address, index); // Rebuild the table rows
            });
        }
    }

    // Function to add a new row to the table
    function addAddressRow(address, index) {
        const newRow = document.createElement('tr');
        newRow.setAttribute('data-index', index);

        newRow.innerHTML = `
            <td>${index + 1}</td>
            <td>${address.category_name} / ${address.sub_category_name}</td>
            <td>${address.HOA_modal_name} / ${address.member_type_modal_name}</td>
            <td>${ 'Block : ' + address.block + ' Lot : ' + address.lot + ', ' + address.houseNumber + ', ' + address.street_modal 
                + ', ' + address.building_name_modal + ', ' + address.subdivision_village_modal + ', ' + address.HOA_modal_name
                + ', ' + address.city_modal + ', ' + address.zipcode_modal}
            </td>
            <td>
                <button type="button" class="btn btn-sm btn-warning editAddressBtn">Edit</button>
                <button type="button" class="btn btn-sm btn-danger deleteAddressBtn">Delete</button>
            </td>
        `;
        addressesTable.appendChild(newRow);

        newRow.querySelector('.editAddressBtn').addEventListener('click', function() {
            loadAddressForEditing(index);
        });

        newRow.querySelector('.deleteAddressBtn').addEventListener('click', function() {
            deleteAddress(index);
        });
    }

    // Function to update an existing row in the table
    function updateAddressRow(address, index) {
        const row = addressesTable.querySelector(`tr[data-index="${index}"]`);

        if (row) {
            row.innerHTML = `
                <td>${index + 1}</td>
                <td>${address.category_name} / ${address.sub_category_name}</td>
                <td>${address.HOA_modal_name} / ${address.member_type_modal_name}</td>
                <td>${ 'Block : ' + address.block + ' Lot : ' + address.lot + ', ' + address.houseNumber + ', ' + address.street_modal 
                    + ', ' + address.building_name_modal + ', ' + address.subdivision_village_modal + ', ' + address.HOA_modal_name
                    + ', ' + address.city_modal + ', ' + address.zipcode_modal}
                </td>
                <td>
                    <button type="button" class="btn btn-sm btn-warning editAddressBtn">Edit</button>
                    <button type="button" class="btn btn-sm btn-danger deleteAddressBtn">Delete</button>
                </td>
            `;

            row.querySelector('.editAddressBtn').addEventListener('click', function() {
                loadAddressForEditing(index);
            });

            row.querySelector('.deleteAddressBtn').addEventListener('click', function() {
                deleteAddress(index);
            });
        }
    }

    // Function to load an address into the modal for editing
    function loadAddressForEditing(index) {
        const address = addressesArray[index];

        if (address) {
            editIndex = index; // Set the current index to edit
            // addressInput.value = address.addressName;
            categoryInput.value = address.category_modal;
            subCategoryInput.value = address.sub_category_modal

            hoaInput.value = address.HOA_modal;
            memberTypeInput.value = address.member_type_modal

            blockInput.value = address.block;
            lotInput.value = address.lot;
            houseNumberInput.value = address.houseNumber;

            streetInput.value = address.street_modal;
            buildingNameInput.value = address.building_name_modal;
            subdivisionVillageInput.value = address.subdivision_village_modal;

            cityInput.value = address.city_modal;
            zipcodeInput.value = address.zipcode_modal;

            const modalInstance = bootstrap.Modal.getOrCreateInstance(addressModal);
            modalInstance.show();
        }
    }

    // Function to delete an address
    function deleteAddress(index) {
        addressesArray.splice(index, 1); // Remove the address from the array
        refreshAddressTable();
        addressesArrayInput.value = JSON.stringify(addressesArray);
        checkIfTableIsEmpty();
    }

    // Function to refresh the table after deleting an address
    function refreshAddressTable() {
        addressesTable.innerHTML = ''; // Clear the table
        addressesArray.forEach((address, index) => {
            addAddressRow(address, index); // Rebuild the table rows
        });
    }

    // Form validation
    function validateForm() {
        let isValid = true;

        // addressInput.classList.remove('is-invalid');
        blockInput.classList.remove('is-invalid');
        lotInput.classList.remove('is-invalid');
        houseNumberInput.classList.remove('is-invalid');

        // if (!addressInput.value.trim()) {
        //     addressInput.classList.add('is-invalid');
        //     isValid = false;
        // }
        if (!blockInput.value || isNaN(blockInput.value)) {
            blockInput.classList.add('is-invalid');
            isValid = false;
        }
        if (!lotInput.value || isNaN(lotInput.value)) {
            lotInput.classList.add('is-invalid');
            isValid = false;
        }
        if (!houseNumberInput.value || isNaN(houseNumberInput.value)) {
            houseNumberInput.classList.add('is-invalid');
            isValid = false;
        }

        return isValid;
    }

    // Function to reset the form
    function resetForm() {
        // addressInput.value = '';
        categoryInput.value = '';
        subCategoryInput.value = '';
        hoaInput.value = '';
        memberTypeInput.value = '';
        blockInput.value = '';
        lotInput.value = '';
        houseNumberInput.value = '';
        streetInput.value = '';
        buildingNameInput.value  = '';
        subdivisionVillageInput.value = '';
        cityInput.value  = '';
        zipcodeInput.value = '';

        editIndex = null;
    }

    // Function to close the modal
    function closeModal() {
        const modalInstance = bootstrap.Modal.getInstance(addressModal);
        if (modalInstance) {
            modalInstance.hide();
        }
    }
});
