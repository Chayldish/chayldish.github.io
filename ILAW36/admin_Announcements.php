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


/* Container for the photo previews */
#edit_photo_container {
    display: flex;
    flex-wrap: wrap; /* Allows wrapping of photos */
    gap: 10px; /* Space between photos */
    justify-content: center; /* Centers the photos in the container */
}

/* Styles for individual photo previews */
.edit-photo-preview {
    width: 200px; /* Set a fixed width */
    height: 200px; /* Set a fixed height */
    object-fit: cover; /* Ensures the image maintains aspect ratio and fills the box */
    border: 2px solid #ccc; /* Optional: add a border for visual separation */
    border-radius: 8px; /* Optional: slightly rounded corners */
}

/* For remove button alignment */
.remove-photo-btn {
    position: absolute;
    top: 5px;
    right: 5px;
    padding: 2px 6px;
    font-size: 12px;
    line-height: 1;
    border-radius: 50%; /* Round button for a clean look */
}


#photoModal .modal-title {
  font-size: 24px;
  font-family: 'Montserrat', sans-serif;
  font-weight: bold;
}

#photoModal #modalBody {
  font-size: 18px;
  font-family: 'Roboto', sans-serif;
  color: #333;
}

#photoModal #modalDate {
  font-size: 14px;
  font-family: 'Arial', sans-serif;
  display: block;
  margin-top: 10px;
}


#modalPhoto {
    max-width: 100%; /* Ensure the image does not exceed the modal width */
    max-height: 500px; /* Define a consistent max height for the image */
    width: auto; /* Maintain aspect ratio */
    height: auto; /* Maintain aspect ratio */
    object-fit: contain; /* Ensure the image fits nicely within the modal */
    display: block; /* Center the image */
    margin: 0 auto; /* Center the image */
}


/* Photo grid container */
.photo-grid {
    display: flex; /* Use flexbox for side-by-side alignment */
    flex-wrap: nowrap; /* Prevent photos from wrapping to the next line */
    gap: 5px; /* Add a small space between photos */
    width: 100%; /* Ensure the grid spans the full container */
    margin: 0 auto; /* Center the grid */
}

/* Individual photo items */
.photo-item {
    flex: 1 1 20%; /* Each photo takes up 20% of the row */
    max-width: 20%; /* Ensure consistent width */
    margin: 0; /* Remove margins */
    padding: 0; /* Remove padding */
    box-sizing: border-box; /* Include padding/border in size calculations */
    overflow: hidden; /* Prevent overflow of images */
    position: relative; /* For precise layout control if needed */
}

