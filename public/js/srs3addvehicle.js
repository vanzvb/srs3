    // Function to add vehicle row to the table
    document.getElementById('addVehicleBtn').addEventListener('click', function () {
        // Get values from the input fields
        let plateNumber = document.getElementById('plateNumber').value;
        // let brandSeries = document.getElementById('brandSeries').value;
        let series1 = document.getElementById('series1').value;
        let brand1 = document.getElementById('brand1').value;
        let category = document.getElementById('category').value;
        let subCategory1 = document.getElementById('subCategory1').value;
        let hoa = document.getElementById('hoa').value;

        // Create a new row
        let tableBody = document.getElementById('vehicleTableBody');
        let newRow = document.createElement('tr');

        // Create the columns and set the values
        newRow.innerHTML = `
            <td>${plateNumber}</td>
            <td>${brand1} ${series1}</td>
            <td>${category}</td>
            <td>${subCategory1}</td>
            <td>${hoa}</td>
            <td>
            <button class="btn btn-primary btn-sm">View</button>
            <button class="btn btn-danger btn-sm removeVehicleBtn">Remove</button>
            </td>
        `;

        // Add the new row to the table body
        tableBody.appendChild(newRow);

        // Clear the form fields after adding
        document.getElementById('plateNumber').value = '';
        // document.getElementById('brandSeries').value = '';
        document.getElementById('brand1').value = '';
        document.getElementById('series1').value = '';
        document.getElementById('category').value = '';
        document.getElementById('subCategory1').value = '';
        document.getElementById('hoa').value = '';

        // Hide the modal after adding
        const modal = bootstrap.Modal.getInstance(document.getElementById('addVehicleModal'));
        modal.hide();

        // Attach event listener to the 'Remove' button
        newRow.querySelector('.removeVehicleBtn').addEventListener('click', function () {
            newRow.remove();
        });
    });