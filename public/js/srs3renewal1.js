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


// 

document.getElementById('renewal_request_form').addEventListener('submit', function(e) {
    e.preventDefault();  // Prevent the default form submission

    if (e.submitter.id === 'request_submit_btn') {
        // Handle 'Submit Renewal' button logic
        console.log('Submit Renewal button clicked');
        // You can submit the form using JS if needed
        this.submit();  // Submit the form as normal
    } else if (e.submitter.id === 'save_btn') {
        // Handle 'Save' button logic (e.g., dismiss modal, save draft)
        console.log('Save button clicked');
        // Perform any specific action for the 'Save' button
        // You might not want to submit the form here
        // Dismiss modal automatically because of `data-bs-dismiss="modal"`
    }
});

