<?php

require_once ('dbConfig.php');
require_once ('functions.php');

$userObj = new User();
$database = new Database();
$db = $database->dbConnection();

session_start();

if(!isset($_SESSION['session_login']))
{
    header("Location: index.php");
}

if(isset($_GET['logout']))
{
    session_destroy();
    unset($_SESSION);
    header("Location: index.php");
}

// Pagination logic
$results_per_page = 10; // Number of rows per page
$query = "SELECT COUNT(*) FROM resident_info";
$stmt = $userObj->runQuery($query);
$stmt->execute();
$total_results = $stmt->fetchColumn(); // Total number of rows

$total_pages = ceil($total_results / $results_per_page); // Total number of pages
$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // Current page number
$starting_limit = ($current_page - 1) * $results_per_page; // Starting limit for SQL

// Fetch paginated results from resident_info table
$qry = "SELECT * FROM resident_info ORDER BY first_name ASC LIMIT $starting_limit, $results_per_page";
$stmt = $userObj->runQuery($qry);
$stmt->execute();
$residents = $stmt->fetchAll(PDO::FETCH_ASSOC);


?>



<!DOCTYPE html>

<html>
    <head>

    <style>

                /* Dropdown styling to match the page design */
.navbar .dropdown-menu {
    background-color: #007bff; /* Match navbar background color */
    border: none;
    border-radius: 0;
    box-shadow: none;
}

/* Dropdown styling with larger, bold text */
.navbar .dropdown-item {
    color: white;
    font-family: 'Montserrat', sans-serif !important;
    font-size: 22px; /* Increase the font size */
    font-weight: bold; /* Make the text bold */
    padding: 10px 20px;
    transition: background-color 0.2s ease-in-out;
}

.navbar .dropdown-item:hover {
    background-color: #0056b3; /* Slightly darker blue for hover effect */
    color: white;
}


.navbar .dropdown-toggle::after {
    color: white; /* Match the dropdown arrow color */
}

/* Align dropdown properly */
.navbar .dropdown-menu {
    top: 100%;
    left: 0;
    right: auto;
    margin-top: 0;
}

.card {
    border-radius: 8px;
    padding: 20px;
    background-color: #f9f9f9;
}

.card-title {
    font-weight: bold;
    color: #333;
}

.btn-outline-success {
    font-size: 16px;
    padding: 10px 20px;
    transition: all 0.3s ease;
}

.btn-outline-success:hover {
    background-color: #28a745;
    color: white;
}

