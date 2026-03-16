<?php
// Set timezone to Manila, Philippines
date_default_timezone_set('Asia/Manila');

session_start();

require_once('dbConfig.php'); 
require_once('functions.php'); 

// Initialize User object and Database connection
$userObj = new User();
$database = new Database();
$db = $database->dbConnection();



// Check if the user is logged in
if (!isset($_SESSION['session_login'])) {
    header("Location: index.php");
    exit();
} else {
    // Fetch the username from the session
    $official_username = $_SESSION['session_login']['official_username'];

    // Check if the login action has already been logged
    if (!isset($_SESSION['login_logged'])) {
        $login_time = date('Y-m-d H:i:s'); // Current time

        // Log the login action in the database
        $log_sql = "INSERT INTO admin_logs (official_username, login_time) VALUES (:username, :login_time)";
        $log_stmt = $db->prepare($log_sql);
        $log_stmt->bindParam(":username", $official_username);
        $log_stmt->bindParam(":login_time", $login_time);
        $log_stmt->execute();

        // Save the login time in the session for logout reference
        $_SESSION['login_time'] = $login_time;
        $_SESSION['login_logged'] = true;
    }
}

// Logout logic
if (isset($_GET['logout'])) {
    if (isset($_SESSION['session_login']['official_username']) && isset($_SESSION['login_time'])) {
        $official_username = $_SESSION['session_login']['official_username'];
        $logout_time = date('Y-m-d H:i:s'); // Current time
        $login_time = $_SESSION['login_time']; // Login time from session

        // Update the logout time for the corresponding log entry
        $logout_sql = "UPDATE admin_logs SET logout_time = :logout_time 
                       WHERE official_username = :username AND login_time = :login_time";
        $logout_stmt = $db->prepare($logout_sql);
        $logout_stmt->bindParam(":logout_time", $logout_time);
        $logout_stmt->bindParam(":username", $official_username);
        $logout_stmt->bindParam(":login_time", $login_time);
        $logout_stmt->execute();
    }

    // Destroy the session and redirect to login page
    session_destroy();
    unset($_SESSION);
    header("Location: index.php");
    exit();
}
?>


<!DOCTYPE html>
<html>
<head>
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
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


<style>

     body {
        font-family: 'Montserrat', sans-serif;
        background-color: #f8f9fa;
        color: #333;
        margin: 0;
        padding: 0;
    }

    h1, h2, h4 {
        font-weight: 700;
    }

    h1 {
        font-size: 2.5rem;
    }

    h2 {
        font-size: 2rem;
    }

    h4 {
        font-size: 1.5rem;
    }
    p {
        font-size: 1rem;
        line-height: 1.6;
    }

    a {
        text-decoration: none;
    }
/* About Section */
#about {
        padding: 50px 0;
    }

    #about h2 {
        margin-bottom: 20px;
    }

    #about p {
        text-align: justify;
    }

    .image-scroll-wrapper img {
        border-radius: 10px;
        margin-right: 20px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }
    
