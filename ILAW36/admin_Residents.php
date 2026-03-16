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
// Default results per page if not set in URL
$results_per_page = isset($_GET['results_per_page']) ? (int)$_GET['results_per_page'] : 10;

// Get the search query if available
$search_query = isset($_GET['search']) ? $_GET['search'] : '';

// Find out the number of results in the database based on the search query
if (!empty($search_query)) {
    $query = "SELECT COUNT(*) FROM resident_info WHERE first_name LIKE :search OR last_name LIKE :search";
    $stmt = $userObj->runQuery($query);
    $stmt->bindValue(':search', '%' . $search_query . '%');
} else {
    $query = "SELECT COUNT(*) FROM resident_info";
    $stmt = $userObj->runQuery($query);
}
$stmt->execute();
$total_results = $stmt->fetchColumn(); // Get the total number of rows

// Determine the total number of pages available
$total_pages = ceil($total_results / $results_per_page);

// Determine the current page number from the URL
$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

// Calculate the starting limit for the rows to fetch
$starting_limit = ($current_page - 1) * $results_per_page;

// Fetch the rows based on search query and pagination
if (!empty($search_query)) {
    $qry = "SELECT * FROM resident_info WHERE first_name LIKE :search OR last_name LIKE :search ORDER BY resident_id ASC LIMIT $starting_limit, $results_per_page";
    $stmt = $userObj->runQuery($qry);
    $stmt->bindValue(':search', '%' . $search_query . '%');
} else {
    $qry = "SELECT * FROM resident_info ORDER BY resident_id ASC LIMIT $starting_limit, $results_per_page";
    $stmt = $userObj->runQuery($qry);
}
$stmt->execute();
?>

<!-- Search Bar -->
<div class="row justify-content-around pb-4 mb-2 search-add-container">
    <div class="col-md-4 mt-2 mb-2">
        <form method="get" action="">
            <div class="input-group">
                <input type="text" class="form-control" placeholder="Search Members" id="search_field" name="search" value="<?php echo htmlspecialchars($search_query); ?>">
                <div class="input-group-append">
                    <button class="btn btn-danger search-btn" type="submit">
                        <i class="fa fa-search"></i>
                    </button>
                </div>
                <!-- Preserve pagination and dropdown state -->
                <input type="hidden" name="results_per_page" value="<?php echo $results_per_page; ?>">
                <input type="hidden" name="page" value="1">
            </div>
        </form>
    </div>
</div>

<!-- Dropdown to Select Results Per Page -->
<div class="row justify-content-center mt-3">
    <div class="col-md-3">
        <form method="get" action="">
            <label for="results_per_page">Show records:</label>
            <select class="form-control" id="results_per_page" name="results_per_page" onchange="this.form.submit()">
            <option></option>
                <option value="1" <?php echo $results_per_page == 1 ? 'selected' : ''; ?>>1</option>
                <option value="2" <?php echo $results_per_page == 2 ? 'selected' : ''; ?>>2</option>
                <option value="50" <?php echo $results_per_page == 50 ? 'selected' : ''; ?>>50</option>
                <option value="100" <?php echo $results_per_page == 100 ? 'selected' : ''; ?>>100</option>
            </select>
            <input type="hidden" name="search" value="<?php echo htmlspecialchars($search_query); ?>">
            <input type="hidden" name="page" value="1">
        </form>
    </div>
</div>

<!-- Pagination Controls -->
<nav aria-label="Page navigation" class="mt-3">
    <ul class="pagination justify-content-center">
        <?php if ($current_page > 1): ?>
            <li class="page-item">
                <a class="page-link" href="?page=<?php echo $current_page - 1; ?>&results_per_page=<?php echo $results_per_page; ?>&search=<?php echo urlencode($search_query); ?>" aria-label="Previous">
                    <span aria-hidden="true">&laquo;</span>
                </a>
            </li>
        <?php endif; ?>

        <?php for ($page = 1; $page <= $total_pages; $page++): ?>
            <li class="page-item <?php echo ($page == $current_page) ? 'active' : ''; ?>">
                <a class="page-link" href="?page=<?php echo $page; ?>&results_per_page=<?php echo $results_per_page; ?>&search=<?php echo urlencode($search_query); ?>"><?php echo $page; ?></a>
            </li>
        <?php endfor; ?>

        <?php if ($current_page < $total_pages): ?>
            <li class="page-item">
                <a class="page-link" href="?page=<?php echo $current_page + 1; ?>&results_per_page=<?php echo $results_per_page; ?>&search=<?php echo urlencode($search_query); ?>" aria-label="Next">
                    <span aria-hidden="true">&raquo;</span>
                </a>
            </li>
        <?php endif; ?>
    </ul>
