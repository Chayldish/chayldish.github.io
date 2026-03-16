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
        <!-- <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css"> -->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
        
        <!-- Link for Bootstrap Fontawesome Icons -->
        <script src="https://kit.fontawesome.com/4aee20adf0.js" crossorigin="anonymous"></script>
        
        <!-- Link for Local bootstrap Datetimepicker CSS -->
        <link rel="stylesheet" href="css/bootstrap-datetimepicker.min.css">

        <!-- Link for Local CSS -->
        <link rel="stylesheet" href="stylesheets/admin_Announcements.css" >

    </head>

    <body>
        <div>

            <!-- Navbar Markup -->
            <div>
                    <nav class="navbar navbar-expand-lg mb-5">

                        <button class="navbar-toggler navbar-light" type="button" data-toggle="collapse" data-target="#navbar1">
                            <span class="navbar-toggler-icon"></span>
                        </button>

                        <img src="img/Logo.png" alt="logo" id="nav-img" class="ml-md-5">

                        <div class="navbar-collapse collapse  justify-content-center" id="navbar1">
                            <ul class="navbar-nav">

                                <li class="nav-item pl-3 pr-3 ">
                                    <a class="nav-item nav-link" href="user_Home.php">Home</a>
                                </li>

                                <li class="nav-item pl-3 pr-3 ">
                                    <a class="nav-link" href="user_PersonalInfo.php">Personal Info</a>
                                </li>
                                <li class="nav-item pl-3 pr-3">
                                    <a class="nav-link" href="user_Documents.php">Documents</a>
                                </li>
                                <li class="nav-item pl-3 pr-3">
                                    <a class="nav-link" href="user_BarangayOfficials.php">The Officers</a>
                                </li>
                                <li class="nav-item pl-3 pr-3 active" >
                                    <a class="nav-link" href="user_Announcements.php" >Announcements</a>
                                </li>
                                
                            </ul>
                        </div>
                        
                        <a href="user_Announcements.php?logout=true">
                            <button style="font-family: 'Montserrat', sans-serif !important;" class="btn btn-outline-primary logout-btn  mr-md-5" type="button"  id="logout-btn">Logout</button>
                        </a>
                    </nav>
                </div>

            <div class="container-fluid">
                <div class="page-container">

                    <!-- Search Bar -->
                    <div class="row pb-4 mb-2 add-container">

                        <div class="col-md-4 offset-md-7 mt-2 mb-2 ">
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Search Posts" id="search_announcement_field" name="search_announcement_field">
                                <div class="input-group-append">
                                <button class="btn btn-danger search-btn" type="button">
                                    <i class="fa fa-search"></i>
                                </button>
                                </div>
                            </div>
                        </div>

                    </div>

                    <!-- Posts Table Markup -->
                    <div class="row justify-content-center">

                        <div class="col-md-10">

                            <div class="posts-container" id="card_container">

                                <?php
                                    $qry = "SELECT * FROM announcement_post ORDER BY post_id DESC";
                                    $stmt = $userObj->runQuery($qry);
                                    $stmt->execute();

                                    if ($stmt->rowCount() > 0) {
                                        while ($rowUser = $stmt->fetch(PDO::FETCH_ASSOC)) { 
                                    ?>
                                        <div class="card pb-2 mt-5 ml-3 mr-3 post-card">
    <div class="card-header post-header">
        <div class="row">
            <div class="col text-left">
                <?php print($rowUser['post_title']) ?>
            </div>
        </div>
    </div>
    <div class="card-body">
        <h6 class="card-title post-title">Post written on <?php print($rowUser['post_date_time']) ?></h6>
        <p class="card-text post-body"><?php print($rowUser['post_body']) ?></p>
        <!-- Photo Grid -->
        <div class="photo-grid">
            <?php
                $photos = json_decode($rowUser['post_photo'], true);
                if (!empty($photos)) {
                    foreach ($photos as $index => $photo) {
                        echo '<div class="photo-item">';
                        echo '<a href="#" class="photo-preview" data-index="' . $index . '" data-photos="' . htmlspecialchars(json_encode($photos)) . '">';
                        echo '<img src="' . $photo . '" alt="Post Photo" class="photo-image">';
                        echo '</a>';
                        echo '</div>';
                    }
                }
            ?>
        </div>
    </div>
</div>

<?php
    }
}
?>
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
                <img id="modalPhoto" class="img-fluid" src="" alt="Photo" style="max-width: 100%; max-height: 600px; border-radius: 8px;">
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-primary" id="prevPhoto">Previous</button>
                <button type="button" class="btn btn-primary" id="nextPhoto">Next</button>
            </div>
        </div>
    </div>
</div>

                                    

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
        <img id="modalPhoto" class="img-fluid" src="" alt="Photo" style="max-width: 100%; max-height: 600px; border-radius: 8px;">
      </div>
      <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-primary" id="prevPhoto">Previous</button>
        <button type="button" class="btn btn-primary" id="nextPhoto">Next</button>
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

document.addEventListener("DOMContentLoaded", () => {
        let currentIndex = 0;
        let photos = [];

        // Open modal and initialize photos and index
        document.querySelectorAll(".photo-preview").forEach((element) => {
            element.addEventListener("click", (e) => {
                e.preventDefault();
                photos = JSON.parse(element.dataset.photos);
                currentIndex = parseInt(element.dataset.index);
                showPhoto(currentIndex);
                $("#photoModal").modal("show");
            });
        });

        // Show photo in modal
        const showPhoto = (index) => {
            document.getElementById("modalPhoto").src = photos[index];
        };

        // Next photo
        document.getElementById("nextPhoto").addEventListener("click", () => {
            currentIndex = (currentIndex + 1) % photos.length;
            showPhoto(currentIndex);
        });

        // Previous photo
        document.getElementById("prevPhoto").addEventListener("click", () => {
            currentIndex = (currentIndex - 1 + photos.length) % photos.length;
            showPhoto(currentIndex);
        });
    });

        //Function to Search posts
        function search_post()
            {
                $('#search_announcement_field').keyup(function()
                {
                    var search_announcements=jQuery('#search_announcement_field').val();

                    jQuery.ajax({
                        method:'post',
                        url:'admin_Actions.php',
                        // data : {search_load_post:search},
                        // data:'search='+search,
                        data:'search_announcements='+search_announcements,
                        success:function(data)
                        {
                            jQuery('#card_container').html(data);
                            // console.log(data);
                        }
                    });	
                });
            }


            $(document).ready(function(){
                search_post();
            });

        </script>



    </body>
    
</html>