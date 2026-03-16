<?php

require_once('dbConfig.php');
require_once('functions.php');

$userObj = new User();
$database = new Database();
$db = $database->dbConnection();

?>

<!DOCTYPE html>

<html>

<head>
    <style>
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
    <link rel="stylesheet" href="stylesheets/index_BarangayOfficials.css">

</head>

<body>
    <div>

        <!--Navbar Markup  -->
        <div>
            <nav class="navbar navbar-expand-lg mb-5">

                <button class="navbar-toggler navbar-light" type="button" data-toggle="collapse" data-target="#navbar1">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <img src="img/Logo.png" alt="logo" id="nav-img" class="ml-md-5">

                <div class="navbar-collapse collapse  justify-content-center" id="navbar1">
                    <ul class="navbar-nav">

                        <li class="nav-item pl-3 pr-3 ">
                            <a class="nav-link" href="index.php">Home</a>
                        </li>
                        <li class="nav-item pl-3 pr-3 active">
                            <a class="nav-link" href="index_BarangayOfficials.php">The Officers</a>
                        </li>
                    </ul>
                </div>

                <button class="btn btn-outline-primary login-btn mr-md-5" type="button" id="login-btn"
                    data-toggle="modal" data-target="#login-modal"> Login </button>


            </nav>
        </div>

        <div class="container-fluid ">

        <!-- Add Dropdown Menu for Year Filtering -->
<div class="row justify-content-center mb-4">
    <div class="col-md-4">
        <label for="year-select" class="form-label font-weight-bold">Filter by Year:</label>
        <select class="form-control" id="year-select">
            <option></option>
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
                <div>
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

                                    $currentYear = null;

                                    if ($stmt->rowCount() > 0) {
                                        while ($rowUser = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                            if ($currentYear !== $rowUser['official_year']) {
                                                if ($currentYear !== null) {
                                                    echo '</tbody></table>';
                                                }

                                                $currentYear = $rowUser['official_year'];
                                                echo "<h3 class='year-title' data-year='$currentYear' style='display: none;'>Year: $currentYear</h3>";
                                                echo "<table class='table table-borderless table-hover officials-table' data-year='$currentYear' style='display: none;'>
                                                    <thead>
                                                        <th class='officials-table-heading'>Officer Position</th>
                                                        <th class='officials-table-heading'>Officer Name</th>
                                                        <th class='officials-table-heading'>Address</th>
                                                        <th class='officials-table-heading'>Contact Number</th>
                                                        <th class='officials-table-heading'>Email</th>
                                                    </thead>
                                                    <tbody>";
                                            }
                                            echo "<tr>
                                            <td>{$rowUser['official_position']}</td>
                                            <td>{$rowUser['official_first_name']} {$rowUser['official_middle_name']} {$rowUser['official_last_name']}</td>
                                            <td>{$rowUser['official_sex']}</td>
                                            <td>{$rowUser['official_contact_info']}</td>
                                            <td>{$rowUser['official_email']}</td>
                                        </tr>";
                                    }

                                    echo '</tbody></table>';
                                } else {
                                    echo '<p>No records found.</p>';
                                }
                                ?>

                            </div>
                        </div>

                    </div>

                </div>
            </div>

        </div>
                                            


            <!-- Markup for Login Modal -->
            <div class="modal fade" id="login-modal" tabindex="-1" role="dialog" aria-labelledby="login-modal"
                aria-hidden="true">
                <div class="modal-dialog modal-md" role="document">

                    <div class="modal-content">

                        <div class="modal-header">
                            <h1 class="modal-title login-modal-heading" id="login-modal-heading">Login</h1>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>

                        <div class="modal-body">
                            <div class="form-container">
                                <form>

                                    <div class="form-row">
                                        <div class="col-md-12 form-group ">
                                            <div class="input-group mb-2">
                                                <div class="input-group-append">
                                                    <span class="input-group-text"><i class="fas fa-user"> </i> </span>
                                                </div>
                                                <input type="text" name="username_field" id="username_field"
                                                    class="form-control login-modal-field" placeholder="Enter Username"
                                                    required>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <div class="col-md-12 form-group ">
                                            <div class="input-group mb-3">
                                                <div class="input-group-append">
                                                    <span class="input-group-text"><i class="fas fa-key"> </i> </span>
                                                </div>
                                                <input type="password" name="password_field" id="password_field"
                                                    class="form-control login-modal-field" placeholder="Enter Password"
                                                    required>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row justify-content-center">
                                        <div class="col form-group text-right  pr-2 mr-2">
                                            <button type="submit" class="btn login-modal-btn" id="btn_login"
                                                name="btn_login">Sign in</button>
                                        </div>
                                        <div class="col form-group text-left pl-2 ml-2">
                                            <button type="button" data-dismiss="modal" class="btn login-modal-btn"
                                                id="btn_cancel" name="btn_cancel">Cancel</button>
                                        </div>
                                    </div>


                                </form>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