</nav>


                <div>
                    <!-- // PHP Code for Notification Alert -->
                    <?php
                        if(isset($_GET['residentUpdated']))
                        {
                            echo 
                            '<div style="text-align:center" class = "alert alert-success alert-dismissible fade show" role="alert">
                                Member Edited Successfully
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>                               
                            </div>';
                        };

                        if(isset($_GET['residentDeleted']))
                        {
                            echo 
                            '<div style="text-align:center" class = "alert alert-success alert-dismissible fade show" role="alert">
                                Member Deleted Successfully
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>                               
                            </div>';
                        };
                    ?>
                </div>

               <!-- Resident Table Markup -->
<div class="row">
    <div class="col-md-10 offset-md-1">
        <div class="resident-table-container">
            <div class="table-responsive w-auto text-nowrap resident-table-div" id="residents_table">
                <table class="table table-borderless table-hover resident-table">
                    <thead>
                        <th class="resident-table-heading"></th>
                        <th class="resident-table-heading"></th>
                        <th class="resident-table-heading"></th>
                        <th class="resident-table-heading">ID Number</th>
                        <th class="resident-table-heading">First Name</th>
                        <th class="resident-table-heading">Middle Name</th>
                        <th class="resident-table-heading">Last Name</th>
                        <th class="resident-table-heading">Purok</th>
                        <th class="resident-table-heading">Barangay</th>
                        <th class="resident-table-heading">Birthdate</th>
                        <th class="resident-table-heading">Birthplace</th>
                        <th class="resident-table-heading">Residency Data</th>
                        <th class="resident-table-heading">Civil Status</th>
                        <th class="resident-table-heading">Religion</th>
                        <th class="resident-table-heading">Employment</th>
                        <th class="resident-table-heading">Spouse First Name</th>
                        <th class="resident-table-heading">Spouse Last Name</th>
                        <th class="resident-table-heading">Father's First Name</th>
                        <th class="resident-table-heading">Father's Last Name</th>
                        <th class="resident-table-heading">Mother's First Name</th>
                        <th class="resident-table-heading">Mother's Last Name</th>
                        <th class="resident-table-heading">Number of Children</th>
                        <th class="resident-table-heading">Status</th>
                        <th class="resident-table-heading">Username</th>
                        <th class="resident-table-heading">Password</th>
                    </thead>

                    <tbody>
                        <?php if($stmt->rowCount() > 0) { 
                            while($rowUser = $stmt->fetch(PDO::FETCH_ASSOC)) { ?>
                                <tr>
                                    <td>
                                        <a class="table-btn-anchor" href="#delete-resident-modal" data-toggle="modal" data-resident_id="<?php print($rowUser['resident_id']) ?>" >
                                            <button type="button" class="btn btn-danger table-btn">
                                                <span> <i class="fas fa-trash-alt"></i></span>
                                            </button>
                                        </a>
                                    </td> 

                                    <td>
                                        <a class="table-btn-anchor" href="#edit-resident-modal" data-toggle="modal" data-resident_id="<?php print($rowUser['resident_id']) ?>" >
                                            <button type="button" class="btn btn-danger table-btn">
                                                <span> <i class="fas fa-edit"></i></span>
                                            </button>
                                        </a>
                            </td>
                            <td>
                                         <a class="table-btn-anchor" href="#print-resident-modal" data-toggle="modal" data-resident_id="<?php print($rowUser['resident_id']) ?>">
                                            <button type="button" class="btn btn-danger table-btn">
                                                <span> <i class="fas fa-print"></i></span>
                                            </button>
                                        </a>
                                    </td>

                                    <td><?php print($rowUser['resident_id']) ?></td>
                                    <td><?php print($rowUser['first_name']) ?></td>
                                    <td><?php print($rowUser['middle_name']) ?></td>
                                    <td><?php print($rowUser['last_name']) ?></td>
                                    <td><?php print($rowUser['purok']) ?></td>
                                    <td><?php print($rowUser['barangay']) ?></td>
                                    <td><?php print($rowUser['birthday']) ?></td>
                                    <td><?php print($rowUser['suffix']) ?></td>
                                    <td><?php print($rowUser['sex']) ?></td>
                                    <td><?php print($rowUser['civil_stat']) ?></td>
                                    <td><?php print($rowUser['alias']) ?></td>
                                    <td><?php print($rowUser['voter_stat']) ?></td>
                                    <td><?php print($rowUser['mobile_no']) ?></td>
                                    <td><?php print($rowUser['email']) ?></td>
                                    <td><?php print($rowUser['religion']) ?></td>
                                    <td><?php print($rowUser['fathers_last_name']) ?></td>
                                    <td><?php print($rowUser['mothers_first_name']) ?></td>
                                    <td><?php print($rowUser['mothers_last_name']) ?></td>
                                    <td><?php print($rowUser['num_children']) ?></td>
                                    <td><?php print($rowUser['stat']) ?></td>
                                    <td><?php print($rowUser['username']) ?></td>
                                    <td><?php print($rowUser['password']) ?></td>
                                </tr>
                            <?php }
                        } ?>
                    </tbody>
                </table>

            </div>
        </div>
    </div>
