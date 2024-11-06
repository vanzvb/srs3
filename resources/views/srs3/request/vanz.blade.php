@extends('layouts.guest')

@section('title', 'Sticker Application Request')

@section('content')

    <div class="container px-md-5">
        <div class=" px-md-5 mb-3">

            <!-- Button to open the modal -->
            <button onclick="showModal()">Add File</button>

            <!-- Modal for file upload -->
            <div id="fileUploadModal"
                style="display:none; position:fixed; top:50%; left:50%; transform:translate(-50%, -50%); background-color: #fff; padding:20px; border-radius:5px;">
                <h3>Upload File</h3>
                <input type="file" id="fileInput" />
                <button onclick="saveFile()">Save</button>
                <button onclick="closeModal()">Cancel</button>
            </div>

            <!-- Table to display uploaded files -->
            <table id="fileTable">
                <thead>
                    <tr>
                        <th>File Name</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>


        </div>
    </div>

@endsection
<script>
let filesArray = [];

// Function to show the modal for file upload
function showModal() {
  document.getElementById("fileUploadModal").style.display = "block";
}

// Function to hide the modal
function closeModal() {
  document.getElementById("fileUploadModal").style.display = "none";
}

// Function to add a file to the array and update the table
function saveFile() {
  const fileInput = document.getElementById("fileInput");
  const file = fileInput.files[0];

  if (file) {
    filesArray.push(file); // Add the file to the array
    updateTable(); // Update the table to show the new file
  }

  fileInput.value = ""; // Clear input after saving
  closeModal(); // Close the modal
}

// Function to update the table display
function updateTable() {
  const tableBody = document.getElementById("fileTable").querySelector("tbody");
  tableBody.innerHTML = "";

  filesArray.forEach((file, index) => {
    const row = document.createElement("tr");

    const fileNameCell = document.createElement("td");
    fileNameCell.textContent = file.name;
    row.appendChild(fileNameCell);

    const actionCell = document.createElement("td");
    const editButton = document.createElement("button");
    editButton.textContent = "Edit";
    editButton.onclick = () => editFile(index);
    actionCell.appendChild(editButton);
    row.appendChild(actionCell);

    tableBody.appendChild(row);
  });
}

// Function to edit a file in the array
function editFile(index) {
  showModal(); // Show modal to allow file replacement

  // Save the new file and replace the old one in the array
  document.getElementById("fileInput").onchange = () => {
    const newFile = document.getElementById("fileInput").files[0];
    if (newFile) {
      filesArray[index] = newFile; // Replace the file in the array
      updateTable(); // Refresh the table
      closeModal(); // Close modal
    }
  };
}

</script>
@section('links_js')
