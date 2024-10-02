function toggleFields() {
    const accountType = document.getElementById('account_type').value;
    const individualFields = document.getElementById('individualFields');
    const companyFields = document.getElementById('companyFields');

    if (accountType === 'company') {
        individualFields.style.display = 'none';
        companyFields.style.display = 'block';
    } else {
        individualFields.style.display = 'block';
        companyFields.style.display = 'none';
    }
}

// Call toggleFields on page load to set initial visibility
window.onload = toggleFields;