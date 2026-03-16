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

.pagination {
    display: flex;
    justify-content: center;
    list-style: none;
    padding: 0;
    margin: 0;
}

.page-item {
    margin: 0 5px;
}

.page-item .page-link {
    text-decoration: none;
    padding: 8px 16px;
    border: 1px solid #007bff;
    border-radius: 4px;
    color: #007bff;
    transition: background-color 0.3s, color 0.3s;
}

.page-item.active .page-link {
    background-color: #007bff;
    color: #fff;
}

.page-item .page-link:hover {
    background-color: #0056b3;
    color: #fff;
}

    .bg-custom-image .text-muted {
    background: linear-gradient(45deg, #007bff, #000);
    font-weight: bold;
    color: white !important; /* Override the 'text-muted' class */
}

</style>

<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>


<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<meta name="description" content="">
<meta name="author" content="">

<!-- Montseratt Font -->
<link rel="preconnect" href="https://fonts.gstatic.com">
<link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,400;0,500;0,800;1,500&display=swap" rel="stylesheet">

<!-- Local CSS -->
<link rel="stylesheet" href="stylesheets/user_Home.css">

<!-- Bootstrap -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css">
<title>Home</title>
</head>

<body> 

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

<?php
// Set the current page number
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$page = max(1, $page); // Ensure the page number is at least 1
// Set the number of records per page based on the dropdown
$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 20; // Default to 20 records per page
$offset = ($page - 1) * $limit;

$qry = "SELECT * FROM transaction LIMIT :limit OFFSET :offset";
$stmt = $userObj->runQuery($qry);
$stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
$stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();

$totalQry = "SELECT COUNT(*) FROM transaction";
$totalStmt = $userObj->runQuery($totalQry);
$totalStmt->execute();
$totalRows = $totalStmt->fetchColumn();
$totalPages = ceil($totalRows / $limit);

?>

<!-- Dropdown to Select Results Per Page -->
<div class="row justify-content-center mt-3">
    <div class="col-md-6 d-flex justify-content-center align-items-center">
        <form method="get" action="" class="d-flex align-items-center mr-3">
            <label for="limit" class="mr-2 font-weight-bold">Show records:</label>
            <select class="form-control" id="limit" name="limit" onchange="this.form.submit()">
                <option></option>
                <option value="1" <?php echo (isset($_GET['limit']) && $_GET['limit'] == 1) ? 'selected' : ''; ?>>1</option>
                <option value="2" <?php echo (isset($_GET['limit']) && $_GET['limit'] == 2) ? 'selected' : ''; ?>>2</option>
                <option value="50" <?php echo (isset($_GET['limit']) && $_GET['limit'] == 50) ? 'selected' : ''; ?>>50</option>
                <option value="100" <?php echo (isset($_GET['limit']) && $_GET['limit'] == 100) ? 'selected' : ''; ?>>100</option>
            </select>
            <input type="hidden" name="page" value="1"> <!-- Reset to first page when limit changes -->
        </form>
        <button id="print-button" class="btn btn-primary ml-3">Print</button>
    </div>
</div>


<div class="row">
    <div class="col-md-10 offset-md-1">
        <div class="resident-table-container">
            <h2 class="text-center my-4">Transaction List</h2> <!-- Add your title here -->
            <div class="table-responsive w-auto text-nowrap resident-table-div" id="residents_table">
                <table class="table table-borderless table-hover resident-table">
                    <thead>
                        <tr>
                            <th class="resident-table-heading">ID</th>
                            <th class="resident-table-heading">Resident Id</th>
                            <th class="resident-table-heading">Name</th>
                            <th class="resident-table-heading">Description</th>
                            <th class="resident-table-heading">Submission Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($stmt->rowCount() > 0) {
                            while ($rowUser = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        ?>
                                <tr>
                                    <td><?php print($rowUser['id']) ?></td>
                                    <td><?php print($rowUser['resident_id']) ?></td>
                                    <td><?php print($rowUser['first_name'] . ' ' . $rowUser['middle_name'] . ' ' . $rowUser['last_name']); ?></td>
                                    <td>
                                        <?php
                                        echo $rowUser['description'] ? $rowUser['description'] : 'Paid membership fee through GCash';
                                        ?>
                                    </td>
                                    <td><?php echo date("m/d/Y h:i A", strtotime($rowUser['submission_date'])); ?></td>

                                </tr>
                        <?php
                            }
                        }
                        ?>
                    </tbody>
                </table>

                <!-- Pagination Controls -->
<div class="pagination mt-3">
    <ul class="pagination">
        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <li class="page-item <?php echo ($i == $page) ? 'active' : ''; ?>">
                <a href="?page=<?php echo $i; ?>&limit=<?php echo $limit; ?>" class="page-link"><?php echo $i; ?></a>
            </li>
        <?php endfor; ?>
    </ul>
</div>
        </div>

<!--footer-->
<div class="row m-0 p-0 bg-custom-image">
    <div class="col-12 text-center text-muted">
        <p class="m-0 pb-3 mt-md-2 mt-sm-2">Ilagan Association for Women, Copyright ©️ 2024</p>
    </div>
    </div>

   <script>
   document.getElementById('print-button').addEventListener('click', function () {
    // Clone the entire content
    const tableContainer = document.querySelector('.resident-table-container').cloneNode(true);

    // Remove the pagination controls from the cloned content
    const pagination = tableContainer.querySelector('.pagination');
    if (pagination) {
        pagination.remove();
    }

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
            .sub-title {
                text-align: center;
                font-family: 'Georgia', serif;
                font-size: 20px;
                font-weight: bold;
                color: #2c3e50;
                margin: 15px 0;
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
        ${tableContainer.innerHTML}
    </body>
    </html>
    `;

    // Write the content to the print window
    printWindow.document.write(printContent);
    printWindow.document.close();

    // Delay the print dialog to ensure the content is rendered
    setTimeout(() => printWindow.print(), 500);
});

</script>


    </body>
</html>