</div>

                <!-- Footer Markup -->
                <div class="row m-0 p-0 bg-custom-image">
    <div class="col-12 text-center text-muted">
        <p class="m-0 pb-3 mt-md-2 mt-sm-2">Ilagan Association for Women, Copyright ©️ 2024</p>
    </div>
    </div>

            <!-- Markup for Edit Resident Modal -->
            <div class="modal fade" id="edit-resident-modal" tabindex="-1" role="dialog" aria-labelledby="edit-resident-modal" aria-hidden="true">               
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title edit-resident-heading" id="edit-resident-heading">Edit Member</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>

                        <div class="modal-body">
                            <form action="admin_Actions.php" method="post">
                                <div class="form-row justify-content-center text-center">
                                    <div class="col-md-6 form-group">
                                        <label for="edit_resident_id_field" class="add-resident-form-label" >Member ID</label>
                                        <input readonly style="text-align: center;" type="text" class="form-control add-resident-form-field" placeholder="ID" id="edit_resident_id_field" name="edit_resident_id_field" required>
                                    </div>
                                    
                                </div>

                                <div class="form-row">

                                    <div class="col-md-4 form-group ">
                                        <label for="edit_first_name_field" class="add-resident-form-label" >First Name</label>
                                        <input type="text" class="form-control add-resident-form-field" placeholder="Given Name" id="edit_first_name_field" name="edit_first_name_field" style="text-transform: capitalize" required onblur="capitalizeFirstLetter(this)">
                                    </div>

                                    <div class="col-md-4 form-group">
                                        <label for="edit_middle_name_field" class="add-resident-form-label" >Middle Name</label>
                                        <input type="text" class="form-control add-resident-form-field" placeholder="Given Name" id="edit_middle_name_field" name="edit_middle_name_field" style="text-transform: capitalize" required onblur="capitalizeFirstLetter(this)">
                                    </div>

                                    <div class="col-md-4 form-group">
                                        <label for="edit_last_name_field" class="add-resident-form-label" >Last Name</label>
                                        <input type="text" class="form-control add-resident-form-field" placeholder="Given Name" id="edit_last_name_field" name="edit_last_name_field" style="text-transform: capitalize" required onblur="capitalizeFirstLetter(this)">
                                    </div>
                                       
                                </div>

                                <div class="form-row">
                                <div class="col-md-6 form-group">
                                    <label for="edit_purok_field" class="add-resident-form-label">Purok</label>
                                    <input type="number" class="form-control add-resident-form-field" placeholder="Purok" id="edit_purok_field" name="edit_purok_field" min="1"  required>
                                </div>

                                <div class="col-md-6 form-group">
                                    <label for="edit_barangay_field" class="add-resident-form-label">Barangay</label>
                                    <input class="form-control add-resident-form-field" list="barangay_list" placeholder="Start typing barangay name" id="edit_barangay_field" name="edit_barangay_field" required>
                                    <datalist id="barangay_list">
                                    <option value="Aggasian"></option>
                                    <option value="Alibagu"></option>
                                    <option value="Alinguigan 1st"></option>
                                    <option value="Alinguigan 2nd"></option>
                                    <option value="Alinguigan 3rd"></option>
                                    <option value="Arusip"></option>
                                    <option value="Baculud"></option>
                                    <option value="Bagong Silang"></option>
                                    <option value="Bagumbayan"></option>
                                    <option value="Ballacong"></option>
                                    <option value="Baligatan"></option>
                                    <option value="Bangag"></option>
                                    <option value="Batong Labang"></option>
                                    <option value="Bigao"></option>
                                    <option value="Bliss"></option>
                                    <option value="Cabannungan 1st"></option>
                                    <option value="Cabannungan 2nd"></option>
                                    <option value="Cabisera 1"></option>
                                    <option value="Cabisera 2"></option>
                                    <option value="Cabisera 3"></option>
                                    <option value="Cabisera 4"></option>
                                    <option value="Cabisera 5"></option>
                                    <option value="Cabisera 6-24"></option>
                                    <option value="Cabisera 7"></option>
                                    <option value="Cabisera 8"></option>
                                    <option value="Cabisera 9-11"></option>
                                    <option value="Cabisera 10"></option>
                                    <option value="Cabisera 14-16"></option>
                                    <option value="Cabisera 17-21"></option>
                                    <option value="Cabisera 19"></option>
                                    <option value="Cabisera 22"></option>
                                    <option value="Cabisera 23"></option>
                                    <option value="Cabisera 25"></option>
                                    <option value="Cabisera 27"></option>
                                    <option value="Cadu"></option>
                                    <option value="Calamagui 1st"></option>
                                    <option value="Calamagui 2nd"></option>
                                    <option value="Camunatan"></option>
                                    <option value="Capellan"></option>
                                    <option value="Capo"></option>
                                    <option value="Carikkikan Norte"></option>
                                    <option value="Carikkikan Sur"></option>
                                    <option value="Centro Poblacion"></option>
                                    <option value="Centro San Antonio"></option>
                                    <option value="Fugu"></option>
                                    <option value="Fuyo"></option>
                                    <option value="Gayong Gayong Norte"></option>
                                    <option value="Gayong Gayong Sur"></option>
                                    <option value="Guinatan"></option>
                                    <option value="Lullutan"></option>
                                    <option value="Malalam"></option>
                                    <option value="Malasin"></option>
                                    <option value="Manaring"></option>
                                    <option value="Mangcuram"></option>
                                    <option value="Marana 1st"></option>
                                    <option value="Marana 2nd"></option>
                                    <option value="Marana 3rd"></option>
                                    <option value="Minabang"></option>
                                    <option value="Morado"></option>
                                    <option value="Naguilian Norte"></option>
                                    <option value="Naguilian Sur"></option>
                                    <option value="Namnama"></option>
                                    <option value="Nanaguan"></option>
                                    <option value="Osmena"></option>
                                    <option value="Paliueg"></option>
                                    <option value="Pasa"></option>
                                    <option value="Pilar"></option>
                                    <option value="Quimalabasa"></option>
                                    <option value="Rang-Ayan"></option>
                                    <option value="Rugao"></option>
                                    <option value="Salindingan"></option>
                                    <option value="San Andres"></option>
                                    <option value="San Felipe"></option>
                                    <option value="San Ignacio"></option>
                                    <option value="San Isidro"></option>
                                    <option value="San Juan"></option>
                                    <option value="San Lorenzo"></option>
                                    <option value="San Pablo"></option>
                                    <option value="San Rodrigo"></option>
                                    <option value="San Vicente"></option>
                                    <option value="Santa Barbara"></option>
                                    <option value="Santa Catalina"></option>
                                    <option value="Santa Isabel Norte"></option>
                                    <option value="Santa Isabel Sur"></option>
                                    <option value="Santa Victoria"></option>
                                    <option value="Santo Tomas"></option> <!-- Corrected position -->
                                    <option value="Siffu"></option>
                                    <option value="Sindon Bayabo"></option>
                                    <option value="Sindon Maride"></option>
                                    <option value="Sipay"></option>
                                    <option value="Tangcul"></option>
                                    <option value="Villa Imelda"></option>
                                        </datalist>
                                </div>
                                </div>

                                <div class="form-row">
                                    <div class="col-md-4 form-group">
                                        <label for="edit_birthday_field" class="add-resident-form-label" >Birthdate</label>
                                        <input type="date" class="form-control add-resident-form-field" id="edit_birthday_field" name="edit_birthday_field" required>
                                        
                                    </div>

                                    <div class="col-md-4 form-group">
                                        <label for="edit_suffix_field" class="add-resident-form-label" >Birthplace</label>
                                        <input type="text" class="form-control add-resident-form-field" placeholder="(Birthplace)" id="edit_suffix_field" name="edit_suffix_field" style="text-transform: capitalize" required onblur="capitalizeFirstLetter(this)">
                                    </div>

                                    <div class="col-md-4 form-group">
                                        <label for="edit_sex_field" class="add-resident-form-label" >Residency Data</label>
                                        <select class="form-control add-resident-form-field" id="edit_sex_field" placeholder="Sex" name="edit_sex_field" required>
                                            <option></option>
                                            <option>Since Birth</option>
                                            <option>Recently Moved</option>
                                            <option>OFW</option>
                                        </select>
                                    </div>                                   
                                </div>

                                <div class="form-row">
                                    <div class="col-md-4 form-group">
                                        <label for="edit_civil_stat_field" class="add-resident-form-label" >Civil Status</label>
                                        <select class="form-control add-resident-form-field" id="edit_civil_stat_field"  placeholder="Civil Status" name="edit_civil_stat_field" value="<?php print($rowUser['civil_stat']) ?>" required>
                                            <option></option>
                                            <option>Single</option> 
                                           <option>Married</option>
                                           <option>Widowed</option>
                                           <option>Seperated</option>
                                           <option>Annuled</option>
                                        </select>
                                    </div>

                                    <div class="col-md-4 form-group">
                                        <label for="edit_alias_field" class="add-resident-form-label" >Religion</label>
                                        <input type="text" class="form-control add-resident-form-field" placeholder="Religion" id="edit_alias_field" name="edit_alias_field" style="text-transform: capitalize" required onblur="capitalizeFirstLetter(this)">
                                    </div>

                                    <div class="col-md-4 form-group">
                                        <label for="edit_voter_stat_field" class="add-resident-form-label" >Employment</label>
                                        <select class="form-control add-resident-form-field" id="edit_voter_stat_field" placeholder="Voter Status" name="edit_voter_stat_field" required>                                           
                                            <option></option>
                                            <option>Government</option>
                                            <option>Private</option>
                                            <option>Self-employed</option>  
                                        </select>
                                                </div>
                                    
                                </div>

                                <div class="form-row">

                                    <div class="col-md-6 form-group">
                                        <label for="edit_mobile_no_field" class="add-resident-form-label" >Spouse First Name</label>
                                        <input type="text" class="form-control add-resident-form-field" id="edit_mobile_no_field" placeholder="Spouse First Name" name="edit_mobile_no_field" style="text-transform: capitalize" required onblur="capitalizeFirstLetter(this)">
                                    </div>

                                    <div class="col-md-6 form-group">
                                        <label for="edit_email_field" class="add-resident-form-label" >Spouse Last Name</label>
                                        <input type="text" class="form-control add-resident-form-field" id="edit_email_field" placeholder="Spouse Last Name" name="edit_email_field" style="text-transform: capitalize" required onblur="capitalizeFirstLetter(this)">
                                    </div>

                                </div>

                                <div class="form-row">
                                    <div class="col-md-6 form-group">
                                        <label for="edit_religion_field" class="add-resident-form-label" >Father's First Name</label>
                                        <input type="text" class="form-control add-resident-form-field" placeholder="Father's First Name" id="edit_religion_field" name="edit_religion_field" style="text-transform: capitalize" required onblur="capitalizeFirstLetter(this)">
                                    </div>

                                    <div class="col-md-6 form-group">
                                        <label for="edit_fathers_last_name_field" class="add-resident-form-label">Father's Last Name</label>
                                        <input type="text" class="form-control add-resident-form-field" placeholder="Father's Last Name" id="edit_fathers_last_name_field" name="edit_fathers_last_name_field" style="text-transform: capitalize" required onblur="capitalizeFirstLetter(this)">
                                     </div>
                                </div>

                                <div class="form-row">

                                <div class="col-md-4 form-group">
                                        <label for="edit_mothers_first_name_field" class="add-resident-form-label">Mother's First Name</label>
                                        <input type="text" class="form-control add-resident-form-field" placeholder="Mother's First Name" id="edit_mothers_first_name_field" name="edit_mothers_first_name_field" style="text-transform: capitalize" required onblur="capitalizeFirstLetter(this)">
                                    </div>

                                    <div class="col-md-4 form-group">
                                        <label for="edit_mothers_last_name_field" class="add-resident-form-label">Mother's Last Name</label>
                                        <input type="text" class="form-control add-resident-form-field" placeholder="Mother's Last Name" id="edit_mothers_last_name_field" name="edit_mothers_last_name_field" style="text-transform: capitalize" required onblur="capitalizeFirstLetter(this)">
                                    </div>

                                    <div class="col-md-4 form-group">
                                        <label for="edit_num_children_field" class="add-resident-form-label">Number of Children</label>
                                        <input type="number" class="form-control add-resident-form-field" placeholder="Number of Children" id="edit_num_children_field" name="edit_num_children_field"  min="0" required>
                                    </div>
                                </div>

                                <div class="form-row">                                    
                                <div class="col-md-12 form-group">
        <label for="edit_stat_field" class="add-resident-form-label">Stat</label> <!-- New Stat Field -->
        <select class="form-control add-resident-form-field" id="edit_stat_field" placeholder="Status" name="edit_stat_field">
                                            <option></option>
                                            <option>Member</option>                                        
                                        </select>
    </div>
