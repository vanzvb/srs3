// This is when you click the dropdown "Account Type" and picked 'Company' a textbox will show
document.getElementById('account_type').addEventListener('change', function() {
    var companyNameContainer = document.getElementById('company-name-container');
    
    if (this.value === 'company') {
        companyNameContainer.style.display = 'block';
    } else {
        companyNameContainer.style.display = 'none';
    }
});
// Trigger the change event to handle the default selected value
document.getElementById('account_type').dispatchEvent(new Event('change'));


// For Removing Button
document.querySelectorAll('.btn-remove').forEach(button => {
    button.addEventListener('click', function() {
        const vehicleId = this.getAttribute('data-id');
        const row = document.getElementById('vehicle-row-' + vehicleId);
        
        // Show a confirmation alert
        if (confirm('Are you sure you want to remove this vehicle from the list?')) {
            if (row) {
                row.remove(); // Remove the row from the DOM
            }
        }
        // If the user clicks 'No', the row won't be removed
    });
});
