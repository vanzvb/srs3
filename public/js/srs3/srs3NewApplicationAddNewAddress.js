document.addEventListener('DOMContentLoaded', function() {
    let addressesArray = []; // Array to hold addresses
    let addressCount = 0;
    let editIndex = null; // Track the index for editing

    const addressModal = document.getElementById('addressModal');
    const saveAddressBtn = document.getElementById('saveAddressBtn');
    const addressesTable = document.getElementById('addressesTable');
    const addressesArrayInput = document.getElementById('addressesArray');

    const addressInput = document.getElementById('addressName');
    const blockInput = document.getElementById('block');
    const lotInput = document.getElementById('lot');
    const houseNumberInput = document.getElementById('houseNumber');

    saveAddressBtn.addEventListener('click', function() {
        let isValid = validateForm();

        if (isValid) {
            const newAddress = {
                addressName: addressInput.value.trim(),
                block: blockInput.value.trim(),
                lot: lotInput.value.trim(),
                houseNumber: houseNumberInput.value.trim(),
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
        }
    });

    // Function to add a new row to the table
    function addAddressRow(address, index) {
        const newRow = document.createElement('tr');
        newRow.setAttribute('data-index', index);

        newRow.innerHTML = `
            <td>${index + 1}</td>
            <td>${address.addressName}</td>
            <td>${address.block}</td>
            <td>${address.lot}</td>
            <td>${address.houseNumber}</td>
            <td>
                <button class="btn btn-sm btn-warning editAddressBtn">Edit</button>
                <button class="btn btn-sm btn-danger deleteAddressBtn">Delete</button>
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
                <td>${address.addressName}</td>
                <td>${address.block}</td>
                <td>${address.lot}</td>
                <td>${address.houseNumber}</td>
                <td>
                    <button class="btn btn-sm btn-warning editAddressBtn">Edit</button>
                    <button class="btn btn-sm btn-danger deleteAddressBtn">Delete</button>
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
            addressInput.value = address.addressName;
            blockInput.value = address.block;
            lotInput.value = address.lot;
            houseNumberInput.value = address.houseNumber;

            const modalInstance = bootstrap.Modal.getOrCreateInstance(addressModal);
            modalInstance.show();
        }
    }

    // Function to delete an address
    function deleteAddress(index) {
        addressesArray.splice(index, 1); // Remove the address from the array
        refreshAddressTable();
        addressesArrayInput.value = JSON.stringify(addressesArray);
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

        addressInput.classList.remove('is-invalid');
        blockInput.classList.remove('is-invalid');
        lotInput.classList.remove('is-invalid');
        houseNumberInput.classList.remove('is-invalid');

        if (!addressInput.value.trim()) {
            addressInput.classList.add('is-invalid');
            isValid = false;
        }
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
        addressInput.value = '';
        blockInput.value = '';
        lotInput.value = '';
        houseNumberInput.value = '';
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