</div>

                                <div class="form-row">
                                    <div class="col-md-6 form-group ">
                                        <label for="edit_username_field" class="add-resident-form-label" >Username</label>
                                        <input type="text" class="form-control add-resident-form-field" placeholder="Username" id="edit_username_field" name="edit_username_field" required>
                                    </div>

                                    <div class="col-md-6 form-group">
                                        <label for="edit_password_field" class="add-resident-form-label" >Password</label>
                                        <input type="text" class="form-control add-resident-form-field" placeholder="Password" id="edit_password_field" name="edit_password_field" required>
                                    </div>
                                </div>

                                <input type="hidden" id="edit_submission_date" name="edit_submission_date">

                                <div class="row justify-content-center">

                                    <div class="col text-right mt-2 mb-2">
                                        <button type="submit" class="btn btn-danger edit-resident-btn" id="btn_edit_resident" name="btn_edit_resident">UPDATE</button>                                   
                                    </div>
                                    <div class="col text-left mt-2 mb-2">
                                        <button type="button" class="btn btn-danger edit-resident-btn" data-dismiss="modal">CANCEL</button>                            
                                    </div>
                                </div>  
                            </form>
                        </div>     
                    </div>
                </div>
            </div>

            <!-- Markup for Delete Resident Modal -->
            <div class="modal fade" id="delete-resident-modal" tabindex="-1" role="dialog" aria-labelledby="delete-resident-modal" aria-hidden="true">
                <div class="modal-dialog modal-md" role="document">
                    <div class="modal-content">
                        <div class="modal-header pb-2 mb-3">
                            <h5 class="modal-title delete-resident-heading" id="delete-resident-heading">Delete Member</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>

                        <div class="modal-body">
                            <form action="admin_Actions.php" method="post">
                                <div class="form-row justify-content-center text-center pt-2 mt-5">
                                    <div class="col-md-6 form-group">
                                        <label for="delete_resident_id_field" class="delete-resident-form-label" >Member ID</label>
                                        <input readonly style="text-align: center;" type="text" class="form-control delete-resident-form-field" placeholder="ID" id="delete_resident_id_field" name="delete_resident_id_field" required>
                                    </div>
                                    
                                </div>

                                <div class="row">
                                    <div class="col-md-12 justify-content-center"> 
                                        <p class="delete-resident-name-field"></p>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12 justify-content-center"> 
                                        <p class="delete-resident-prompt">Are you sure you want to delete this member?</p>
                                    </div>
                                </div>                            
                        </div>

                        <div class="modal-footer">
                                 <div class="row justify-content-around">

                                    <div class="col text-right mt-2 mb-2">
                                        <button type="submit" class="btn btn-danger delete-resident-btn" id="btn_delete_resident" name="btn_delete_resident">DELETE</button>                                   
                                    </div>
                                    <div class="col text-left mt-2 mb-2">
                                        <button type="button" class="btn btn-danger delete-resident-btn" data-dismiss="modal">CANCEL</button>                            
                                    </div>
                                </div>                                
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>   

        <div class="modal fade" id="print-resident-modal" tabindex="-1" role="dialog" aria-labelledby="print-resident-modal" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Print Resident Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="print-content">
                <!-- Resident details for printing will be dynamically added here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="printResidentDetails()">Print</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>



        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
        <script src="https://code.jquery.com/jquery-3.5.1.js" integrity="sha256-QWo7LDvxbWT2tbbQ97B53yJnYU3WhH/C8ycbRAkjPDc=" crossorigin="anonymous"></script>
        <script type="text/javascript" src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"> </script>
    
        <script>

