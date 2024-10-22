document.addEventListener('DOMContentLoaded', function() {
    let addressesArray = []; // Array to hold addresses
    let addressCount = 0;
    let editIndex = null; // Track the index for editing

    const addressModal = document.getElementById('addressModal');
    const saveAddressBtn = document.getElementById('saveAddressBtn');
    const addressesTable = document.getElementById('addressesTable');
    const addressesArrayInput = document.getElementById('addressesArray');
    const addressForm = document.getElementById('addressForm');

    const addressInput = document.getElementById('addressName');
    const blockInput = document.getElementById('block');
    const lotInput = document.getElementById('lot');
    const houseNumberInput = document.getElementById('houseNumber');

    saveAddressBtn.addEventListener('click', function() {
        // Clear previous validation state
        addressInput.classList.remove('is-invalid');
        blockInput.classList.remove('is-invalid');
        lotInput.classList.remove('is-invalid');
        houseNumberInput.classList.remove('is-invalid');

        let isValid = true;

        // Validate each input
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

        if (isValid) {
            const newAddress = {
                addressName: addressInput.value.trim(),
                block: blockInput.value.trim(),
                lot: lotInput.value.trim(),
                houseNumber: houseNumberInput.value.trim(),
            };

            if (editIndex === null) {
                // Adding a new address
                addressCount++;
                addressesArray.push(newAddress);

                // Create a new row for the table
                addAddressRow(newAddress, addressCount - 1);
            } else {
                // Editing an existing address
                addressesArray[editIndex] = newAddress;

                // Update the row in the table
                updateAddressRow(newAddress, editIndex);
            }

            // Update the hidden input with the JSON string of the addresses array
            addressesArrayInput.value = JSON.stringify(addressesArray);

            // Reset form and modal state
            resetForm();
            closeAddressModal();
        }
    });

    // Function to add a new row to the table
    function addAddressRow(address, index) {
        const newRow = document.createElement('tr');
        newRow.setAttribute('data-index', index); // Track row index

        newRow.innerHTML = `
            <td>${index + 1}</td>
            <td>${address.addressName}</td>
            <td>${address.block}</td>
            <td>${address.lot}</td>
            <td>${address.houseNumber}</td>
            <td>
                <button class="btn btn-sm btn-warning editAddressBtn">Edit</button>
            </td>
        `;
        addressesTable.appendChild(newRow);

        // Add event listener to edit button
        newRow.querySelector('.editAddressBtn').addEventListener('click', function() {
            loadAddressForEditing(index);
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
                </td>
            `;

            // Re-attach the edit event listener
            row.querySelector('.editAddressBtn').addEventListener('click', function() {
                loadAddressForEditing(index);
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

            // Open the modal
            const modalInstance = bootstrap.Modal.getOrCreateInstance(addressModal);
            modalInstance.show();
        }
    }

    // Function to reset the form
    function resetForm() {
        addressInput.value = '';
        blockInput.value = '';
        lotInput.value = '';
        houseNumberInput.value = '';
        editIndex = null; // Reset edit index
    }

    // Function to close the modal
    function closeAddressModal() {
        const modalInstance = bootstrap.Modal.getInstance(addressModal);
        if (modalInstance) {
            modalInstance.hide();
        }
    }
});
