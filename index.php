
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Downloaded Data</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

</head>
<body>

<div class="container mt-5">

    <!-- Search bar -->
    <div class="mb-3">
        <label for="search" class="form-label">Search:</label>
        <input type="text" id="search" name="random_name_123" autocomplete="off" class="form-control" onkeyup="filterTable()" value="" placeholder="Type to search">
    </div>
    <table class="table table-bordered" id="dataTable" border="1">
        <thead>
        <tr>
            <th>Task</th>
            <th>Title</th>
            <th>Description</th>
            <th>Color Code</th>
        </tr>
        </thead>
        <tbody id="tableData">
        <tr>
        </tr>
        </tbody>
    </table>

    <!-- Button to open the modal -->
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#imageModal">
        Open Image Modal
    </button>

    <!-- Modal -->
    <div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="imageModalLabel">Select Image</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <!-- File input to select an image -->
                    <input type="file" id="imageInput" class="form-control mb-3" accept=".png, .jpg, .jpeg">

                    <!-- Display selected image -->
                    <img id="selectedImage" class="img-fluid" alt="No Selected Image"  style="display: none">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

</div>

<!-- Bootstrap JS (optional, but needed for certain features) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function fetchDataAndRefreshTable() {
        // Fetch new data from the server using PHP
        fetch('fetch_data.php')
            .then(response => response.json())
            .then(data => {
                // Update the table with the new data
                const tableBody = document.getElementById('dataTable').getElementsByTagName('tbody')[0];
                //tableBody.innerHTML = ''; // Clear existing rows
                let tableData = "";
                data.forEach(row => {
                    tableData += "<tr>\n" +
                        "            <td>" +row.task+ "</td>\n" +
                        "            <td>" +row.title+ "</td>\n" +
                        "            <td>" +row.description+ "</td>\n" +
                        "            <td style=\"background-color: " +row.colorCode+ ";\">" +row.colorCode+ "</td>\n" +
                        "        </tr>";
                });
                tableBody.innerHTML = tableData;
                /*data.forEach(row => {
                    const newRow = tableBody.insertRow();
                    row.forEach(cellValue => {
                        const cell = newRow.insertCell();
                        cell.textContent = cellValue;
                    });
                });*/
            })
            .catch(error => {
                console.error('Error fetching data:', error);
            });
    }

    // Fetch data initially
    fetchDataAndRefreshTable();

    // Schedule auto-refresh every 60 minutes
    setInterval(fetchDataAndRefreshTable, 60 * 60 * 1000);

    //function to search something in table
    function filterTable() {
        // Declare variables
        var input, filter, table, tr, td, i, txtValue;
        input = document.getElementById("search");
        filter = input.value.toUpperCase();
        table = document.getElementById("dataTable");
        tr = table.getElementsByTagName("tr");

        // Loop through all table rows, and hide those that don't match the search query
        for (i = 0; i < tr.length; i++) {
            td = tr[i].getElementsByTagName("td");
            for (var j = 0; j < td.length; j++) {
                if (td[j]) {
                    txtValue = td[j].textContent || td[j].innerText;
                    if (txtValue.toUpperCase().indexOf(filter) > -1) {
                        tr[i].style.display = "";
                        break;
                    } else {
                        tr[i].style.display = "none";
                    }
                }
            }
        }
    }

    //get and display image in the modal
    document.getElementById('imageInput').addEventListener('change', function (event) {
        const selectedImage = document.getElementById('selectedImage');
        const fileInput = event.target;

        if (fileInput.files && fileInput.files[0]) {
            const reader = new FileReader();

            reader.onload = function (e) {
                selectedImage.style.display = 'block';
                selectedImage.src = e.target.result;
            };

            reader.readAsDataURL(fileInput.files[0]);
        }
    });
</script>

</body>
</html>
