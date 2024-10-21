let addressCount = 0;

document.getElementById('saveAddressBtn').addEventListener('click', function() {
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

        // Reset the form
        addressForm.reset();

        // Close the modal programmatically
        const modal = bootstrap.Modal.getInstance(document.getElementById('addressModal'));
        modal.hide();
    }
});