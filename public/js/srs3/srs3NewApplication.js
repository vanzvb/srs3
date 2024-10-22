function toggleFields() {
    const accountType = document.getElementById('account_type').value;
    const individualFields = document.getElementById('individualFields');
    const companyFields = document.getElementById('companyFields');

    const companyNameInput = document.getElementById('company_name');
    const companyRepInput = document.getElementById('company_representative');

    if (accountType === 'company') {
        individualFields.style.display = 'none';
        companyFields.style.display = 'block';

        companyNameInput.setAttribute('required', 'required');
        companyRepInput.setAttribute('required', 'required');
    } else {
        individualFields.style.display = 'block';
        companyFields.style.display = 'none';

        companyNameInput.removeAttribute('required');
        companyRepInput.removeAttribute('required');
    }
}

// Call toggleFields on page load to set initial visibility
window.onload = toggleFields;