.bg-custom-image .text-muted {
    background: linear-gradient(45deg, #007bff, #000);
    font-weight: bold;
    color: white !important; /* Override the 'text-muted' class */
}
        
.hero-section {
            padding: 100px 0;
            background: linear-gradient(rgba(0, 123, 255, 0.7), rgba(255, 138, 101, 0.7)), url('img/bg.png') center center no-repeat;
            background-size: cover;
            color: white;
            position: relative;
            text-align: center;
            animation: fadeIn 2s ease-in-out;
        }


        .hero-section h1 {
            animation: slideInDown 1s ease-in-out;
        }

        .hero-section p {
            animation: slideInUp 1s ease-in-out;
        }

        .hero-section .btn-primary {
    background: #ff5722;
    border: none;
    padding: 10px 20px; /* Adjust padding */
    text-align: center;
    transition: background 0.3s ease-in-out;
}


        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        @keyframes slideInDown {
            from {
                transform: translateY(-50px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        @keyframes slideInUp {
            from {
                transform: translateY(50px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        /* Image Scrolling */
    .image-scroll-container {
        overflow: hidden;
        position: relative;
        padding: 15px 0;
    }

    @keyframes scroll {
    0% {
        transform: translateX(0);
    }
    100% {
        transform: translateX(-50%); /* Adjust scrolling for smoother effect */
    }
}

.image-scroll-wrapper {
    display: flex;
    animation: scroll 30s linear infinite; /* Adjust timing for smoother scroll */
}


        #ddown, .dropdown-menu {
    background-color: #007bff !important;
    border: none !important;
    color: white !important;
}

#ddown li a:hover {
    background-color: #ffcccc !important;
    color: #333 !important;
}

.bg-custom-image {
    padding: 20px 0;
    text-align: center;
    background: linear-gradient(45deg, #007bff, #000); /* Ensure gradient consistency */
}

.bg-custom-image .text-muted {
    color: #ffffff !important;
    font-size: 0.9rem;
}


#mission-vision {
    padding: 50px 20px;
    text-align: center;
}

#mission, #vision {
    margin-bottom: 20px; /* Ensure spacing between sections */
}




</style>

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
                <li class="nav-item pl-3 pr-3 active">
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

        <!-- Hero Area -->
        <section class="hero-section text-center d-flex align-items-center" id="heroarea">
    <div class="container">
        
        <!-- Hero Section Content -->
        <br><br>
        <h1 class="display-4 mt-5 mb-3">ILAGAN ASSOCIATION FOR WOMEN</h1>
        <p class="lead pt-3 pb-2"></p>
        <!-- VIEW MORE Button -->
        <button id="viewmore-btn" class="btn btn-primary btn-lg mt-3 mb-5">
            <a class="text-light" href="#mission-vision">VIEW MORE</a>
        </button>
    </div>
</section>
<br><br>

        <!-- Mission / Vision -->
        <div class="row align-items-center justify-content-around bg-primary text-light m-0" id="mission-vision"> 
            <div class="col-md-5 text-center py-md-5 px-md-0 p-sm-5" id="mission">
                <h4 class="py-2">MISSION</h4>
                <p class="py-2">To spearhead and consolidate Women Programs support LGU in aide to legislation of Womens's concerns and execution  of  Women Development Program.</p>
            </div>
            <div class="col-md-5 text-center py-md-5 px-md-0 p-sm-5" id="vision">
                <h4 class="py-2">VISION</h4>
                <p class="py-2"> A federal city-wide association composed of autonomous barangay chapters that shall promote unity, close relationship towards Total Women Development in the barangays and in our City, do hereby ordain and  promulgate this constitution and by-laws.</p>
            </div>
        </div>

       <!-- About Section -->
<section id="about" class="py-5">
    <div class="container" style="background-color: #f0f0f0; padding: 30px; border-radius: 10px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); max-width: 95%; margin: 0 auto;">
        <div class="row align-items-center">
            <div class="col-md-6 mb-4 mb-md-0 text-center">
                <h2>History of Ilagan Association of Women</h2>
                <p style="text-align: justify;">In Ilagan, Isabela, ILAW was founded and created by Olive Domingo on September 10, 2008, with the goal of promoting unity and improving women's development. At the time, there were only 50 members. The organization's membership grew over time, which led to the adoption of a per-barangay officer election system and a 20-peso membership fee, which would serve as a crucial source of income for their numerous activities. They also gave their members 20-peso identity cards. ILAW acknowledged the value of working with the local government unit (LGU) despite its non-governmental status to get crucial financial support because finances were few.</p>
                <p style="text-align: justify;">ILAW's headquarters, recognized as the Center for Women's Development, can be found on the first floor of the municipal hall in Ilagan, Isabela, generously provided by the LGU. This central location guarantees easy access and convenience for ILAW's members and strengthens its role as an effective LGU partner in the empowerment and development of women. Additionally, ILAW can establish chapter offices in each of the 91 barangays throughout Ilagan and significant sitios within the municipality.</p>
            </div>
            <div class="col-md-6 text-center">
                <div class="image-scroll-container">
                    <div class="image-scroll-wrapper">
                        <img src="img/logos.png" alt="About Us" class="img-fluid rounded shadow">
                        <img src="img/logos.png" alt="About Us" class="img-fluid rounded shadow">
                        <img src="img/logos.png" alt="About Us" class="img-fluid rounded shadow">
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section id="features" class="py-5 text-center">
    <div class="container" style="background-color: #007bff; padding: 30px; border-radius: 10px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">
        <h2 class="mb-4">Objectives</h2>
        <div class="row justify-content-center">
            <div class="col-md-12 mb-4">
                <i class="fas fa-shield-alt fa-3x feature-icon"></i>
                <h4>Secure Transactions</h4>
                <p>The center aims to guide women on the wisdom of being united, the art of loving, build bridges the will to succeed, undaunted of new horizon faith in new beginnings, is is a place where women dares to dream once again!</p>
            </div>
        </div>
    </div>
</section>

       
        <!-- Contact -->
        <div class="row m-0 justify-content-center" id="contact">
            <div class="col-12 text-center p-5 pb-lg-5 pb-md-0 pb-sm-0">
                <h4>CONTACT</h4>
            </div>
            <div class="col-lg-4 align-items-center col-sm-10 p-5 clearfix" >
                <h5 class="pb-0 pt-sm-0">Get In Touch</h5>
                <p class="pt-0" id="b-top"></p>

                <img src="./img/email-icon.png" alt="email-icon">
                <ul class="d-inline-block m-4 p-0 pl-1 clearfix">
                    <li >ILAW@gmail.com</li>
</ul>
<br>
                <img src="./img/telephone-icon.png" alt="phone-icon">
                <ul class="d-inline-block m-0 p-0 pl-3 clearfix">
                    <li>+ 63 97 6264 6047</li>
                    <li>+ 63 96 5159 4762</li>
                </ul>
                <br><br>

                
            </div>
            <div class="col-lg-5 col-md-10 col-sm-10 align-items-center pl-5 pt-lg-5 mb-lg-5 mb-md-0 mb-sm-0" id="b-left">
                <h6 class="pb-3">San Vicente, City of Ilagan, Isabela</h6> 
                <img src="./img/map.png " alt="location" class="mb-lg-5 mb-sm-0 mb-md-0 img-fluid"> 
            </div>
        </div>
    </div>

    <!-- Footer -->
    <div class="row m-0 p-0 bg-custom-image">
    <div class="col-12 text-center text-muted">
        <p class="m-0 pb-3 mt-md-2 mt-sm-2">Ilagan Association for Women, Copyright ©️ 2024</p>
    </div>
    </div>


    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

</body>
</html>