function load_resident_to_print_modal() {
    $('#print-resident-modal').on('show.bs.modal', function (e) {
        var resident_id = $(e.relatedTarget).data('resident_id');

        $.ajax({
            type: 'post',
            url: 'admin_Actions.php',
            data: { print_load_resident_info: resident_id },
            dataType: 'json',
            success: function (data) {
                var residentDetails = `
                    <div style="border: 2px solid #0044cc; padding: 20px; font-family: Arial, sans-serif; line-height: 1.5;">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                            <img src="img/Logo.png" alt="City Logo" style="height: 100px;">
                            <div>
                                <h3 style="margin: 0; color: #0044cc; font-size: 24px;">Name: ${data.first_name} ${data.middle_name} ${data.last_name}</h3>
                                <p style="margin: 0; font-size: 18px; font-weight: bold;">Member ID: ${data.resident_id}</p>
                            </div>
                        </div>
                        <table style="width: 100%; border-collapse: collapse;">
                            <tbody>
                                <tr><td>First Name</td><td>${data.first_name}</td></tr>
                                <tr><td>Middle Name</td><td>${data.middle_name}</td></tr>
                                <tr><td>Last Name</td><td>${data.last_name}</td></tr>
                                <tr><td>Purok</td><td>${data.purok}</td></tr>
                                <tr><td>Barangay</td><td>${data.barangay}</td></tr>
                                <tr><td>Birthdate</td><td>${data.birthday}</td></tr>
                                <tr><td>Birthplace</td><td>${data.suffix}</td></tr>
                                <tr><td>Residency Data</td><td>${data.sex}</td></tr>
                                <tr><td>Civil Status</td><td>${data.civil_stat}</td></tr>
                                <tr><td>Religion</td><td>${data.alias}</td></tr>
                                <tr><td>Employment</td><td>${data.voter_stat}</td></tr>
                                <tr><td>Spouse First Name</td><td>${data.mobile_no}</td></tr>
                                <tr><td>Spouse Last Name</td><td>${data.email}</td></tr>
                                <tr><td>Father's First Name</td><td>${data.religion}</td></tr>
                                <tr><td>Father's Last Name</td><td>${data.fathers_last_name}</td></tr>
                                <tr><td>Mother's First Name</td><td>${data.mothers_first_name}</td></tr>
                                <tr><td>Mother's Last Name</td><td>${data.mothers_last_name}</td></tr>
                                <tr><td>Number of Children</td><td>${data.num_children}</td></tr>
                                <tr><td>Status</td><td>${data.stat}</td></tr>
                            </tbody>
                        </table>
                    </div>
                `;
                document.getElementById('print-content').innerHTML = residentDetails;
            },
            error: function () {
                alert('Failed to load resident details');
            }
        });
    });
}

