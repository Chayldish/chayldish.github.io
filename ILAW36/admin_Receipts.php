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

// Fetch uploaded images from the database, including the resident_id
$query = $db->prepare("SELECT resident_id, name, directory, submission_date, first_name, middle_name, last_name FROM uploads");
$query->execute();
$images = $query->fetchAll(PDO::FETCH_ASSOC);

// Pagination setup
$limit = 20; // Number of records per page
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? $_GET['page'] : 1; // Current page number
$offset = ($page - 1) * $limit; // Calculate offset for SQL query

// Fetch the total number of records
$totalQuery = $db->prepare("SELECT COUNT(*) as total FROM uploads");
$totalQuery->execute();
$totalResult = $totalQuery->fetch(PDO::FETCH_ASSOC);
$totalRecords = $totalResult['total'];
$totalPages = ceil($totalRecords / $limit); // Calculate total pages

// Fetch paginated records
$query = $db->prepare("SELECT resident_id, name, directory, submission_date, first_name, middle_name, last_name 
                       FROM uploads 
                       LIMIT :offset, :limit");
$query->bindValue(':offset', $offset, PDO::PARAM_INT);
$query->bindValue(':limit', $limit, PDO::PARAM_INT);
$query->execute();
$images = $query->fetchAll(PDO::FETCH_ASSOC);

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
            color: white !important;
        }

        .uploaded-image {
            width: 300px;
            height: 300px;
            margin: 10px;
        }
    </style>

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

   <!-- Display uploaded images in a table -->
<div class="container mt-5">
    <h2 class="text-center">Pending Requests</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th scope="col">Resident ID</th>
                <th scope="col">Member Name</th>
                <th scope="col">Submission Date</th>
                <th scope="col">Image Name</th>
                <th scope="col">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($images) > 0): ?>
                <?php foreach ($images as $index => $image): ?>
                    <tr>
                        <td><?php echo $image['resident_id']; ?></td>
                        <td><?php echo $image['first_name'] . ' ' . $image['middle_name'] . ' ' . $image['last_name']; ?></td>
                        <td><?php echo date("m/d/Y h:i A", strtotime($image['submission_date'])); ?></td>
                        <td><?php echo htmlspecialchars($image['name']); ?></td>
                        <td>
                            <button class="btn btn-primary" data-toggle="modal" data-target="#imageModal<?php echo $index; ?>">View</button>
                        </td>
                    </tr>

                    <!-- Modal structure for each image -->
                    <div class="modal fade" id="imageModal<?php echo $index; ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Image Preview</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>

                                <div class="modal-body">
                                    <form action="admin_Actions.php" method="post">
                                        <img src="<?php echo $image['directory'] . $image['name']; ?>" class="img-fluid" alt="Uploaded Image">
                                        <input type="hidden" name="resident_id" value="<?php echo $image['resident_id']; ?>">
                                        <input type="hidden" name="first_name" value="<?php echo $image['first_name']; ?>">
                                        <input type="hidden" name="middle_name" value="<?php echo $image['middle_name']; ?>"> 
                                        <input type="hidden" name="last_name" value="<?php echo $image['last_name']; ?>">
                                        <input type="hidden" name="submission_date" value="<?php echo date("m/d/Y h:i A", strtotime($image['submission_date'])); ?>">


                                        
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" id="btn_confirm" name="btn_confirm">DELETE</button>
                                            <button type="submit" class="btn btn-primary" id="btn_confirm" name="btn_confirm">CONFIRM</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5" class="text-center">No images found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- Pagination Links -->
<nav aria-label="Page navigation">
    <ul class="pagination justify-content-center">
        <?php if ($page > 1): ?>
            <li class="page-item">
                <a class="page-link" href="?page=<?php echo $page - 1; ?>&limit=<?php echo $limit; ?>" aria-label="Previous">
                    <span aria-hidden="true">&laquo;</span>
                </a>
            </li>
        <?php endif; ?>

        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <li class="page-item <?php echo $i == $page ? 'active' : ''; ?>">
                <a class="page-link" href="?page=<?php echo $i; ?>&limit=<?php echo $limit; ?>"><?php echo $i; ?></a>
            </li>
        <?php endfor; ?>

        <?php if ($page < $totalPages): ?>
            <li class="page-item">
                <a class="page-link" href="?page=<?php echo $page + 1; ?>&limit=<?php echo $limit; ?>" aria-label="Next">
                    <span aria-hidden="true">&raquo;</span>
                </a>
            </li>
        <?php endif; ?>
    </ul>
</nav>

    <div class="row m-0 p-0 bg-custom-image">
        <div class="col-12 text-center text-muted">
            <p class="m-0 pb-3 mt-md-2 mt-sm-2">Ilagan Association for Women, Copyright ©️ 2024</p>
        </div>
    </div>

    <!-- Bootstrap JavaScript -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
    // Set the submission date in the hidden input field when the modal is shown
    $(document).ready(function() {
        $('.modal').on('show.bs.modal', function() {
            let currentDate = new Date().toISOString().split('T')[0]; // Get date in YYYY-MM-DD format
            $(this).find('#submission_date').val(currentDate); // Set the value to the submission_date input
        });
    });

    function confirmAction() {
        alert("Image confirmed!");
    }
</script>
</body>
</html>
