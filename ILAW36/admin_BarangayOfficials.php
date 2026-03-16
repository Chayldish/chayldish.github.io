<?php

require_once('dbConfig.php');
require_once('functions.php');

$userObj = new User();
$database = new Database();
$db = $database->dbConnection();

session_start();

if (!isset($_SESSION['session_login'])) {
    header("Location: index.php");
}

if (isset($_GET['logout'])) {
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

 @media print {
        body {
            font-family: Arial, sans-serif;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table, th, td {
            border: 1px solid black;
            padding: 8px;
        }

        th {
            background-color: #f2f2f2;
            text-align: center;
        }

        td {
            text-align: left;
        }

        /* Hide buttons when printing */
        #print-button,
        .btn-danger {
            display: none;
        }
    }

        .bg-custom-image .text-muted {
            background: linear-gradient(45deg, #007bff, #000);
            font-weight: bold;
            color: white !important;
            /* Override the 'text-muted' class */
        }
    </style>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title> Barangay Officials </title>

    <!-- Montseratt Font -->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,400;0,500;0,800;1,500&display=swap"
        rel="stylesheet">


    <!-- Link for Bootstrap CSS -->
    <!-- <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css"> -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <!-- Link for Bootstrap Icons -->
    <script src="https://kit.fontawesome.com/4aee20adf0.js" crossorigin="anonymous"></script>
    <!-- Link for Local CSS -->
    <link rel="stylesheet" href="stylesheets/admin_BarangayOfficials.css">

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


        <div class="container-fluid ">

        <div class="row justify-content-around pb-4 mb-2 add-container">

        <div class="col-md-10 text-right mt-2 mb-2">
            <button class="btn btn-primary add-btn" type="button" data-toggle="modal" data-target="#add-official-modal">
                + ADD OFFICER
            </button>

            <!-- Print Button Added Beside Add Officer Button -->
            <button id="print-button" class="btn btn-primary add-btn ml-2" type="button">Print</button>
        </div>
</div>

<!-- Add Dropdown Menu for Year Filtering -->
<div class="row justify-content-center mb-4">
    <div class="col-md-4">
        <label for="year-select" class="form-label font-weight-bold">Filter by Year:</label>
        <select class="form-control" id="year-select">
            <option value="all">Show All</option>
            <option value="2010">2010</option>
            <option value="2011">2011</option>
            <option value="2012">2012</option>
            <option value="2013">2013</option>
            <option value="2014">2014</option>
            <option value="2015">2015</option>
            <option value="2016">2016</option>
            <option value="2017">2017</option>
            <option value="2018">2018</option>
            <option value="2019">2019</option>
            <option value="2020">2020</option>
            <option value="2021">2021</option>
            <option value="2022">2022</option>
            <option value="2023">2023</option>
            <option value="2024">2024</option>
        </select>
    </div>
</div>


            <div>
                <?php

                // PHP Code for Notification Alert
                
                if (isset($_GET['officialAdded'])) {
                    echo
                        '<div style="text-align:center" class = "alert alert-success alert-dismissible fade show" role="alert">
                                Officer Added Successfully.
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>                               
                            </div>';
                }
                ;

                if (isset($_GET['officialUpdated'])) {
                    echo
                        '<div style="text-align:center" class = "alert alert-success alert-dismissible fade show" role="alert">
                                Officer Edited Successfully
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>                               
                            </div>';
                }
                ;

                if (isset($_GET['officialDeleted'])) {
                    echo
                        '<div style="text-align:center" class = "alert alert-success alert-dismissible fade show" role="alert">
                                Officer Deleted Successfully
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>                               
                            </div>';
                }
                ;

                if (isset($_GET['officialUsernameTaken'])) {
                    echo
                        '<div style="text-align:center" class = "alert alert-danger alert-dismissible fade show" role="alert">
                                Barangay Official Edit Failed. Username is Already Taken, Please Choose Another Username
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>                               
                            </div>';
                }
                ;

                ?>
            </div>

            <div class="row justify-content-center">
    <div class="col-md-10">
        <div class="officials-table-container">
            <div class="table-responsive w-auto text-nowrap officials-table-div">
                <?php
                $qry = "
                SELECT * 
                FROM official_info 
                ORDER BY 
                    official_year ASC, 
                    CASE official_position
                        WHEN 'President Emeritus' THEN 1
                        WHEN 'President' THEN 2
                        WHEN 'Vice president (Internal)' THEN 3
                        WHEN 'Vice president (External)' THEN 4
                        WHEN 'Auditor 1' THEN 5
                        WHEN 'Auditor 2' THEN 6
                        WHEN 'Treasurer' THEN 7
                        WHEN 'Assistant Treasurer' THEN 8
                        WHEN 'Executive Secretary' THEN 9
                        WHEN 'Assistant Secretary' THEN 10
                        WHEN 'Business Manager' THEN 11
                        WHEN 'Assistant Business Manager' THEN 12
                        WHEN 'Info' THEN 13
                        WHEN 'Assistant Info' THEN 14
                        WHEN 'Eastern Barangay Coordinator' THEN 15
                        WHEN 'Northern Barangay Coordinator' THEN 16
                        WHEN 'Western Poblacion Barangay Coordinator' THEN 17
                        WHEN 'Southern Region Barangay Coordinator' THEN 18
                        ELSE 19
                    END ASC
                ";
                $stmt = $userObj->runQuery($qry);
                $stmt->execute();

                if ($stmt->rowCount() > 0) {
                    $currentYear = null;

                    while ($rowUser = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        // Check for a new year and start a new table
                        if ($currentYear !== $rowUser['official_year']) {
                            if ($currentYear !== null) {
                                // Close previous table
                                echo '</tbody></table>';
                            }

                            $currentYear = $rowUser['official_year'];

                            // Display year title
                            echo "<h3 class='table-year-title' id='table-{$currentYear}'>Year: $currentYear</h3>";

                            // Start new table
                            echo '<table class="table table-borderless table-hover officials-table" id="officials-table">';
                            echo '<thead>
                                    <tr>
                                        <th></th>
                                        <th></th>
                                        <th>Officer ID</th>
                                        <th>Officer Position</th>
                                        <th>Officer Name</th>
                                        <th>Address</th>
                                        <th>Contact Number</th>
                                        <th>Email</th>
                                        <th>Year</th>
                                        <th>Username</th>
                                        <th>Password</th>
                                    </tr>
                                  </thead>
                                  <tbody>';
                        }

                        // Render row
                        echo '<tr>
                                <td>
                                    <a class="table-btn-anchor" href="#delete-official-modal" data-toggle="modal" data-official_id="' . $rowUser['official_id'] . '">
                                        <button type="button" class="btn btn-danger officials-table-btn">
                                            <span><i class="fas fa-trash-alt"></i></span>
                                        </button>
                                    </a>
                                </td>
                                <td>
                                    <a class="table-btn-anchor" href="#edit-official-modal" data-toggle="modal" data-official_id="' . $rowUser['official_id'] . '">
                                        <button type="button" class="btn btn-danger officials-table-btn">
                                            <span><i class="fas fa-edit"></i></span>
                                        </button>
                                    </a>
                                </td>
                                <td>' . $rowUser['official_id'] . '</td>
                                <td>' . $rowUser['official_position'] . '</td>
                                <td>' . $rowUser['official_first_name'] . ' ' . $rowUser['official_middle_name'] . ' ' . $rowUser['official_last_name'] . '</td>
                                <td>' . $rowUser['official_sex'] . '</td>
                                <td>' . $rowUser['official_contact_info'] . '</td>
                                <td>' . $rowUser['official_email'] . '</td>
                                <td>' . $rowUser['official_year'] . '</td>
                                <td>' . $rowUser['official_username'] . '</td>
                                <td>' . $rowUser['official_password'] . '</td>
                              </tr>';
                    }

                    // Close the last table
                    echo '</tbody></table>';
                } else {
                    echo '<p>No records found.</p>';
                }
                ?>
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





            <!-- Markup for Modal Dialogs -->

            <!-- Markup for Add Official Modal -->
            <div class="modal fade" id="add-official-modal" tabindex="-1" role="dialog"
                aria-labelledby="add-official-modal" aria-hidden="true">

                <div class="modal-dialog modal-lg" role="document">

                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title add-official-heading" id="add-official-heading">Add Officer</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>

                        <div class="modal-body">
                            <form action="admin_Actions.php" method="post">

                                <div class="form-row">

                                    <div class="col-md-4 form-group ">
                                        <label for="first_name_field" class="add-official-form-label">First Name</label>
                                        <input type="text" class="form-control add-official-form-field"
                                            placeholder="Given Name" id="first_name_field" name="first_name_field" style="text-transform: capitalize"
                                            required onblur="capitalizeFirstLetter(this)">
                                    </div>

                                    <div class="col-md-4 form-group">
                                        <label for="middle_name_field" class="add-official-form-label">Middle
                                            Name</label>
                                        <input type="text" class="form-control add-official-form-field"
                                            placeholder="Given Name" id="middle_name_field" name="middle_name_field" style="text-transform: capitalize"
                                            required onblur="capitalizeFirstLetter(this)">
                                    </div>

                                    <div class="col-md-4 form-group">
                                        <label for="last_name_field" class="add-official-form-label">Last Name</label>
                                        <input type="text" class="form-control add-official-form-field"
                                            placeholder="Given Name" id="last_name_field" name="last_name_field" style="text-transform: capitalize"
                                            required onblur="capitalizeFirstLetter(this)">
                                    </div>

                                </div>

                                <div class="form-row">



                                    <div class="col-md-6 form-group">
                                        <label for="sex_field" class="add-official-form-label">Address</label>
                                        <input type="text" class="form-control add-official-form-field" id="sex_field"
                                            placeholder="Address" name="sex_field" style="text-transform: capitalize" required> 
                                    </div onblur="capitalizeFirstLetter(this)">

                                    <div class="col-md-6 form-group">
                                        <label for="position_field" class="add-official-form-label">Position</label>
                                        <select class="form-control add-official-form-field" id="position_field"
                                            placeholder="Position" name="position_field" required>
                                            <option></option>
                                            <option>President Emeritus</option>
                                            <option>President</option>
                                            <option>Vice president (Internal)</option>
                                            <option>Vice president (External)</option>
                                            <option>Auditor 1</option>
                                            <option>Auditor 2</option>
                                            <option>Treasurer</option>
                                            <option>Assistant Treasurer</option>
                                            <option>Executive Secretary</option>
                                            <option>Assistant Secretary</option>
                                            <option>Business Manager</option>
                                            <option>Assistant Business Manager</option>
                                            <option>Info</option>
                                            <option>Assistant Info</option>
                                            <option>Eastern Barangay Coordinator</option>
                                            <option>Northern Barangay Coordinator</option>
                                            <option>Western Poblacion Barangay Coordinator</option>
                                            <option>Southern Region Barangay Coordinator</option>
                                        </select>
                                    </div>



                                </div>

                                <div class="form-row">

                                    <div class="col-md-4 form-group">
                                        <label for="mobile_no_field" class="add-official-form-label">Mobile
                                            Number</label>
                                        <input type="tel" class="form-control add-official-form-field"
                                            id="mobile_no_field" placeholder="09XXXXXXXXX" name="mobile_no_field"
                                            required maxlength="11" pattern="\d{11}">
                                    </div>


                                    <div class="col-md-4 form-group">
                                        <label for="email_field" class="add-official-form-label">Email</label>
                                        <input type="email" class="form-control add-official-form-field"
                                            placeholder="Email" id="email_field" name="email_field" required>
                                    </div>

                                    <div class="col-md-4 form-group">
                                        <label for="year_field" class="add-official-form-label">Year</label>
                                        <select class="form-control add-official-form-field" id="year_field"
                                            placeholder="Year" name="year_field" required>
                                            <option></option>
                                            <option>2010</option>
                                            <option>2011</option>
                                            <option>2012</option>
                                            <option>2013</option>
                                            <option>2014</option>
                                            <option>2015</option>
                                            <option>2016</option>
                                            <option>2017</option>
                                            <option>2018</option>
                                            <option>2019</option>
                                            <option>2020</option>
                                            <option>2021</option>
                                            <option>2022</option>
                                            <option>2023</option>
                                            <option>2024</option>
                                        </select>
                                    </div>

                                </div>

                                <div class="row" id="username_check">


                                </div>

                                <div class="form-row">
                                    <div class="col-md-6 form-group ">
                                        <label for="username_field" class="add-official-form-label">Username</label>
                                        <input type="text" class="form-control add-official-form-field"
                                            placeholder="Username" id="username_field" name="username_field">
                                    </div>

                                    <div class="col-md-6 form-group">
                                        <label for="password_field" class="add-official-form-label">Password</label>
                                        <input type="text" class="form-control add-official-form-field"
                                            placeholder="Password" id="password_field" name="password_field">
                                    </div>
                                </div>

                                <div class="row justify-content-center">

                                    <div class="col text-right mt-2 mb-2">
                                        <button type="submit" class="btn btn-danger add-official-btn"
                                            id="btn_add_official" name="btn_add_official">ADD OFFICER</button>
                                    </div>
                                    <div class="col text-left mt-2 mb-2">
                                        <button type="button" class="btn btn-danger add-official-btn"
                                            data-dismiss="modal">CANCEL</button>
                                    </div>

                                </div>

                            </form>

                        </div>



                    </div>
                </div>
            </div>

            <!-- Markup for Edit Official Modal -->
            <div class="modal fade" id="edit-official-modal" tabindex="-1" role="dialog"
                aria-labelledby="edit-official-modal" aria-hidden="true">

                <div class="modal-dialog modal-lg" role="document">

                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title edit-official-heading" id="edit-official-heading">Edit Officer</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>

                        <div class="modal-body">
                            <form action="admin_Actions.php" method="post">

                                <div class="form-row justify-content-center text-center">

                                    <div class="col-md-6 form-group">
                                        <label for="edit_official_id_field" class="edit-official-form-label">Officer
                                            ID</label>
                                        <input readonly style="text-align: center;" type="text"
                                            class="form-control edit-official-form-field" placeholder="ID"
                                            id="edit_official_id_field" name="edit_official_id_field" required>
                                    </div>

                                </div>

                                <div class="form-row">

                                    <div class="col-md-4 form-group ">
                                        <label for="edit_official_first_name_field"
                                            class="edit-official-form-label">First Name</label>
                                        <input type="text" class="form-control edit-official-form-field"
                                            placeholder="Given Name" id="edit_official_first_name_field"
                                            name="edit_official_first_name_field" style="text-transform: capitalize" required onblur="capitalizeFirstLetter(this)">
                                    </div>

                                    <div class="col-md-4 form-group">
                                        <label for="edit_official_middle_name_field"
                                            class="edit-official-form-label">Middle Name</label>
                                        <input type="text" class="form-control edit-official-form-field"
                                            placeholder="Given Name" id="edit_official_middle_name_field"
                                            name="edit_official_middle_name_field" style="text-transform: capitalize" required onblur="capitalizeFirstLetter(this)">
                                    </div>

                                    <div class="col-md-4 form-group">
                                        <label for="edit_official_last_name_field" class="edit-official-form-label">Last
                                            Name</label>
                                        <input type="text" class="form-control edit-official-form-field"
                                            placeholder="Given Name" id="edit_official_last_name_field"
                                            name="edit_official_last_name_field" style="text-transform: capitalize" required onblur="capitalizeFirstLetter(this)">
                                    </div>

                                </div>

                                <div class="form-row">



                                    <div class="col-md-6 form-group">
                                        <label for="edit_official_sex_field"
                                            class="edit-official-form-label">Address</label>
                                        <input type="text" class="form-control edit-official-form-field"
                                            id="edit_official_sex_field" placeholder="Address"
                                            name="edit_official_sex_field" style="text-transform: capitalize" required onblur="capitalizeFirstLetter(this)">
                                    </div>

                                    <div class="col-md-6 form-group">
                                        <label for="edit_official_position_field"
                                            class="edit-official-form-label">Position</label>
                                        <select class="form-control edit-official-form-field"
                                            id="edit_official_position_field" placeholder="Position"
                                            name="edit_official_position_field" required>
                                            <option></option>
                                            <option>President Emeritus</option>
                                            <option>President</option>
                                            <option>Vice president (Internal)</option>
                                            <option>Vice president (External)</option>
                                            <option>Auditor 1</option>
                                            <option>Auditor 2</option>
                                            <option>Treasurer</option>
                                            <option>Assistant Treasurer</option>
                                            <option>Executive Secretary</option>
                                            <option>Assistant Secretary</option>
                                            <option>Business Manager</option>
                                            <option>Assistant Business Manager</option>
                                            <option>Info</option>
                                            <option>Assistant Info</option>
                                            <option>Eastern Barangay Coordinator</option>
                                            <option>Northern Barangay Coordinator</option>
                                            <option>Western Poblacion Barangay Coordinator</option>
                                            <option>Southern Region Barangay Coordinator</option>
                                        </select>
                                    </div>



                                </div>

                                <div class="form-row">



                                    <div class="col-md-4 form-group">
                                        <label for="edit_official_mobile_no_field"
                                            class="edit-official-form-label">Mobile Number</label>
                                        <input type="text" class="form-control edit-official-form-field"
                                            id="edit_official_mobile_no_field" placeholder="09XXXXXXXXX"
                                            name="edit_official_mobile_no_field" required>
                                    </div>

                                    <div class="col-md-4 form-group">
                                        <label for="edit_official_email_field"
                                            class="edit-official-form-label">Email</label>
                                        <input type="email" class="form-control edit-official-form-field"
                                            placeholder="Email" id="edit_official_email_field"
                                            name="edit_official_email_field" required>
                                    </div>

                                    <div class="col-md-4 form-group">
                                        <label for="edit_official_year_field" class="edit-official-form-label">Year</label>
                                        <select class="form-control edit-official-form-field"
                                            id="edit_official_year_field" placeholder="Year"
                                            name="edit_official_year_field" required>
                                            <option></option>
                                            <option>2010</option>
                                            <option>2011</option>
                                            <option>2012</option>
                                            <option>2013</option>
                                            <option>2014</option>
                                            <option>2015</option>
                                            <option>2016</option>
                                            <option>2017</option>
                                            <option>2018</option>
                                            <option>2019</option>
                                            <option>2020</option>
                                            <option>2021</option>
                                            <option>2022</option>
                                            <option>2023</option>
                                            <option>2024</option>
                                        </select>
                                    </div>

                                </div>

                                <div class="form-row">
                                    <div class="col-md-6 form-group ">
                                        <label for="edit_official_username_field"
                                            class="edit-official-form-label">Username</label>
                                        <input type="text" class="form-control edit-official-form-field"
                                            placeholder="Username" id="edit_official_username_field"
                                            name="edit_official_username_field">
                                    </div>

                                    <div class="col-md-6 form-group">
                                        <label for="edit_official_password_field"
                                            class="edit-official-form-label">Password</label>
                                        <input type="text" class="form-control edit-official-form-field"
                                            placeholder="Password" id="edit_official_password_field"
                                            name="edit_official_password_field">
                                    </div>
                                </div>

                                <div class="row justify-content-center">

                                    <div class="col text-right mt-2 mb-2">
                                        <button type="submit" class="btn btn-danger edit-official-btn"
                                            id="btn_edit_official" name="btn_edit_official">SAVE</button>
                                    </div>
                                    <div class="col text-left mt-2 mb-2">
                                        <button type="button" class="btn btn-danger edit-official-btn"
                                            data-dismiss="modal">CANCEL</button>
                                    </div>

                                </div>

                            </form>

                        </div>



                    </div>
                </div>
            </div>

            <!-- Markup for Delete Resident Modal -->
            <div class="modal fade" id="delete-official-modal" tabindex="-1" role="dialog"
                aria-labelledby="delete-official-modal" aria-hidden="true">

                <div class="modal-dialog modal-md" role="document">

                    <div class="modal-content">

                        <div class="modal-header pb-2 mb-3">
                            <h5 class="modal-title delete-official-heading" id="delete-official-heading">Delete Officer
                            </h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>

                        <div class="modal-body">

                            <form action="admin_Actions.php" method="post">

                                <div class="form-row justify-content-center text-center pt-2 mt-5">

                                    <div class="col-md-6 form-group">
                                        <label for="delete_official_id_field" class="delete-official-form-label">Officer
                                            ID</label>
                                        <input readonly style="text-align: center;" type="text"
                                            class="form-control delete-resident-form-field" placeholder="ID"
                                            id="delete_official_id_field" name="delete_official_id_field" required>
                                    </div>

                                </div>

                                <div class="row">
                                    <div class="col-md-12 justify-content-center">
                                        <p class="delete-official-name-field"></p>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12 justify-content-center">
                                        <p class="delete-official-prompt">Are you sure you want to delete this Officer?
                                        </p>
                                    </div>
                                </div>


                        </div>

                        <div class="modal-footer">
                            <div class="row justify-content-around">

                                <div class="col text-right mt-2 mb-2">
                                    <button type="submit" class="btn btn-danger delete-official-btn"
                                        id="btn_delete_official" name="btn_delete_official">DELETE</button>
                                </div>
                                <div class="col text-left mt-2 mb-2">
                                    <button type="button" class="btn btn-danger delete-official-btn"
                                        data-dismiss="modal">CANCEL</button>
                                </div>

                            </div>

                            </form>
                        </div>



                    </div>
                </div>

            </div>


        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.js"
        integrity="sha256-QWo7LDvxbWT2tbbQ97B53yJnYU3WhH/C8ycbRAkjPDc=" crossorigin="anonymous"></script>
    <script type="text/javascript"
        src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"> </script>

    <script>

document.getElementById('year-select').addEventListener('change', function () {
    const selectedYear = this.value;

    // Hide all tables
    document.querySelectorAll('.table-year-title, .officials-table').forEach(function (element) {
        element.style.display = 'none';
    });

    // Show only the selected year table
    if (selectedYear === 'all') {
        document.querySelectorAll('.table-year-title, .officials-table').forEach(function (element) {
            element.style.display = '';
        });
    } else {
        document.getElementById(`table-${selectedYear}`).style.display = '';
        document.getElementById(`table-${selectedYear}`).nextElementSibling.style.display = '';
    }
});


 // Function to capitalize the first letter of the input
 function capitalizeFirstLetter(inputField) {
        inputField.addEventListener("input", function() {
            this.value = this.value.charAt(0).toUpperCase() + this.value.slice(1).toLowerCase();
        });
    }

    capitalizeFirstLetter(document.getElementById("first_name_field"));
    capitalizeFirstLetter(document.getElementById("middle_name_field"));
    capitalizeFirstLetter(document.getElementById("last_name_field"));
    capitalizeFirstLetter(document.getElementById("sex_field"));
    capitalizeFirstLetter(document.getElementById("edit_official_first_name_field"));
    capitalizeFirstLetter(document.getElementById("edit_official_middle_name_field"));
    capitalizeFirstLetter(document.getElementById("edit_official_last_name_field"));
    capitalizeFirstLetter(document.getElementById("edit_official_sex_field"));
    
    document.getElementById('print-button').addEventListener('click', function () {
    const selectedYear = document.getElementById('year-select').value;

    let tableHTML = '';

    // Fetch the content of the visible table based on the selected year
    if (selectedYear === 'all') {
        // Include all tables if "Show All" is selected
        document.querySelectorAll('.officials-table').forEach(function (table) {
            // Clone the table to modify it for print
            const clonedTable = table.cloneNode(true);

            // Remove unnecessary columns (Edit, Delete, Username, Password)
            removeUnnecessaryColumns(clonedTable);

            tableHTML += table.previousElementSibling.outerHTML + clonedTable.outerHTML;
        });
    } else {
        // Fetch only the selected year's table
        const yearTable = document.getElementById(`table-${selectedYear}`);
        if (yearTable) {
            // Clone the table to modify it for print
            const clonedTable = yearTable.nextElementSibling.cloneNode(true);

            // Remove unnecessary columns (Edit, Delete, Username, Password)
            removeUnnecessaryColumns(clonedTable);

            tableHTML = yearTable.outerHTML + clonedTable.outerHTML;
        } else {
            alert('No data available for the selected year.');
            return;
        }
    }

    // Create a new window for printing
    const printWindow = window.open('', '', 'height=700,width=900');

    // Add style and content to the print window
    const printContents = `
    <html>
    <head>
        <title>Print Officials</title>
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
                width: 110px;
                height: 100px;
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
        <h3 class="sub-title">Officers</h3>
        ${tableHTML}
    </body>
    </html>`;

    // Write the content and add a delay before printing
    printWindow.document.write(printContents);
    printWindow.document.close();

    // Wait for the content to load before showing the print dialog
    setTimeout(() => {
        printWindow.print();
    }, 500);
});

// Helper function to remove unnecessary columns
function removeUnnecessaryColumns(table) {
    const headerCells = table.querySelectorAll('thead th');
    const bodyRows = table.querySelectorAll('tbody tr');

    // Remove Edit and Delete columns (first and second columns)
    headerCells[0].style.display = 'none';
    headerCells[1].style.display = 'none';

    // Remove Username and Password columns (ninth and tenth columns)
    headerCells[9].style.display = 'none';
    headerCells[10].style.display = 'none';

    // Hide corresponding table data cells
    bodyRows.forEach((row) => {
        row.children[0].style.display = 'none'; // Edit
        row.children[1].style.display = 'none'; // Delete
        row.children[9].style.display = 'none'; // Username
        row.children[10].style.display = 'none'; // Password
    });
}




        //Pass Data from anchored edit official button to edit official modal form
        function load_official_to_edit_modal() {
            $('#edit-official-modal').on('show.bs.modal', function (e) {

                var official_id = $(e.relatedTarget).data('official_id');


                $.ajax({
                    type: 'post',
                    url: 'admin_Actions.php',
                    data: { edit_load_official_info: official_id },
                    dataType: 'json',
                    success: function (data) {

                        var len = data.length;

                        for (var i = 0; i < len; i++) {

                            var position = data[0]['official_position'];
                            var first_name = data[0]['official_first_name'];
                            var middle_name = data[0]['official_middle_name'];
                            var last_name = data[0]['official_last_name'];
                            var sex = data[0]['official_sex'];
                            var contact_info = data[0]['official_contact_info'];
                            var email = data[0]['official_email'];
                            var year = data[0]['official_year'];
                            var username = data[0]['official_username'];
                            var password = data[0]['official_password'];


                            document.getElementById('edit_official_id_field').value = official_id;

                            document.getElementById('edit_official_first_name_field').value = first_name;
                            document.getElementById('edit_official_middle_name_field').value = middle_name;
                            document.getElementById('edit_official_last_name_field').value = last_name;
                            document.getElementById('edit_official_sex_field').value = sex;
                            document.getElementById('edit_official_mobile_no_field').value = contact_info;
                            document.getElementById('edit_official_email_field').value = email;
                            document.getElementById('edit_official_year_field').value = year;
                            document.getElementById('edit_official_username_field').value = username;
                            document.getElementById('edit_official_password_field').value = password;
                            document.getElementById('edit_official_position_field').value = position;

                            // console.log(official_id);

                            // console.log(first_name);
                            // console.log(middle_name);
                            // console.log(last_name);
                            // console.log(sex);
                            // console.log(contact_info);
                            // console.log(email);
                            // console.log(year);
                            // console.log(position);
                            // console.log(username);
                            // console.log(password);
                        }
                    },

                    error: function (data) {
                        errormsg = JSON.stringify(data);
                        alert(errormsg);
                    }


                });
            });
        }
        //Pass Data from anchored delete official button to delete official modal form       
        function load_official_to_delete_modal() {
            $('#delete-official-modal').on('show.bs.modal', function (e) {

                var official_id = $(e.relatedTarget).data('official_id');

                $.ajax({
                    type: 'post',
                    url: 'admin_Actions.php',
                    data: { delete_load_official_info: official_id },
                    dataType: 'json',
                    success: function (data) {

                        var len = data.length;

                        for (var i = 0; i < len; i++) {
                            var first_name = data[0]['official_first_name'];
                            var middle_name = data[0]['official_middle_name'];
                            var last_name = data[0]['official_last_name'];

                            $('.delete-official-name-field').text(first_name + ' ' + middle_name + ' ' + last_name);
                            document.getElementById('delete_official_id_field').value = official_id;

                            // console.log(birthday);

                            // console.log(name);
                            // console.log(username);
                            // console.log(password);
                        }
                    },

                    error: function (data) {
                        errormsg = JSON.stringify(data);
                        alert(errormsg);
                    }


                });
            });
        }

        //Check if Username Already Exists and Disable Button if it exists already ( Add and Edit Modal)
        function check_username() {
            $("#username_field").keyup(function () {

                var username = $(this).val().trim();

                if (username != '') {
                    $.ajax({
                        url: 'admin_Actions.php',
                        type: 'post',
                        data: { username_verify_official: username },
                        success: function (response) {
                            $("#username_check").html(response);
                        }
                    });

                    $.ajax({
                        url: 'admin_Actions.php',
                        type: 'post',
                        data: { username_verify_official_button: username },
                        success: function (response) {
                            if (response == 0) {
                                document.getElementById("btn_add_official").disabled = false;
                            }

                            if (response == 1) {
                                document.getElementById("btn_add_official").disabled = true;
                            }
                        }
                    });
                }

            });
        }

        $(document).ready(function () {

            load_official_to_edit_modal();
            load_official_to_delete_modal();
            check_username();
        });





    </script>
</body>

</html>