.fa-map-marker-alt {
    color: #007bff;
}

    .bg-custom-image .text-muted {
    background: linear-gradient(45deg, #007bff, #000);
    font-weight: bold;
    color: white !important; /* Override the 'text-muted' class */
}
</style>


        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="">
        <meta name="author" content="">

        <title> Residents </title>

        <!-- Montseratt Font -->
        <link rel="preconnect" href="https://fonts.gstatic.com">
        <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,400;0,500;0,800;1,500&display=swap" rel="stylesheet">
    
        <!-- Link for Bootstrap CSS -->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
        <!-- Link for Bootstrap Icons -->
        <script src="https://kit.fontawesome.com/4aee20adf0.js" crossorigin="anonymous"></script>
                <!-- Link for Local CSS -->
                <link rel="stylesheet" href="stylesheets/admin_Residents.css" >

    </head>

    <body>
        <div>

            <!-- Markup for Navbar -->
            <div>
    <nav class="navbar navbar-expand-lg mb-5">

        <button class="navbar-toggler navbar-light" type="button" data-toggle="collapse" data-target="#navbar1">
            <span class="navbar-toggler-icon"></span>
        </button>

        <img src="img/Logo.png" alt="logo" id="nav-img" class="ml-md-5">

        <div class="navbar-collapse collapse justify-content-center" id="navbar1">
            <ul class="navbar-nav">

                <!-- Home -->
                <li class="nav-item pl-3 pr-3">
                    <a class="nav-item nav-link" href="admin_Home.php">Home</a>
                </li>

                <!-- Dropdown: Membership Management -->
                <li class="nav-item dropdown pl-3 pr-3">
                    <a class="nav-link dropdown-toggle" href="#" id="membershipDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Membership Management
                    </a>
                    <div class="dropdown-menu" aria-labelledby="membershipDropdown">
                        <a class="dropdown-item" href="admin_Residents.php">Members</a>
                        <a class="dropdown-item" href="admin_Receipts.php">Receipts</a>
                        <a class="dropdown-item" href="admin_Transactions.php">Transactions</a>
                    </div>
                </li>

                <!-- Dropdown: Organizational Records -->
                <li class="nav-item dropdown pl-3 pr-3">
                    <a class="nav-link dropdown-toggle" href="#" id="recordsDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Organizational Records
                    </a>
                    <div class="dropdown-menu" aria-labelledby="recordsDropdown">
                        <a class="dropdown-item" href="admin_BarangayOfficials.php">Officers</a>
                        <a class="dropdown-item" href="admin_Cluster.php">Clusters</a>
                    </div>
                </li>

                <!-- Dropdown: Posts -->
                <li class="nav-item dropdown pl-3 pr-3">
                    <a class="nav-link dropdown-toggle" href="#" id="postsDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Posts
                    </a>
                    <div class="dropdown-menu" aria-labelledby="postsDropdown">
                        <a class="dropdown-item" href="admin_Announcements.php">Announcements</a>
                        <a class="dropdown-item" href="admin_Documents.php">QR Codes</a>
                    </div>
                </li>

                <!-- Logs -->
                <li class="nav-item pl-3 pr-3">
                    <a class="nav-link" href="admin_Logs.php">Logs</a>
                </li>

            </ul>
        </div>

        <a href="admin_Home.php?logout=true">
    <button style="font-family: 'Montserrat', sans-serif !important;" class="btn btn-outline-primary logout-btn mr-md-5" type="button" id="logout-btn">Logout</button>
</a>
    </nav>
</div>


            

<!-- Cluster Selection Section -->
<div class="row justify-content-center mb-4" id="cluster-selection">
    <div class="col-md-12">
        <div class="card shadow-sm">
            <div class="card-body d-flex flex-column align-items-center">
                <h4 class="card-title mb-3 text-center">Select Cluster</h4>
                <div class="d-flex justify-content-center w-100">
                    <button type="button" class="btn btn-outline-primary mx-2" onclick="loadBarangays('Eastern', this)">
                        <i class="fas fa-sun"></i> Eastern
                    </button>
                    <button type="button" class="btn btn-outline-primary mx-2" onclick="loadBarangays('San Antonio', this)">
                        <i class="fas fa-tree"></i> San Antonio
                    </button>
                    <button type="button" class="btn btn-outline-primary mx-2" onclick="loadBarangays('Western', this)">
                        <i class="fas fa-mountain"></i> Western
                    </button>
                    <button type="button" class="btn btn-outline-primary mx-2" onclick="loadBarangays('Poblacion', this)">
                        <i class="fas fa-city"></i> Poblacion
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Barangay Selection Section -->
<div class="row justify-content-center mb-4" id="barangay-selection" style="display:none;">
    <div class="col-md-25 ">
        <div class="card shadow-sm">
            <div class="card-body text-center">
                <h4 id="cluster-title" class="card-title d-none mb-3">Select Barangay from <span id="cluster-name"></span>:</h4>
                <div id="barangay-list" class="row d-none">
                    <!-- Barangay buttons will be dynamically added here -->
                </div>
                <!-- Back Button -->
                <button type="button" class="btn btn-outline-secondary mt-3" id="backToClusters" onclick="showClusters()">Back to Clusters</button>
            </div>
        </div>
    </div>
</div>

<div class="row justify-content-center mt-3">
    <div class="col-md-6 d-flex justify-content-center align-items-center">
        <form method="get" action="" class="d-flex align-items-center mr-3">
            <label for="limit" class="mr-2 font-weight-bold">Show records:</label>
            <select class="form-control mr-3" id="limit" name="limit" onchange="updateTableWithLimit()">
                <option></option>
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="50">50</option>
                <option value="100">100</option>
            </select>
            <input type="hidden" id="selected-barangay" name="barangay">
            <input type="hidden" name="page" value="1">
        </form>
        <button type="button" class="btn btn-primary" id="print-button" onclick="printCurrentTable()">Print Table</button>
    </div>
</div>

                <!-- Resident Table Markup -->
<div class="row">
    <div class="col-md-10 offset-md-1">
        <div class="resident-table-container">
            <div class="table-responsive w-auto text-nowrap resident-table-div" id="residents_table">
                <table class="table table-borderless table-hover resident-table">
                </table>

                

        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
        <script src="https://code.jquery.com/jquery-3.5.1.js" integrity="sha256-QWo7LDvxbWT2tbbQ97B53yJnYU3WhH/C8ycbRAkjPDc=" crossorigin="anonymous"></script>
        <script type="text/javascript" src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"> </script>
    


        <script>

function printCurrentTable() {
    // Clone the table content
    const tableContainer = document.querySelector('.resident-table-container').cloneNode(true);

    // Remove the pagination controls from the cloned content
    const pagination = tableContainer.querySelector('.pagination');
    if (pagination) {
        pagination.remove();
    }

    // Get the selected Barangay
    const selectedBarangay = document.getElementById('selected-barangay').value;

    // Prepare the print content
    const printWindow = window.open('', '', 'height=700,width=900');
    const printContent = `
    <html>
    <head>
        <title>.</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                margin: 0;
                padding: 20px;
            }
            header {
                text-align: center;
                margin-bottom: 20px;
                font-family: 'Georgia', serif;
                position: relative;
            }
            header h2 {
                font-size: 26px;
                font-weight: bold;
                color: #2c3e50;
                margin-bottom: 5px;
                text-transform: uppercase;
            }
            header p {
                font-size: 16px;
                color: #34495e;
                margin: 3px 0;
            }
            header img {
                position: absolute;
                top: 0;
                right: 0;
                width: 100px;
                height: 90px;
            }
            hr {
                border: 1px solid black;
                margin: 20px 0;
            }
            .sub-title, .members-title, .barangay-title {
                text-align: center;
                font-family: 'Georgia', serif;
                font-size: 18px;
                font-weight: normal;
                color: #2c3e50;
                margin-top: 10px;
                margin-bottom: 20px;
            }
            table {
                width: 100%;
                border-collapse: collapse;
                margin-top: 20px;
                font-family: 'Arial', sans-serif;
                font-size: 14px;
                border: 2px solid black;
            }
            th {
                background-color: #0044cc;
                color: white;
                font-size: 16px;
                text-align: center;
                padding: 10px;
                text-transform: capitalize;
                border: 2px solid black;
            }
            td {
                text-align: left;
                padding: 8px;
                font-size: 14px;
                border: 1px solid black;
            }
            td:nth-child(even) {
                background-color: #f9f9f9;
            }
            td:nth-child(odd) {
                background-color: #ffffff;
            }
            .bg-custom-image {
                display: none; /* Hide the footer */
            }
        </style>
    </head>
    <body>
        <header>
            <h2 class="title">ILAGAN ASSOCIATION OF WOMEN</h2>
            <p>Republic of the Philippines</p>
            <p>Province of Isabela</p>
            <p>City of Ilagan</p>
            <img src="img/logos.png" alt="Logo">
        </header>
        <hr>
        <div class="members-title">Members</div>
        ${selectedBarangay ? `<div class="barangay-title">Barangay: ${selectedBarangay}</div>` : ''}
        ${tableContainer.innerHTML}
    </body>
    </html>
    `;

    // Write the content to the print window
    printWindow.document.write(printContent);
    printWindow.document.close();

    // Delay the print dialog to ensure the content is rendered
    setTimeout(() => printWindow.print(), 500);
}


// Removed the dynamic button creation to avoid duplication

// Attach the print functionality to the existing button
document.addEventListener('DOMContentLoaded', function () {
    const printButton = document.querySelector('.btn-primary'); // Use the existing button
    if (printButton) {
        printButton.addEventListener('click', printCurrentTable);
    }
});


    
function changePage(page) {
    const barangay = document.getElementById('selected-barangay').value;
    const limit = document.getElementById('limit').value || 10;

    $.ajax({
        method: 'POST',
        url: 'admin_Actions.php',
        data: { barangay: barangay, limit: limit, page: page },
        success: function(response) {
            $('#residents_table').html(response);
        }
    });
}

function updateTableWithLimit() {
    const barangay = document.getElementById('selected-barangay').value;
    const limit = document.getElementById('limit').value;

    // Ensure a barangay is selected before updating the table
    if (barangay) {
        $.ajax({
            method: 'POST',
            url: 'admin_Actions.php',
            data: { barangay: barangay, limit: limit, page: 1 },
            success: function(response) {
                $('#residents_table').html(response);
            }
        });
    } else {
        alert('Please select a barangay first!');
    }
}


// List of barangays for each cluster
const barangays = {
    'Eastern': ['Alinguigan 1st', 'Alinguigan 2nd', 'Alinguigan 3rd', 'Ballacong', 'Bangag', 'Batong Labang', 'Cadu', 'Capellan', 'Capo', 'Fuyo', 'Manaring', 'Marana 1st', 'Marana 2nd', 'Marana 3rd', 'Minabang', 'Morado', 'Nanaguan', 'Pasa',  'Quimalabasa', 'Rang-Ayan', 'Rugao', 'San Andres', 'San Isidro', 'San Juan', 'San Lorenzo', 'San Pablo', 'San Rodrigo', 'Sipay', 'Sta. Catalina', 'Sta. Victoria', 'Tangcul', 'Villa Imelda'],
    'San Antonio': ['Aggasian', 'Cab. 2', 'Cab. 3', 'Cab. 4', 'Cab. 5', 'Cab. 6-24', 'Cab. 7', 'Cab. 8', 'Cab. 9-11', 'Cab. 10', 'Cab. 14-16', 'Cab. 17-21', 'Cab. 19', 'Cab. 22', 'Cab. 23', 'Cab. 25', 'Cab. 27', 'Centro San Antonio', 'Gayong2 Norte', 'Gayong2 Sur', 'Namnama', 'Paliueg','Salindingan', 'Sindon Bayabo', 'Sindon Maride'],
    'Western': ['Arusip', 'Bagong Silang', 'Bigao', 'Cabannungan 1st', 'Cabannungan 2nd', 'Carikkikan Norte', 'Carikkikan Sur', 'Lullutan', 'Malasin', 'Mangcuram', 'Naguilian Norte', 'Naguilian Sur', 'Pilar', 'San Ignacio', , 'Siffu', 'Sta. Isabel Norte', 'Sta. Isabel Sur'],
    'Poblacion': ['Alibagu', 'Baculud', 'Bagumbayan', 'Baligatan', 'Bliss', 'Calamagui 1st', 'Calamagui 2nd', 'Camunatan', 'Centro Poblacion', 'Fugu', 'Guinatan', 'Malalam', 'Osmeña', 'San Felipe', 'San Vicente', 'Sta. Barbara', 'Sto. Tomas']
};

// Function to load barangays based on selected cluster
function loadBarangays(clusterName, clusterButton) {
    const barangayList = document.getElementById('barangay-list');
    const clusterNameElement = document.getElementById('cluster-name');
    const clusterTitle = document.getElementById('cluster-title');
    const barangaySelection = document.getElementById('barangay-selection');
    const clusterSelection = document.getElementById('cluster-selection');

    // Hide previous cluster selection
    clusterSelection.style.display = 'none';
    barangaySelection.style.display = 'block'; // Show barangay selection

    // Reset button colors for all cluster buttons
    document.querySelectorAll('.btn-outline-primary').forEach(button => {
        button.classList.remove('btn-primary');
        button.classList.add('btn-outline-primary');
    });

    // Highlight the selected button
    clusterButton.classList.remove('btn-outline-primary');
    clusterButton.classList.add('btn-primary');

    // Update the cluster title
    clusterNameElement.textContent = clusterName;
    clusterTitle.classList.remove('d-none');
    barangayList.classList.remove('d-none');

    // Clear previous barangays
    barangayList.innerHTML = '';

    // Create buttons for each barangay in the selected cluster
    barangays[clusterName].forEach(function(barangay) {
        const colDiv = document.createElement('div');
        colDiv.className = 'col-md-2 mb-2'; // 4 columns layout

        const barangayButton = document.createElement('button');
        barangayButton.type = 'button';
        barangayButton.className = 'btn btn-outline-secondary btn-block';
        barangayButton.textContent = barangay;

        // Change button color and hide others when clicked
        barangayButton.onclick = function() {
            // Hide all barangay buttons except the clicked one
            const buttons = document.querySelectorAll('#barangay-list .btn');
            buttons.forEach(btn => {
                if (btn !== barangayButton) {
                    btn.style.display = 'none'; // Hide unselected barangays
                } else {
                    btn.classList.remove('btn-outline-secondary');
                    btn.classList.add('btn-secondary'); // Highlight the selected barangay
                }
            });

            // Filter table by selected barangay
            filterTableByBarangay(barangay);
        };

        colDiv.appendChild(barangayButton);
        barangayList.appendChild(colDiv);
    });
}

// Function to show the cluster selection screen
function showClusters() {
    const barangaySelection = document.getElementById('barangay-selection');
    const clusterSelection = document.getElementById('cluster-selection');

    // Show the cluster selection and hide the barangay selection
    clusterSelection.style.display = 'block';
    barangaySelection.style.display = 'none';
}

function filterTableByBarangay(barangay) {
    document.getElementById('selected-barangay').value = barangay;

    $.ajax({
        method: 'POST',
        url: 'admin_Actions.php',
        data: { barangay: barangay, limit: $('#limit').val() || 10, page: 1 },
        success: function(response) {
            const tableContainer = document.getElementById('residents_table');
            tableContainer.innerHTML = response;

            // Check if the table contains rows
            const table = tableContainer.querySelector('table');
            const printButton = document.getElementById('print-button');

            if (table && table.querySelector('tbody tr')) {
                // Show the print button if the table has rows
                printButton.style.display = 'block';
            } else {
                // Hide the print button if no table or no rows
                printButton.style.display = 'none';
            }
        }
    });
}

// Hide the print button initially
document.addEventListener('DOMContentLoaded', function () {
    const printButton = document.getElementById('print-button');
    if (printButton) {
        printButton.style.display = 'none'; // Hide the button initially
    }
});


</script>
    </body>
    
</html>