<!-- Warning Modal -->
<div class="modal fade" id="warningModal" tabindex="-1" role="dialog" aria-labelledby="warningModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="warningModalLabel">Warning</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Right-click is disabled on this page for security reasons.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
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

    // Hide all year titles and tables
    document.querySelectorAll('.year-title, .officials-table').forEach(element => {
        element.style.display = 'none';
    });

    if (selectedYear === 'all') {
        // Show all tables and titles
        document.querySelectorAll('.year-title, .officials-table').forEach(element => {
            element.style.display = '';
        });
    } else {
        // Show only the selected year's table and title
        document.querySelector(`.year-title[data-year='${selectedYear}']`).style.display = '';
        document.querySelector(`.officials-table[data-year='${selectedYear}']`).style.display = '';
    }
});



    // Disable right-click context menu and show warning modal
    document.addEventListener('contextmenu', function(e) {
        e.preventDefault();
        $('#warningModal').modal('show');
    });


        // Clear Modal Form when Closing
        function clear_modal() {
            $('#login-modal').on('hidden.bs.modal', function (e) {
                $(this)
                    .find("input,textarea,select")
                    .val('')
                    .end()
                    .find("input[type=checkbox], input[type=radio]")
                    .prop("checked", "")
                    .end();
            })
        }

        //Login Button Function
        function login() {
            $('#btn_login').click(function (e) {

                var valid = this.form.checkValidity();

                if (valid) {

                    var username = $('#username_field').val();
                    var password = $('#password_field').val();

                    e.preventDefault();

                    $.ajax({
                        type: 'POST',
                        url: 'admin_Actions.php',
                        data: { current_username: username, current_password: password },
                        success: function (data) {

                            if ($.trim(data) === "0") {
                                Swal.fire
                                    ({
                                        title: 'Login Success',
                                        text: 'Official Login',
                                        icon: 'success'
                                    })

                                setTimeout('window.location.href = "admin_Home.php"', 2000);

                            }

                            else if ($.trim(data) === "1") {
                                Swal.fire
                                    ({
                                        title: 'Login Success',
                                        text: 'Resident Login',
                                        icon: 'success'
                                    })

                                setTimeout('window.location.href = "user_Home.php"', 2000);

                            }

                            else {
                                Swal.fire
                                    ({
                                        title: 'Failed',
                                        text: data,
                                        icon: 'error'
                                    })
                            }
                        },

                        error: function (data) {
                            Swal.fire
                                ({
                                    title: 'Failed',
                                    text: 'Login Failed',
                                    icon: 'error'
                                })
                        }

                    });
                };

                $('#login-modal')
                    .find("input,textarea,select")
                    .val('')
                    .end()
                    .find("input[type=checkbox], input[type=radio]")
                    .prop("checked", "")
                    .end();

            });

        }

        $(document).ready(function () {
            clear_modal();
            login();
        });

    </script>
</body>

</html>