function printResidentDetails() {
    var printContent = document.getElementById('print-content').innerHTML;
    var printWindow = window.open('', '_blank', 'height=700,width=900');
    printWindow.document.write(`
        <html>
        <head>
            <title>.</title>
            <style>
                @media print {
                    body {
                        margin: 0;
                        padding: 0;
                        font-family: Arial, sans-serif;
                        line-height: 1.6;
                    }
                    @page {
                        size: A4;
                        margin: 3mm; 
                    }
                    header {
                        text-align: center;
                        margin-bottom:2px;
                        font-family: 'Georgia', serif;
                        position: relative;
                    }
                    header h2 {
                        font-size: 26px;
                        font-weight: bold;
                        color: #2c3e50;
                        margin-bottom: 2px;
                        text-transform: uppercase;
                    }
                    header p {
                        font-size: 16px;
                        color: #34495e;
                        margin: 2px;
                    }
                    header img {
                        position: absolute;
                        top: 0;
                        right: 0;
                        width: 120px;
                        height: 100px;
                    }
                    hr {
                        border: 1px solid black;
                        margin: 2px;
                    }
                    .title {
                        text-align: center;
                        font-family: 'Georgia', serif;
                        font-size: 18px;
                        font-weight: bold;
                        color: #2c3e50;
                        margin: 2px;
                    }
                    .print-container {
                        width: 100%;
                        display: flex;
                        justify-content: center;
                        align-items: center;
                        padding: 10px;
                        box-sizing: border-box;
                    }
                    .content-wrapper {
                        width: calc(100% - 2mm); /* Full width minus left/right margins */
                        display: block;
                        box-sizing: border-box;
                    }
                    table {
                        width: 100%;
                        border-collapse: collapse;
                        margin-top: 2px;
                        font-size: 11pt;
                    }
                    td {
                        border: 1px solid #000000;
                        padding: 10px;
                        text-align: left;
                    }
                    td:nth-child(1) {
                        font-weight: bold;
                        background-color: #f9f9f9;
                    }
                }
            </style>
        </head>
        <body>
            <header>
                <h2>ILAGAN ASSOCIATION OF WOMEN</h2>
                <p>Republic of the Philippines</p>
                <p>Province of Isabela</p>
                <p>City of Ilagan</p>
                <img src="img/logos.png" alt="Logo">
            </header>
            <hr>
            <h3 class="title">Personal Data</h3>
            <div class="print-container">
                <div class="content-wrapper">
                    ${printContent}
                </div>
            </div>
        </body>
        </html>
    `);
    printWindow.document.close();
    
    // Wait for the content to load before showing the print dialog
    setTimeout(() => {
        printWindow.print();
    }, 500); // 500ms delay
}

             // Ensure pagination resets when the search bar is cleared
    document.getElementById('search_field').addEventListener('input', function () {
        if (this.value.trim() === '') {
            const form = this.closest('form'); // Get the form element
            const hiddenInputs = form.querySelectorAll('input[type="hidden"]');
            
            // Reset paginator to default values when search is cleared
            hiddenInputs.forEach(input => {
                if (input.name === 'page') input.value = 1;
            });

            form.submit(); // Submit the form automatically when search is cleared
        }
    });

