document.addEventListener('DOMContentLoaded', function() {
    let addressesArray = []; // Assuming this is already populated from the previous logic

    const addressDropdown = document.getElementById('addressDropdown');

    // Function to populate the dropdown with address names
    window.populateAddressDropdown = function() {
        // Clear any existing options except the default one
        addressDropdown.innerHTML = `<option value="">-- Select Address --</option>`;

        // Populate dropdown with address names from addressesArray
        addressesArray.forEach(function(address, index) {
            const option = document.createElement('option');
            option.value = index; // You can use the index or address as value
            option.textContent = address.addressName; // Show the address name
            addressDropdown.appendChild(option);
        });
    };
});
