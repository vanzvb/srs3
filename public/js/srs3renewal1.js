// This is when you click the dropdown "Account Type" and picked 'Company' a textbox will show
document.getElementById('account_type').addEventListener('change', function() {
    var companyNameContainer = document.getElementById('company-name-container');
    var companyRepContainer = document.getElementById('company-representative-container');
    var individualName = document.getElementById('individual-name-container');
    
    
    if (this.value === '1') {
        companyNameContainer.style.display = 'block';
        companyRepContainer.style.display = 'block';

        individualName.style.display = 'none';
    } else {
        companyNameContainer.style.display = 'none';
        companyRepContainer.style.display = 'none';

        individualName.style.display = 'block';
    }
});
// Trigger the change event to handle the default selected value
document.getElementById('account_type').dispatchEvent(new Event('change'));