document.addEventListener('DOMContentLoaded', function() {
    let addressCount = 0;

    // Get the form and modal elements
    const addressModal = document.getElementById('addressModal');
    const saveAddressBtn = document.getElementById('saveAddressBtn');

    // Attach the click event once (outside of the modal show event)
    saveAddressBtn.addEventListener('click', function() {
        const addressForm = document.getElementById('addressForm');
        const addressInput = document.getElementById('addressName');

        // Clear previous validation state
        addressInput.classList.remove('is-invalid');

        if (!addressInput.value.trim()) {
            // Show validation error if input is empty
            addressInput.classList.add('is-invalid');
        } else {
            // Process form (e.g., save address to table)
            addressCount++;

            // Create a new row for the table
            const newRow = document.createElement('tr');
            newRow.innerHTML = `
                <td>${addressCount}</td>
                <td>${addressInput.value.trim()}</td>
            `;

            // Append the new row to the table body
            document.getElementById('addressesTable').appendChild(newRow);

            // Reset the form (if found)
            if (addressForm) {
                addressForm.reset();
            } else {
                console.error("Form element not found!");
            }

            // Close the modal programmatically
            const modalInstance = bootstrap.Modal.getInstance(addressModal);
            if (modalInstance) {
                modalInstance.hide(); // Close the modal
            } else {
                console.error("Modal instance not found!");
            }
        }
    });
});