/* Photo images */
.photo-image {
    width: 100%; /* Full width of the container */
    height: 100%; /* Full height of the container */
    object-fit: cover; /* Ensure the image fills the container proportionally */
    display: block; /* Remove inline spacing */
    margin: 0; /* No margin */
    padding: 0; /* No padding */
    border: 5px solid #000; /* Add a colored border to each photo (blue in this case) */
    border-radius: 5px; /* Optional: round the corners slightly */
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

        <title> Announcements </title>

        <!-- Montseratt Font -->
        <link rel="preconnect" href="https://fonts.gstatic.com">
        <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,400;0,500;0,800;1,500&display=swap" rel="stylesheet">
    


        <!-- Link for Bootstrap CSS -->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
        
        <!-- Link for Bootstrap Fontawesome Icons -->
        <script src="https://kit.fontawesome.com/4aee20adf0.js" crossorigin="anonymous"></script>
        
        <!-- Link for Local bootstrap Datetimepicker CSS -->
        <link rel="stylesheet" href="css/bootstrap-datetimepicker.min.css">

        <!-- Link for Local CSS -->
        <link rel="stylesheet" href="stylesheets/admin_Announcements.css" >

        <link href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/css/lightbox.min.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/js/lightbox.min.js"></script>


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

            <div class="container-fluid">
                <div class="page-container">

                    <!-- Search Bar and Create Post Markup -->
                    <div class="row justify-content-around pb-4 mb-2 add-container">

                        <div class="col-md-9 text-right mt-2 mb-2">
                            <button class="btn btn-danger create-btn" type="button" data-toggle="modal" data-target="#create-post-modal">
                                + CREATE NEW POST
                            </button>
                        </div>
                    </div>

                    <!-- PHP Code for Notifications -->
                    <div>        
                        <?php
                            // PHP Code for Notification Alert

                            if(isset($_GET['postCreated']))
                            {
                                echo 
                                '<div style="text-align:center" class = "alert alert-success alert-dismissible fade show" role="alert">
                                    Announcement Posted Successfully.
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>                               
                                </div>';
                            };

                            if(isset($_GET['postUpdated']))
                            {
                                echo 
                                '<div style="text-align:center" class = "alert alert-success alert-dismissible fade show" role="alert">
                                    Announcement Edited Successfully
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>                               
                                </div>';
                            };

                            if(isset($_GET['postDeleted']))
                            {
                                echo 
                                '<div style="text-align:center" class = "alert alert-success alert-dismissible fade show" role="alert">
                                    Announcement Deleted Successfully
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>                               
                                </div>';
                            };

                        ?>
                    </div>

                    <!-- Posts Table Markup -->
                    <div class="row justify-content-center">

                        <div class="col-md-10">

                            <div class="posts-container" id="card_container">

                                <?php
                                    $qry = "SELECT * FROM announcement_post ORDER BY post_id DESC";
                                    $stmt = $userObj->runQuery($qry);
                                    $stmt->execute();

                                    if($stmt->rowCount()>0)
                                        {
                                            while($rowUser = $stmt->fetch(PDO::FETCH_ASSOC)){ 
                                    
                                ?>
                                                
                                <div class="card pb-2  mt-5 ml-3 mr-3 post-card" >

                                    <div class="card-header post-header"> 
                                        <div class="row">

                                            <div class="col text-left">
                                                <?php print($rowUser['post_title']) ?>
                                            </div>

                                            <div class="col text-right">

                                                <a class="post-btn-anchor" href="#edit-post-modal" data-toggle="modal" data-post_id="<?php print($rowUser['post_id']) ?>" >
                                                    <button type="button" class="btn btn-danger  post-card-btn">
                                                        <span> <i class="fas fa-edit"></i></span>
                                                    </button>
                                                </a>

                                                <a class="post-btn-anchor" href="#delete-post-modal" data-toggle="modal" data-post_id="<?php print($rowUser['post_id']) ?>" >
                                                    <button type="button" class="btn btn-danger  post-card-btn">
                                                        <span> <i class="fas fa-trash-alt"></i></span>
                                                    </button>
                                                </a>
                                            </div>

                                        </div>
                                    </div>

                                    <div class="card-body">
    <h6 class="card-title post-title">Post written on <?php print($rowUser['post_date_time']) ?></h6>
    <p class="card-text post-body"><?php print($rowUser['post_body']) ?></p>
    <div class="photo-grid">
    <?php
    $photos = json_decode($rowUser['post_photo'], true);
    if (!empty($photos)) {
        foreach ($photos as $photo) {
            echo '<div class="photo-item">';
            echo '<a href="' . $photo . '" data-lightbox="post-gallery" data-title="Photo">';
            echo '<img src="' . $photo . '" alt="Post Photo" class="photo-image">';
            echo '</a>';
            echo '</div>';
        }
    }
    ?>
</div>



                                <?php
                                            } };
                                    ?>
                            </div>

                            <div class="row">

                            </div>

                        </div>
                        

                        
                    
                    </div>

                    <!-- Markup for Modal Dialogs -->

                    <!-- Markup for Create New Post Modal -->
                    <div class="modal fade" id="create-post-modal" tabindex="-1" role="dialog" aria-labelledby="create-post-modal" aria-hidden="true">
                    
                        <div class="modal-dialog modal-lg" role="document">

                            <div class="modal-content">

                                <div class="modal-header">
                                    <h5 class="modal-title create-post-heading" id="create-post-heading">Create New Post</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>

                                <div class="modal-body">

                                <form action="admin_Actions.php" method="post" enctype="multipart/form-data">

                                        <div class="form-row justify-content-center">

                                            <div class="col-md-12 form-group ">
                                                <label for="post_title_field" class="create-post-form-label" >Announcement Title</label>
                                                <input type="text" class="form-control create-post-form-field" placeholder="Title of the Announcement" id="post_title_field" name="post_title_field" required>
                                            </div>
                                                
                                        </div>

                                        <div class="form-row justify-content-center">

                                            <div class="col-md-12 form-group ">
                                                <label for="post_body_field" class="create-post-form-label" >Announcement Body</label>
                                                <textarea class="form-control create-post-form-field" placeholder="Information about the Announcement" id="post_body_field" name="post_body_field" rows="9" required></textarea>
                                            </div>
                                                
                                        </div>

                                        <div class="form-row justify-content-center">

                                            <div class="col-md-12 form-group">
                                                <label for="post_photo_field" class="create-post-form-label">Attach Photo</label>
                                                <input type="file" class="form-control create-post-form-field" id="post_photo_field" name="post_photo_field[]" accept="image/*" multiple onchange="validateFileLimit()" data-max-files="5">
                                                </div>
                                        </div>


                                        <div class="form-row justify-content-center">
                                            

                                            <div class="col-md-6 form-group text-center">
                                                <label for="post_date_time_field" class="create-post-form-label " >Date and Time of Announcement Creation</label>
                                                <div class="input-group date" id="datetimepicker">
                                                    <input type="text" class="form-control"  id="post_date_time_field" name="post_date_time_field" required readonly>
                                                    <div class="input-group-addon input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-calendar"></i></span>
                                                    </div>
                                                </div>

                                            </div>
                                                
                                        </div>

                                        <div class="row justify-content-center">

                                            <div class="col text-right mt-2 mb-2">
                                                <button type="submit" class="btn btn-danger create-post-btn" id="btn_create_post" name="btn_create_post">PUBLISH POST</button>                                   
                                            </div>
                                            <div class="col text-left mt-2 mb-2">
                                                <button type="button" class="btn btn-danger create-post-btn" data-dismiss="modal">CANCEL</button>                            
                                            </div>
                                            
                                        </div>
                                        
                                    </form>

                                </div>

                                
                                    
                            </div>
                        </div>
                    </div>

                    <!-- Markup for Edit Post Modal -->
                    <div class="modal fade" id="edit-post-modal" tabindex="-1" role="dialog" aria-labelledby="edit-post-modal" aria-hidden="true">
                    
                        <div class="modal-dialog modal-lg" role="document">

                            <div class="modal-content">

                                <div class="modal-header">
                                    <h5 class="modal-title edit-post-heading" id="edit-post-heading">Edit Post</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>

                                <div class="modal-body">
                                <form action="admin_Actions.php" method="post" enctype="multipart/form-data">


                                        <div class="form-row justify-content-center text-center">

                                            <div class="col-md-6 form-group">
                                                <label for="edit_post_id_field" class="edit-post-form-label" >Post ID</label>
                                                <input readonly style="text-align: center;" type="text" class="form-control edit-post-form-field" placeholder="ID" id="edit_post_id_field" name="edit_post_id_field" required>
                                            </div>
                                            
                                        </div>

                                        <div class="form-row justify-content-center">

                                            <div class="col-md-12 form-group ">
                                                <label for="edit_post_title_field" class="edit-post-form-label" >Announcement Title</label>
                                                <input type="text" class="form-control edit-post-form-field" placeholder="Title of the Announcement" id="edit_post_title_field" name="edit_post_title_field" required>
                                            </div>
                                                
                                        </div>

                                        

                                        <div class="form-row justify-content-center">

                                            <div class="col-md-12 form-group ">
                                                <label for="edit_post_body_field" class="edit-post-form-label" >Announcement Body</label>
                                                <textarea class="form-control edit-post-form-field" placeholder="Information about the Announcement" id="edit_post_body_field" name="edit_post_body_field" rows="9" required></textarea>
                                            </div>
                                                
                                        </div>

                                        <div class="form-row justify-content-center">

                                            <div class="col-md-12 form-group">
                                                <label for="edit_photo_container" class="edit-post-form-label">Attached Photos</label>
                                                <div id="edit_photo_container" class="photo-preview-container">
                                                    <!-- Existing photos will be dynamically added here -->
                                                </div>
                                            </div>
                                        </div>

                                        <input type="hidden" id="removed_photos" name="removed_photos" value="">

                                        <!-- Add this in the Edit Post Modal Form -->
                                            <div class="form-row justify-content-center">

                                                <div class="col-md-12 form-group">
                                                    <label for="additional_post_photo_field" class="edit-post-form-label">Add More Photos</label>
                                                    <input type="file" class="form-control edit-post-form-field" id="additional_post_photo_field" name="additional_post_photo_field[]" accept="image/*" multiple onchange="validateAdditionalPhotos()">
                                                    <large class="text" style="font-size: 18px; font-weight: bold;">You can upload up to 5 photos in total, including existing photos.</large>

                                                </div>
                                            </div>

                                        <div class="form-row justify-content-center">
                                            

                                            <div class="col-md-6 form-group text-center">
                                                <label for="edit_post_date_time_field" class="edit-post-form-label " >Date and Time of Announcement Creation</label>
                                                <div class="input-group date" id="datetimepicker">
                                                    <input type="text" class="form-control"  id="edit_post_date_time_field" name="edit_post_date_time_field" required readonly>
                                                    <div class="input-group-addon input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-calendar"></i></span>
                                                    </div>
                                                </div>

                                            </div>
                                                
                                        </div>

                                        <div class="row justify-content-center">

                                            <div class="col text-right mt-2 mb-2">
                                                <button type="submit" class="btn btn-danger edit-post-btn" id="btn_edit_post" name="btn_edit_post">UPDATE POST</button>                                   
                                            </div>
                                            <div class="col text-left mt-2 mb-2">
                                                <button type="button" class="btn btn-danger edit-post-btn" data-dismiss="modal">CANCEL</button>                            
                                            </div>
                                            
                                        </div>
                                        
                                    </form>

                                </div>

                                
                                    
                            </div>
                        </div>
                    </div>

                    <!-- Markup for Delete Post Modal -->
                    <div class="modal fade" id="delete-post-modal" tabindex="-1" role="dialog" aria-labelledby="delete-post-modal" aria-hidden="true">
                    
                        <div class="modal-dialog modal-lg" role="document">

                            <div class="modal-content">

                                <div class="modal-header">
                                    <h5 class="modal-title delete-post-heading" id="delete-post-heading">Delete Post</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>

                                <div class="modal-body">
                                    <form action="admin_Actions.php" method="post">

                                        <div class="form-row justify-content-center text-center">

                                            <div class="col-md-6 form-group">
                                                <label for="delete_post_id_field" class="delete-post-form-label" >Post ID</label>
                                                <input readonly style="text-align: center;" type="text" class="form-control delete-post-form-field" placeholder="ID" id="delete_post_id_field" name="delete_post_id_field" required>
                                            </div>
                                            
                                        </div>

                                        <div class="form-row justify-content-center">

                                            <div class="col-md-12 form-group ">
                                                <label for="delete_post_title_field" class="delete-post-form-label" >Announcement Title</label>
                                                <input readonly type="text" class="form-control delete-post-form-field" placeholder="Title of the Announcement" id="delete_post_title_field" name="delete_post_title_field" required>
                                            </div>
                                                
                                        </div>

                                        <div class="form-row justify-content-center">

                                            <div class="col-md-12 form-group ">
                                                <label for="delete_post_body_field" class="delete-post-form-label" >Announcement Body</label>
                                                <textarea readonly class="form-control delete-post-form-field" placeholder="Information about the Announcement" id="delete_post_body_field" name="delete_post_body_field" rows="9" required></textarea>
                                            </div>
                                                
                                        </div>

                                        <div class="form-row justify-content-center">
                                            

                                            <div class="col-md-6 form-group text-center">
                                                <label for="delete_post_date_time_field" class="delete-post-form-label " >Date and Time of Announcement Creation</label>
                                                <div class="input-group date" id="datetimepicker">
                                                    <input readonly type="text" class="form-control "  id="delete_post_date_time_field" name="delete_post_date_time_field" required readonly>
                                                    <div class="input-group-addon input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-calendar"></i></span>
                                                    </div>
                                                </div>

                                            </div>
                                                
                                        </div>

                                        <div class="row justify-content-center">

                                            <div class="col text-right mt-2 mb-2">
                                                <button type="submit" class="btn btn-danger delete-post-btn" id="btn_delete_post" name="btn_delete_post">DELETE POST</button>                                   
                                            </div>
                                            <div class="col text-left mt-2 mb-2">
                                                <button type="button" class="btn btn-danger delete-post-btn" data-dismiss="modal">CANCEL</button>                            
                                            </div>
                                            
                                        </div>
                                        
                                    </form>

                                </div>

                                
                                    
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

        <!-- Sweetalert JS -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

        <!-- JQuery JS -->
        <script src="https://code.jquery.com/jquery-3.5.1.js" integrity="sha256-QWo7LDvxbWT2tbbQ97B53yJnYU3WhH/C8ycbRAkjPDc=" crossorigin="anonymous"></script>

        <!-- Popper JS -->
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    
        <!-- Bootstrap JS -->
        <script type="text/javascript" src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"> </script>

        <!-- Moment Js for datetimepicker -->
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.0/moment.min.js"></script>
        
        <!-- Datetimepicker bootstrap JS -->
        <script src="js/bootstrap-datetimepicker.min.js"></script>
        


        <script>
    // Validate the total number of photos (existing + new) in the Edit Post Modal
    function validateAdditionalPhotos() {
        const existingPhotos = document.querySelectorAll("#edit_photo_container img").length; // Count existing photos
        const additionalPhotos = document.getElementById("additional_post_photo_field").files.length; // New photos count
        const totalPhotos = existingPhotos + additionalPhotos;

        if (totalPhotos > 5) {
            alert("You can only upload a maximum of 5 photos in total.");
            document.getElementById("additional_post_photo_field").value = ""; // Clear input
        }
    }

    // Document ready function
    document.addEventListener("DOMContentLoaded", () => {
        let currentIndex = 0;
        let photos = [];
        let postDetails = {};

        // Open modal and initialize photos and details
        document.querySelectorAll(".photo-item a").forEach((element, index) => {
            element.addEventListener("click", (e) => {
                e.preventDefault();
                const postCard = element.closest(".card");
                postDetails = {
                    title: postCard.querySelector(".post-header .col.text-left").innerText.trim(),
                    body: postCard.querySelector(".post-body").innerText.trim(),
                    date: postCard.querySelector(".post-title").innerText.trim(),
                };

                photos = Array.from(postCard.querySelectorAll(".photo-item a")).map((a) => a.href);
                currentIndex = index;
                showPhoto(currentIndex);
                $("#photoModal").modal("show");
            });
        });

        // Show photo and details in modal
        const showPhoto = (index) => {
            document.getElementById("modalPhoto").src = photos[index];
            document.getElementById("modalTitle").innerText = postDetails.title;
            document.getElementById("modalBody").innerText = postDetails.body;
            document.getElementById("modalDate").innerText = postDetails.date;
        };

        // Next button functionality
        document.getElementById("nextPhoto").addEventListener("click", () => {
            currentIndex = (currentIndex + 1) % photos.length;
            showPhoto(currentIndex);
        });

        // Previous button functionality
        document.getElementById("prevPhoto").addEventListener("click", () => {
            currentIndex = (currentIndex - 1 + photos.length) % photos.length;
            showPhoto(currentIndex);
        });
    });

    // Validate the number of photos for Create Post Modal
    function validateFileLimit() {
        const fileInput = document.getElementById('post_photo_field');
        const maxFiles = fileInput.dataset.maxFiles || 5; // Default max files if not set in HTML
        if (fileInput.files.length > maxFiles) {
            alert(`You can only upload a maximum of ${maxFiles} photos.`);
            fileInput.value = ''; // Clear the input
        }
    }

    // Clear the Create Post Modal on close
    function clear_modal() {
        $('#create-post-modal').on('hidden.bs.modal', function (e) {
            document.getElementById('post_title_field').value = "";
            document.getElementById('post_body_field').value = "";
        });
    }

    // Datepicker functions
    function load_date_picker_to_modal() {
        $('#create-post-modal').on('show.bs.modal', function (e) {
            date_time_picker_defaults();
            open_date_picker();
        });
    }

    function date_time_picker_defaults() {
        $.extend(true, $.fn.datetimepicker.defaults, {
            icons: {
                time: 'far fa-clock',
                date: 'far fa-calendar',
                up: 'fas fa-arrow-up',
                down: 'fas fa-arrow-down',
                previous: 'fas fa-chevron-left',
                next: 'fas fa-chevron-right',
                today: 'far fa-calendar-check-o',
                clear: 'far fa-trash',
                close: 'far fa-times'
            }
        });
    }

    function open_date_picker() {
        $('#datetimepicker').datetimepicker({
            ignoreReadonly: true,
            minDate: moment(),
            defaultDate: moment()
        });
    }

    let removedPhotos = []; // Array to track removed photos

    // Load post data into Edit Modal
    function load_post_to_edit_modal() {
        $('#edit-post-modal').on('show.bs.modal', function (e) {
            var post_id = $(e.relatedTarget).data('post_id');

            $.ajax({
                type: 'post',
                url: 'admin_Actions.php',
                data: { edit_load_post_info: post_id },
                dataType: 'json',
                success: function (data) {
                    var postDetails = data[0];

                    // Populate fields
                    document.getElementById('edit_post_id_field').value = post_id;
                    document.getElementById('edit_post_title_field').value = postDetails.post_title;
                    document.getElementById('edit_post_body_field').value = postDetails.post_body;
                    document.getElementById('edit_post_date_time_field').value = postDetails.post_date_time;

                    // Populate photos with "Remove" buttons
                    var photoContainer = document.getElementById('edit_photo_container');
                    photoContainer.innerHTML = ""; // Clear existing photos

                    if (postDetails.post_photo && postDetails.post_photo.length > 0) {
                        postDetails.post_photo.forEach(function (photo) {
                            var photoWrapper = document.createElement('div');
                            photoWrapper.style.display = "inline-block";
                            photoWrapper.style.position = "relative";
                            photoWrapper.style.margin = "5px";

                            var imgElement = document.createElement('img');
                            imgElement.src = photo;
                            imgElement.className = "edit-photo-preview";
                            imgElement.style.maxWidth = "100px";
                            imgElement.style.marginBottom = "5px";
                            imgElement.style.border = "1px solid #ccc";
                            imgElement.style.borderRadius = "5px";

                            var removeButton = document.createElement('button');
                            removeButton.innerHTML = "&times;";
                            removeButton.className = "btn btn-danger btn-sm remove-photo-btn";
                            removeButton.style.position = "absolute";
                            removeButton.style.top = "5px";
                            removeButton.style.right = "5px";
                            removeButton.dataset.photo = photo; // Attach photo URL
                            removeButton.addEventListener('click', function () {
                                photoWrapper.remove(); // Remove from UI
                                removedPhotos.push(photo); // Add to removal list
                            });

                            photoWrapper.appendChild(imgElement);
                            photoWrapper.appendChild(removeButton);
                            photoContainer.appendChild(photoWrapper);
                        });
                    }
                },
                error: function (data) {
                    console.error(data);
                }
            });
        });
    }

    // Function to handle post deletion
    function load_post_to_delete_modal() {
        $('#delete-post-modal').on('show.bs.modal', function (e) {
            var post_id = $(e.relatedTarget).data('post_id');

            $.ajax({
                type: 'post',
                url: 'admin_Actions.php',
                data: { delete_load_post_info: post_id },
                dataType: 'json',
                success: function (data) {
                    var postDetails = data[0];
                    document.getElementById('delete_post_id_field').value = post_id;
                    document.getElementById('delete_post_title_field').value = postDetails.post_title;
                    document.getElementById('delete_post_body_field').value = postDetails.post_body;
                    document.getElementById('delete_post_date_time_field').value = postDetails.post_date_time;
                },
                error: function (data) {
                    console.error(data);
                }
            });
        });
    }

    // Function to search posts dynamically
    function search_post() {
        $('#search_post_field').keyup(function () {
            var search_load_post = $('#search_post_field').val();

            $.ajax({
                method: 'post',
                url: 'admin_Actions.php',
                data: { search_load_post: search_load_post },
                success: function (data) {
                    $('#card_container').html(data);
                }
            });
        });
    }

    // Initialize functions on document ready
    $(document).ready(function () {
        load_date_picker_to_modal();
        clear_modal();
        load_post_to_edit_modal();

        // Handle form submission for Edit Modal
        $('#edit-post-modal form').on('submit', function () {
            document.getElementById('removed_photos').value = JSON.stringify(removedPhotos);
        });

        load_post_to_delete_modal();
        search_post();
    });
</script>



<div id="photoModal" class="modal" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 id="modalTitle" class="modal-title" style="font-size: 24px; font-family: 'Montserrat', sans-serif; font-weight: bold;">Post Title</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body text-center">
        <p id="modalBody" class="mt-3" style="font-size: 18px; font-family: 'Roboto', sans-serif; color: #333;">Post description here</p>
        <img id="modalPhoto" class="img-fluid" src="" alt="Photo" style="max-width: 100%; max-height: 600px; border-radius: 8px;">
        <small id="modalDate" class="text-muted" style="font-size: 14px; font-family: 'Arial', sans-serif; display: block; margin-top: 10px;">Post Date and Time</small>
      </div>
      <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-primary" id="prevPhoto">Previous</button>
        <button type="button" class="btn btn-primary" id="nextPhoto">Next</button>
      </div>
    </div>
  </div>
</div>



    </body>
    
</html>