function capitalizeFirstLetter(input) {
    // Get the current value of the input
    let value = input.value;

    // Capitalize the first letter and make the rest lowercase
    if (value) {
        value = value.charAt(0).toUpperCase() + value.slice(1).toLowerCase();
        input.value = value;
    }
}
            
            //Pass Data from anchored edit resident button to edit resident modal form
            function load_resident_to_edit_modal()
            {
                $('#edit-resident-modal').on('show.bs.modal', function (e) {

                    var resident_id = $(e.relatedTarget).data('resident_id');
                        
                    $.ajax({
                        type : 'post',
                        url : 'admin_Actions.php',
                        data : {edit_load_resident_info:resident_id},
                        dataType: 'json',
                        success : function(data)
                        {

                            var len = data.length;

                            for(var i = 0; i<len; i++)
                                {
                                    var first_name = data[0]['first_name'];
                                    var middle_name = data[0]['middle_name'];
                                    var last_name = data[0]['last_name'];
                                    var purok = data[0]['purok'];
                                    var barangay = data[0]['barangay'];
                                    var birthday = data[0]['birthday'];
                                    var suffix = data[0]['suffix'];
                                    var sex = data[0]['sex'];
                                    var civil_stat = data[0]['civil_stat'];
                                    var alias = data[0]['alias'];
                                    var voter_stat = data[0]['voter_stat'];
                                    var mobile_no = data[0]['mobile_no'];
                                    var email = data[0]['email'];
                                    var religion = data[0]['religion'];
                                    var fathers_last_name = data[0]['fathers_last_name'];
                                    var mothers_first_name = data[0]['mothers_first_name'];
                                    var mothers_last_name = data[0]['mothers_last_name'];
                                    var num_children = data[0]['num_children'];
                                    var stat = data[0]['stat'];
                                    var username = data[0]['username'];
                                    var password = data[0]['password'];
                                    

                                    document.getElementById('edit_resident_id_field').value= resident_id;
                                    document.getElementById('edit_first_name_field').value= first_name;
                                    document.getElementById('edit_middle_name_field').value= middle_name;
                                    document.getElementById('edit_last_name_field').value= last_name;
                                    document.getElementById('edit_purok_field').value = purok; 
                                    document.getElementById('edit_barangay_field').value = barangay;
                                    document.getElementById('edit_birthday_field').value= birthday;
                                    document.getElementById('edit_suffix_field').value= suffix;
                                    document.getElementById('edit_sex_field').value= sex;
                                    document.getElementById('edit_civil_stat_field').value= civil_stat;
                                    document.getElementById('edit_alias_field').value= alias;
                                    document.getElementById('edit_voter_stat_field').value= voter_stat;
                                    document.getElementById('edit_mobile_no_field').value= mobile_no;
                                    document.getElementById('edit_email_field').value= email;
                                    document.getElementById('edit_religion_field').value= religion;
                                    document.getElementById('edit_fathers_last_name_field').value = fathers_last_name;
                                    document.getElementById('edit_mothers_first_name_field').value = mothers_first_name;
                                    document.getElementById('edit_mothers_last_name_field').value = mothers_last_name;
                                    document.getElementById('edit_num_children_field').value = num_children;
                                    document.getElementById('edit_stat_field').value = stat;
                                    document.getElementById('edit_username_field').value= username;
                                    document.getElementById('edit_password_field').value= password;

                                    // console.log(birthday);
                                    // console.log(name);
                                    // console.log(username);
                                    // console.log(password);
                                }  
                        },

                        error:function(data)
                        {
                            errormsg = JSON.stringify(data);
                            alert(errormsg);
                        }


                    });
                });
            }
            
            //Pass Data from anchored delete resident button to delete resident modal form       
            function load_resident_to_delete_modal()
            {
                $('#delete-resident-modal').on('show.bs.modal', function (e) {

                    var resident_id = $(e.relatedTarget).data('resident_id');
                    
                    $.ajax({
                        type : 'post',
                        url : 'admin_Actions.php',
                        data : {delete_load_resident_info:resident_id},
                        dataType: 'json',
                        success : function(data)
                        {

                            var len = data.length;

                            for(var i = 0; i<len; i++)
                                {
                                    var first_name = data[0]['first_name'];
                                    var middle_name = data[0]['middle_name'];
                                    var last_name = data[0]['last_name'];
                                        
                                    $('.delete-resident-name-field').text(first_name + ' ' + middle_name + ' ' + last_name);
                                    document.getElementById('delete_resident_id_field').value= resident_id;

                                    // console.log(birthday);

                                    // console.log(name);
                                    // console.log(username);
                                    // console.log(password);
                                }  
                        },

                        error:function(data)
                        {
                            errormsg = JSON.stringify(data);
                            alert(errormsg);
                        }


                    });
                });
            }

            //Search Records of Existing Residents
            function search_residents()
            {
                $('#search_field').keyup(function()
                {
                    var search=jQuery('#search_field').val();

                    jQuery.ajax({
                        method:'post',
                        url:'admin_Actions.php',
                        data:'search='+search,
                        success:function(data)
                        {
                            jQuery('#residents_table').html(data);
                            // console.log(data);
                        }
                    });	
                });
            }
            
//date
            document.addEventListener("DOMContentLoaded", function() {
        // Function to set the current date in the hidden field
        function setEditCurrentDate() {
            const currentDate = new Date().toISOString().slice(0, 10); // Get current date in YYYY-MM-DD format
            document.getElementById("edit_submission_date").value = currentDate;
        }

        // When the modal is shown, set the current date
        $('#edit-resident-modal').on('show.bs.modal', function () {
            setEditCurrentDate();
        });
    });

            $(document).ready(function(){
                load_resident_to_print_modal();
                load_resident_to_edit_modal();
                load_resident_to_delete_modal();
                search_residents();
            });

        </script>
    </body>    
</html>