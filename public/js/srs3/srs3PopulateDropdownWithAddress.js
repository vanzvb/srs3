document.addEventListener('DOMContentLoaded', function() {
    // Assuming addressesArray is already populated with your logic
    const addressDropdown = document.getElementById('addressDropdown');

    // Function to populate the dropdown with address names
    window.populateAddressDropdown = function() {
        if (addressesArray.length === 0) {
            // If there are no addresses, prompt the user to add one first
            alert("Add Address first");
            return;
        }

        // Clear any existing options except the default one
        addressDropdown.innerHTML = `<option value="">-- Select Address --</option>`;

        // Populate dropdown with address names from addressesArray
        addressesArray.forEach(function(address, index) {
            const option = document.createElement('option');
            option.value = index; // Using the index or any identifier you prefer
            option.textContent = 'Address ' + (index+1); // Displaying address name
            addressDropdown.appendChild(option);

            // console.log(`Option Value (Index): ${option.value}, Address Name: ${option.textContent}`);
        });
    };
    
});
