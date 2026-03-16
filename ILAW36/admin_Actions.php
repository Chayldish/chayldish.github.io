<?php

use function PHPSTORM_META\map;

require_once('dbConfig.php');
require_once('functions.php');

$userObj = new User();
$database = new Database();
$db = $database->dbConnection();

if (isset($_POST['print_load_resident_info'])) {
    $resident_id = $_POST['print_load_resident_info'];
    $query = "SELECT * FROM resident_info WHERE resident_id = :resident_id";
    $stmt = $userObj->runQuery($query);
    $stmt->bindValue(':resident_id', $resident_id);
    $stmt->execute();
    $resident = $stmt->fetch(PDO::FETCH_ASSOC);
    echo json_encode($resident);
    exit;
}



if (isset($_POST['barangay'])) {
    $barangay = $_POST['barangay'];
    $stat = 'member'; // We want to only fetch "member" status

    // Pagination inputs
    $limit = isset($_POST['limit']) ? (int)$_POST['limit'] : 10; // Default to 10 records per page
    $page = isset($_POST['page']) ? (int)$_POST['page'] : 1; // Default to page 1
    $offset = ($page - 1) * $limit; // Calculate offset for SQL query

    // Fetch residents based on the selected barangay, stat (member), with pagination
    $qry = "SELECT * FROM resident_info WHERE barangay = :barangay AND stat = :stat ORDER BY resident_id ASC LIMIT :limit OFFSET :offset";
    $stmt = $userObj->runQuery($qry);
    $stmt->bindValue(':barangay', $barangay, PDO::PARAM_STR);
    $stmt->bindValue(':stat', $stat, PDO::PARAM_STR);
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    
    $residents = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Fetch the total number of rows for the selected barangay and stat
    $countQry = "SELECT COUNT(*) FROM resident_info WHERE barangay = :barangay AND stat = :stat";
    $countStmt = $userObj->runQuery($countQry);
    $countStmt->execute(['barangay' => $barangay, 'stat' => $stat]);
    $totalRows = $countStmt->fetchColumn();

    $totalPages = ceil($totalRows / $limit); // Calculate total pages

    // Build the HTML table for the filtered residents
    $html = '<table class="table table-borderless table-hover resident-table">';
    $html .= '<thead><tr>';
    $html .= "<th>Membership Number</th><th>First Name</th><th>Middle Name</th><th>Last Name</th><th>Purok</th><th>Barangay</th><th>Birthdate</th><th>Status</th>";
    $html .= '</tr></thead><tbody>';

    foreach ($residents as $resident) {
        $html .= '<tr>';
        $html .= '<td>' . $resident['resident_id'] . '</td>';
        $html .= '<td>' . $resident['first_name'] . '</td>';
        $html .= '<td>' . $resident['middle_name'] . '</td>';
        $html .= '<td>' . $resident['last_name'] . '</td>';
        $html .= '<td>' . $resident['purok'] . '</td>';
        $html .= '<td>' . $resident['barangay'] . '</td>';
        $html .= '<td>' . $resident['birthday'] . '</td>';
        $html .= '<td>' . $resident['stat'] . '</td>';
        $html .= '</tr>';
    }

    $html .= '</tbody></table>';

    // Add pagination controls if there are multiple pages
    if ($totalPages > 1) {
        $html .= '<nav class="mt-3">';
        $html .= '<ul class="pagination justify-content-center">';

        // Previous button
        if ($page > 1) {
            $html .= '<li class="page-item"><a class="page-link" href="#" onclick="changePage(' . ($page - 1) . ')">Previous</a></li>';
        }

        // Page numbers
        for ($i = 1; $i <= $totalPages; $i++) {
            $active = $i == $page ? 'active' : '';
            $html .= '<li class="page-item ' . $active . '"><a class="page-link" href="#" onclick="changePage(' . $i . ')">' . $i . '</a></li>';
        }

        // Next button
        if ($page < $totalPages) {
            $html .= '<li class="page-item"><a class="page-link" href="#" onclick="changePage(' . ($page + 1) . ')">Next</a></li>';
        }

        $html .= '</ul>';
        $html .= '</nav>';
    }

    // Return the table and pagination controls as the AJAX response
    echo $html;
    exit;
}




//Add Resident Data to Database when add button on the modal is pressed
if (isset($_POST['btn_add_resident'])) {
    // Fetch form data
    $first_name = $_POST['first_name_field'];
    $middle_name = $_POST['middle_name_field'];
    $last_name = $_POST['last_name_field'];
    $suffix = $_POST['suffix_field'];
    $birthday = $_POST['birthday_field'];
    $alias = $_POST['alias_field'];
    $sex = $_POST['sex_field'];
    $civil_stat = $_POST['civil_stat_field'];
    $mobile_no = $_POST['mobile_no_field'];
    $email = $_POST['email_field'];
    $religion = $_POST['religion_field'];
    $voter_stat = $_POST['voter_stat_field'];
    $username = $_POST['username_field'];
    $password = $_POST['password_field'];
    $fathers_last_name = $_POST['fathers_last_name_field'];
    $mothers_last_name = $_POST['mothers_last_name_field'];
    $mothers_first_name = $_POST['mothers_first_name_field'];
    $num_children = $_POST['num_children_field'];
    $purok = $_POST['purok_field'];
    $barangay = $_POST['barangay_field'];
    $submission_date = date('Y-m-d');

    // Resident ID generation
    $year = date('y'); // Get last two digits of the current year (e.g., '24' for 2024)

    // SQL query to fetch the highest resident ID of the current year (starts with '24-')
    $query = "SELECT MAX(resident_id) AS last_resident_id FROM resident_info WHERE resident_id LIKE :year_pattern";
    $stmt = $db->prepare($query);
    $stmt->execute(['year_pattern' => $year . '-%']);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    // Handle case where there are no residents for the current year
    if ($row && $row['last_resident_id']) {
        // Extract the numeric part of the resident ID (after the dash)
        $last_number = (int) substr($row['last_resident_id'], 3); // Get the number after "year-"
        $new_number = $last_number + 1;
    } else {
        $new_number = 1; // Start with 1 if there are no existing records for this year
    }

    // Create the new resident ID (e.g., "24-01", "24-02", etc.)
    $resident_id = $year . '-' . str_pad($new_number, 2, '0', STR_PAD_LEFT);

    // Call the add_resident method to insert the new resident with the generated resident_id
    if (
        $userObj->add_resident(
            $first_name,
            $middle_name,
            $last_name,
            $suffix,
            $birthday,
            $alias,
            $sex,
            $civil_stat,
            $mobile_no,
            $email,
            $religion,
            $voter_stat,
            $username,
            $password,
            $fathers_last_name,
            $mothers_last_name,
            $mothers_first_name,
            $num_children,
            $purok,
            $barangay,
            $submission_date,
            $resident_id
            )
            ) {
                // Redirect after successful insertion
                $userObj->redirect('index.php?residentAdded');
            } else {
                // Set an error message in the session
                session_start(); // Start the session
                $_SESSION['errorMessage'] = "Username already taken, please choose another one.";
                // Redirect to index.php with error
                $userObj->redirect('index.php?errorOccurred');
            }
        }

// Add Official Data to Database when add button on the modal is pressed
if (isset($_POST['btn_add_official'])) {
    $position = $_POST['position_field'];
    $first_name = $_POST['first_name_field'];
    $middle_name = $_POST['middle_name_field'];
    $last_name = $_POST['last_name_field'];
    $sex = $_POST['sex_field'];
    $contact_info = $_POST['mobile_no_field'];
    $email = $_POST['email_field']; // Retrieve the email field
    $year = $_POST['year_field'];
    $username = $_POST['username_field'];
    $password = $_POST['password_field'];

    // Include email in the method call
    if ($userObj->add_official($position, $first_name, $middle_name, $last_name, $sex, $contact_info, $email, $year, $username, $password)) {
        $userObj->redirect('admin_BarangayOfficials.php?officialAdded');
    } else {
        echo "Error";
    }
}

// add new announcement
if (isset($_POST['btn_create_post'])) {
    $post_title = $_POST['post_title_field'];
    $post_body = $_POST['post_body_field'];
    $post_date_time = $_POST['post_date_time_field'];
    $uploaded_photos = []; // Array to store paths of uploaded photos

    // Check if files are uploaded
    if (isset($_FILES['post_photo_field']) && !empty($_FILES['post_photo_field']['name'][0])) {
        $photo_files = $_FILES['post_photo_field'];
        $upload_directory = "uploads/posts/";

        // Create directory if not exists
        if (!is_dir($upload_directory)) {
            mkdir($upload_directory, 0755, true);
        }

        // Check file limit
        if (count($photo_files['name']) > 5) {
            die('Error: You can only upload a maximum of 5 photos.');
        }

        // Loop through uploaded files
        for ($i = 0; $i < count($photo_files['name']); $i++) {
            $file_name = basename($photo_files['name'][$i]);
            $target_file = $upload_directory . $file_name;

            // Validate and move file
            if (move_uploaded_file($photo_files['tmp_name'][$i], $target_file)) {
                $uploaded_photos[] = $target_file; // Save the file path
            } else {
                echo "Error uploading file: " . $file_name;
            }
        }
    }

    // Convert array of uploaded photo paths to JSON
    $photo_paths = json_encode($uploaded_photos);

    // Save post data into the database
    $qry = "INSERT INTO announcement_post (post_title, post_body, post_date_time, post_photo) 
            VALUES (:post_title, :post_body, :post_date_time, :post_photo)";
    $stmt = $db->prepare($qry);
    $stmt->bindParam(':post_title', $post_title);
    $stmt->bindParam(':post_body', $post_body);
    $stmt->bindParam(':post_date_time', $post_date_time);
    $stmt->bindParam(':post_photo', $photo_paths);
    $stmt->execute();

    // Redirect to the announcements page
    header("Location: admin_Announcements.php?postCreated=true");
}


//Edit Resident Data on Database when edit button on the modal is pressed
if (isset($_POST['btn_edit_resident'])) {
    $resident_id = $_POST['edit_resident_id_field'];
    $first_name = $_POST['edit_first_name_field'];
    $middle_name = $_POST['edit_middle_name_field'];
    $last_name = $_POST['edit_last_name_field'];
    $suffix = $_POST['edit_suffix_field'];
    $birthday = $_POST['edit_birthday_field'];
    $alias = $_POST['edit_alias_field'];
    $sex = $_POST['edit_sex_field'];
    $civil_stat = $_POST['edit_civil_stat_field'];
    $mobile_no = $_POST['edit_mobile_no_field'];
    $email = $_POST['edit_email_field'];
    $religion = $_POST['edit_religion_field'];
    $voter_stat = $_POST['edit_voter_stat_field'];
    $username = $_POST['edit_username_field'];
    $password = $_POST['edit_password_field'];
    $fathers_last_name = $_POST['edit_fathers_last_name_field'];
    $mothers_last_name = $_POST['edit_mothers_last_name_field'];
    $mothers_first_name = $_POST['edit_mothers_first_name_field'];
    $num_children = $_POST['edit_num_children_field']; // Add this line
    $purok = $_POST['edit_purok_field'];
    $barangay = $_POST['edit_barangay_field'];
    $stat = $_POST['edit_stat_field']; // New field for Stat

    if ($userObj->edit_resident($resident_id, $first_name, $middle_name, $last_name, $suffix, $birthday, $alias, $sex, $civil_stat, $mobile_no, $email, $religion, $voter_stat, $username, $password, $fathers_last_name, $mothers_last_name, $mothers_first_name, $num_children, $purok, $barangay, $stat)) {
        $userObj->redirect('admin_Residents.php?residentUpdated');
    } else {
        $userObj->redirect('admin_Residents.php?usernameTaken');
    }
}


// Edit Barangay Official Data on Database when edit button on the modal is pressed
if (isset($_POST['btn_edit_official'])) {
    $official_id = $_POST['edit_official_id_field'];
    $position = $_POST['edit_official_position_field'];
    $first_name = $_POST['edit_official_first_name_field'];
    $middle_name = $_POST['edit_official_middle_name_field'];
    $last_name = $_POST['edit_official_last_name_field'];
    $sex = $_POST['edit_official_sex_field'];
    $contact_info = $_POST['edit_official_mobile_no_field'];
    $email = $_POST['edit_official_email_field']; // Retrieve the email field
    $year = $_POST['edit_official_year_field'];
    $username = $_POST['edit_official_username_field'];
    $password = $_POST['edit_official_password_field'];

    // Include email in the method call
    if ($userObj->edit_official($official_id, $position, $first_name, $middle_name, $last_name, $sex, $contact_info, $email, $year, $username, $password)) {
        $userObj->redirect('admin_BarangayOfficials.php?officialUpdated');
    } else {
        $userObj->redirect('admin_BarangayOfficials.php?officialUsernameTaken');
    }
}


if (isset($_POST['btn_edit_post'])) {
    $post_id = $_POST['edit_post_id_field'];
    $post_title = $_POST['edit_post_title_field'];
    $post_body = $_POST['edit_post_body_field'];
    $post_date_time = $_POST['edit_post_date_time_field'];
    $removed_photos = isset($_POST['removed_photos']) ? json_decode($_POST['removed_photos'], true) : [];

    // Fetch current photos
    $qry = "SELECT post_photo FROM announcement_post WHERE post_id = :post_id";
    $stmt = $userObj->runQuery($qry);
    $stmt->bindParam(":post_id", $post_id);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $current_photos = json_decode($row['post_photo'], true);

    // Remove selected photos
    $updated_photos = array_diff($current_photos, $removed_photos);

    // Handle new uploaded photos
    $upload_directory = "uploads/posts/";
    $additional_photos = [];

    if (isset($_FILES['additional_post_photo_field']) && !empty($_FILES['additional_post_photo_field']['name'][0])) {
        $photo_files = $_FILES['additional_post_photo_field'];

        // Check total photos count
        if (count($updated_photos) + count($photo_files['name']) > 5) {
            die('Error: You can only have a maximum of 5 photos.');
        }

        // Ensure upload directory exists
        if (!is_dir($upload_directory)) {
            mkdir($upload_directory, 0755, true);
        }

        // Process uploaded files
        for ($i = 0; $i < count($photo_files['name']); $i++) {
            $file_name = basename($photo_files['name'][$i]);
            $target_file = $upload_directory . $file_name;

            if (move_uploaded_file($photo_files['tmp_name'][$i], $target_file)) {
                $additional_photos[] = $target_file;
            } else {
                echo "Error uploading file: " . $file_name;
            }
        }
    }

    // Combine existing and new photos
    $all_photos = array_merge($updated_photos, $additional_photos);
    $updated_photos_json = json_encode(array_values($all_photos));

    // Use the edit_post function to update the post
    if ($userObj->edit_post($post_id, $post_title, $post_body, $post_date_time, $updated_photos_json)) {
        $userObj->redirect('admin_Announcements.php?postUpdated');
    } else {
        echo "Error updating post.";
    }
}


//Delete Post Data on Database when Delete button on the modal is pressed
if (isset($_POST['btn_delete_post'])) {
    $post_id = $_POST['delete_post_id_field'];


    if ($userObj->delete_post($post_id)) {
        // echo "Successfully Added";
        $userObj->redirect('admin_Announcements.php?postDeleted');
    } else {
        echo "Error";
    }
}


// Upload transaction
if (isset($_POST['btn_confirm'])) {

    $resident_id = $_POST['resident_id'];
    $first_name = $_POST['first_name'];
    $middle_name = $_POST['middle_name'];
    $last_name = $_POST['last_name'];
    $description = $_POST['description'];
    $submission_date = date("Y-m-d H:i:s", strtotime($_POST['submission_date']));


    $userObj->upload_transaction($resident_id, $first_name, $middle_name, $last_name, $description, $submission_date);

}

//receipts
if (isset($_POST['btn_confirm'])) {
    $resident_id = $_POST['resident_id'];
    // Assume you have a function to move the image or mark it as approved
    $query = $db->prepare("DELETE FROM uploads WHERE resident_id = :resident_id");
    $query->bindParam(':resident_id', $resident_id);
    
    if ($query->execute()) {
        // Redirect to the same page to refresh the table
        header("Location: admin_Receipts.php");
        exit();
    } else {
        // Handle error
        echo "Error confirming the request.";
    }
}


// Upload Image to DB
if (isset($_POST['btn_add_photo'])) {
    // Name of the uploaded file
    $filename = $_FILES['myfiles']['name'];
    $directory = 'uploads/'; // Specify the upload directory
    // Destination of the file on the server
    $destination = 'uploads/' . $filename;

    // Get the file extension
    $extension = pathinfo($filename, PATHINFO_EXTENSION);

    $resident_id = $_POST['resident_id'];
    $last_name = $_POST['last_name'];
    $middle_name = $_POST['middle_name'];
    $first_name = $_POST['first_name'];
    date_default_timezone_set('Asia/Manila'); // Set timezone in backend
    $submission_date = date('Y-m-d H:i:s');

    // The physical file on a temporary uploads directory on the server
    $file = $_FILES['myfiles']['tmp_name'];
    $size = $_FILES['myfiles']['size'];

    $userObj->upload_document($file, $destination, $filename, $directory, $resident_id, $submission_date, $last_name, $middle_name, $first_name);

}



//Upload Document to DB
if(isset($_POST['btn_upload']))
{
    
    $description = $_POST['description_field'];
    $date_time = $_POST['document_date_time_field'];

    // Name of the uploaded file
    $filename = $_FILES['myfile']['name'];
    $directory = 'documents/';
    // destination of the file on the server
    $destination = 'documents/' . $filename;

    // get the file extension
    $extension = pathinfo($filename, PATHINFO_EXTENSION);

    // the physical file on a temporary uploads directory on the server
    $file = $_FILES['myfile']['tmp_name'];
    $size = $_FILES['myfile']['size'];
 
    $userObj->upload_documents($file, $destination, $filename,  $description, $date_time, $directory);

}


//Delete Document Data on Database when Delete button on the modal is pressed
if (isset($_POST['btn_delete_document'])) {
    $id = $_POST['delete_document_id_field'];
    $filename = $_POST['delete_document_title_field'];

    if ($userObj->delete_document($id) && $userObj->delete_document_file($filename)) {
        $userObj->redirect('admin_Documents.php?documentDeleted');
    } else {
        $userObj->redirect('admin_Documents.php?documentDeleteError');
    }
}

//Ajax Calls

if (isset($_POST['edit_load_resident_info'])) {
    $resident_id = $_POST['edit_load_resident_info'];

    $resident_arr = array();

    $qry = "SELECT * FROM resident_info WHERE resident_id ='$resident_id'";
    $stmt = $userObj->runQuery($qry);
    $result = $stmt->execute();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $resident_id = $row['resident_id'];
        $first_name = $row['first_name'];
        $middle_name = $row['middle_name'];
        $suffix = $row['suffix'];
        $last_name = $row['last_name'];
        $birthday = $row['birthday'];
        $alias = $row['alias'];
        $sex = $row['sex'];
        $civil_stat = $row['civil_stat'];
        $mobile_no = $row['mobile_no'];
        $email = $row['email'];
        $religion = $row['religion'];
        $voter_stat = $row['voter_stat'];
        $username = $row['username'];
        $password = $row['password'];
        $fathers_last_name = $row['fathers_last_name'];
        $mothers_last_name = $row['mothers_last_name'];
        $mothers_first_name = $row['mothers_first_name'];
        $num_children = $row['num_children']; // Add this line
        $purok = $row['purok'];
        $barangay = $row['barangay'];
        $submission_date = $row['submission_date'];
        $stat = $row['stat'];

        $resident_arr[] = array(
            "resident_id" => $resident_id,
            "first_name" => $first_name,
            "middle_name" => $middle_name,
            "suffix" => $suffix,
            "last_name" => $last_name,
            "birthday" => $birthday,
            "alias" => $alias,
            "sex" => $sex,
            "civil_stat" => $civil_stat,
            "mobile_no" => $mobile_no,
            "email" => $email,
            "religion" => $religion,
            "voter_stat" => $voter_stat,
            "username" => $username,
            "password" => $password,
            "fathers_last_name" => $fathers_last_name,
            "mothers_last_name" => $mothers_last_name,
            "mothers_first_name" => $mothers_first_name,
            "num_children" => $num_children, // Add this line
            "purok" => $purok,
            "barangay" => $barangay,
            "submission_date" => $submission_date,
            "stat" => $stat
        );
    }

    echo json_encode($resident_arr);
}


//Function for ajax call from delete modal to fetch resident information from database
if (isset($_POST['delete_load_resident_info'])) {
    $resident_id = $_POST['delete_load_resident_info'];

    $resident_arr = array();

    $qry = "SELECT * from resident_info WHERE resident_id ='$resident_id'";
    $stmt = $userObj->runQuery($qry);
    $result = $stmt->execute();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

        $resident_id = $row['resident_id'];

        $first_name = $row['first_name'];
        $middle_name = $row['middle_name'];
        $last_name = $row['last_name'];

        $resident_arr[] = array
        (
            "resident_id" => $resident_id,
            "first_name" => $first_name,
            "middle_name" => $middle_name,
            "last_name" => $last_name
        );

    }

    echo json_encode($resident_arr);

}

// Function for ajax call from official edit modal to fetch barangay official information from database
if (isset($_POST['edit_load_official_info'])) {
    $official_id = $_POST['edit_load_official_info'];

    $official_arr = array();

    $qry = "SELECT * FROM official_info WHERE official_id = :official_id"; // Use parameterized query for security

    $stmt = $userObj->runQuery($qry);
    $stmt->bindParam(':official_id', $official_id, PDO::PARAM_INT); // Bind the parameter for security
    $result = $stmt->execute();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $official_first_name = $row['official_first_name'];
        $official_middle_name = $row['official_middle_name'];
        $official_last_name = $row['official_last_name'];
        $official_position = $row['official_position'];
        $official_sex = $row['official_sex'];
        $official_contact_info = $row['official_contact_info'];
        $official_email = $row['official_email']; // Retrieve email
        $official_year = $row['official_year'];
        $official_username = $row['official_username'];
        $official_password = $row['official_password'];

        $official_arr[] = array(
            "official_first_name" => $official_first_name,
            "official_middle_name" => $official_middle_name,
            "official_last_name" => $official_last_name,
            "official_position" => $official_position,
            "official_sex" => $official_sex,
            "official_contact_info" => $official_contact_info,
            "official_email" => $official_email, // Add email to array
            "official_year" => $official_year,
            "official_username" => $official_username,
            "official_password" => $official_password
        );
    }

    echo json_encode($official_arr);
}


//Function for ajax call from delete modal to fetch barangay official information from database
if (isset($_POST['delete_load_official_info'])) {
    $official_id = $_POST['delete_load_official_info'];

    $official_arr = array();

    $qry = "SELECT * from official_info WHERE official_id ='$official_id'";
    $stmt = $userObj->runQuery($qry);
    $result = $stmt->execute();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $official_first_name = $row['official_first_name'];
        $official_middle_name = $row['official_middle_name'];
        $official_last_name = $row['official_last_name'];

        $official_arr[] = array
        (
            "official_id" => $official_id,
            "official_first_name" => $official_first_name,
            "official_middle_name" => $official_middle_name,
            "official_last_name" => $official_last_name
        );

    }

    echo json_encode($official_arr);

}

//Function for ajax call from post edit modal to fetch post information from database gerante
if (isset($_POST['edit_load_post_info'])) {
    $post_id = $_POST['edit_load_post_info'];

    $post_arr = array();

    $qry = "SELECT * from announcement_post WHERE post_id ='$post_id'";

    $stmt = $userObj->runQuery($qry);
    $result = $stmt->execute();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $post_title = $row['post_title'];
        $post_body = $row['post_body'];
        $post_date_time = $row['post_date_time'];
        $post_photo = json_decode($row['post_photo'], true); // Decode JSON photos

        $post_arr[] = array(
            "post_title" => $post_title,
            "post_body" => $post_body,
            "post_date_time" => $post_date_time,
            "post_photo" => $post_photo // Add photos to the response
        );
    }

    echo json_encode($post_arr);
}


//Function for ajax call from post edit modal to fetch post information from database
if (isset($_POST['delete_load_post_info'])) {
    $post_id = $_POST['delete_load_post_info'];

    $post_arr = array();

    $qry = "SELECT * from announcement_post WHERE post_id ='$post_id'";

    $stmt = $userObj->runQuery($qry);
    $result = $stmt->execute();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

        $post_title = $row['post_title'];
        $post_body = $row['post_body'];
        $post_date_time = $row['post_date_time'];

        $post_arr[] = array
        (
            "post_title" => $post_title,
            "post_body" => $post_body,
            "post_date_time" => $post_date_time
        );

    }

    echo json_encode($post_arr);

}

//Ajax Call for Resident Table Search
if (isset($_POST['search'])) {
    $search = $_POST['search'];
    $sql = "SELECT * FROM resident_info ";
    if ($search != '') {
        $sql .= " WHERE resident_id LIKE '%$search%' 
        OR first_name LIKE '%$search%' 
        OR middle_name LIKE '%$search%' 
        OR last_name LIKE '%$search%' 
        OR purok LIKE '%$search%' 
        OR barangay LIKE '%$search%' 
        OR suffix LIKE '%$search%' 
        OR alias LIKE '%$search%' 
        OR birthday LIKE '%$search%' 
        OR sex LIKE '%$search%' 
        OR mobile_no LIKE '%$search%' 
        OR email LIKE '%$search%' 
        OR religion LIKE '%$search%' 
        OR civil_stat LIKE '%$search%' 
        OR voter_stat LIKE '%$search%' 
        OR fathers_last_name LIKE '%$search%' 
        OR mothers_first_name LIKE '%$search%' 
        OR mothers_last_name LIKE '%$search%' 
        OR num_children LIKE '%$search%'
        OR stat LIKE '%$search%'  
        OR username LIKE '%$search%' 
        OR password LIKE '%$search%'";



    }
    $sql .= "ORDER BY first_name ASC";
    $stmt = $userObj->runQuery($sql);
    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (isset($data['0'])) {
        $html = '

        <table class="table table-borderless table-hover resident-table " >

            <thead>

                <th class="resident-table-heading"></th>
                <th class="resident-table-heading"></th>
                <th class="resident-table-heading">Resident ID</th>
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
                <th class="resident-table-heading">Fathers First Name</th>
                <th class="resident-table-heading">Fathers Last Name</th>
                <th class="resident-table-heading">Mothers First Name</th>
                <th class="resident-table-heading">Mothers Last Name</th>
                <th class="resident-table-heading">Number of Children</th>
                <th class="resident-table-heading">Status</th>
                <th class="resident-table-heading">Username</th>
                <th class="resident-table-heading">Password</th>


            </thead>
        
        ';
        foreach ($data as $list) {
            $html .= '
            
            <tr>

                <td>
                    <a class="table-btn-anchor" href="#delete-resident-modal" data-toggle="modal" data-resident_id="' . $list['resident_id'] . '" >
                        <button type="button" class="btn btn-danger table-btn">
                            <span> <i class="fas fa-trash-alt"></i></span>
                        </button>
                    </a>
                </td> 

                <td>
                    <a class="table-btn-anchor" href="#edit-resident-modal" data-toggle="modal" data-resident_id="' . $list['resident_id'] . '" >
                        <button type="button" class="btn btn-danger table-btn">
                            <span> <i class="fas fa-edit"></i></span>
                        </button>
                    </a>
                </td>

                <td>' . $list['resident_id'] . '</td>

                <td>' . $list['first_name'] . '</td>

                <td>' . $list['middle_name'] . '</td>

                <td>' . $list['last_name'] . '</td>

                <td>' . $list['purok'] . '</td>

                <td>' . $list['barangay'] . '</td>

                <td>' . $list['birthday'] . '</td>

                <td>' . $list['suffix'] . '</td>

                <td>' . $list['sex'] . '</td>

                <td>' . $list['civil_stat'] . '</td>

                <td>' . $list['alias'] . '</td>

                <td>' . $list['voter_stat'] . '</td>

                <td>' . $list['mobile_no'] . '</td>

                <td>' . $list['email'] . '</td>

                <td>' . $list['religion'] . '</td>

                <td>' . $list['fathers_last_name'] . '</td>

                <td>' . $list['mothers_first_name'] . '</td>

                <td>' . $list['mothers_last_name'] . '</td>

                <td>' . $list['num_children'] . '</td>

                <td>' . $list['stat'] . '</td>

                <td>' . $list['username'] . '</td>

                <td>' . $list['password'] . '</td>

            </tr>
            ';
        }
        $html .= '</table>';

        echo $html;
    } else {
        echo
            '
        <table class="table table-borderless table-hover resident-table " >

            <thead>

                <th class="resident-table-heading"></th>
                <th class="resident-table-heading"></th>
                <th class="resident-table-heading">Resident ID</th>
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
                <th class="resident-table-heading">Fathers First Name</th>
                <th class="resident-table-heading">Fathers Last Name</th>
                <th class="resident-table-heading">Mothers First Name</th>
                <th class="resident-table-heading">Mothers Last Name</th>
                <th class="resident-table-heading">Number of Children</th>
                <th class="resident-table-heading">Status</th>
                <th class="resident-table-heading">Username</th>
                <th class="resident-table-heading">Password</th>

            </thead>

                <tr>
                    <td></td> 
                    <td></td> 
                    <td> No Record Found </td> 
                    <td> No Record Found </td> 
                    <td> No Record Found </td> 
                    <td> No Record Found </td> 
                    <td> No Record Found </td> 
                    <td> No Record Found </td> 
                    <td> No Record Found </td> 
                    <td> No Record Found </td> 
                    <td> No Record Found </td> 
                    <td> No Record Found </td> 
                    <td> No Record Found </td> 
                    <td> No Record Found </td> 
                    <td> No Record Found </td> 
                    <td> No Record Found </td> 
                    <td> No Record Found </td> 
                    <td> No Record Found </td>
                    <td> No Record Found </td> 
                    <td> No Record Found </td>  
                    <td> No Record Found </td> 
                    <td> No Record Found </td> 
                    <td> No Record Found </td> 
                    <td> No Record Found </td> 

                </tr>
            
        </table>  
        ';
    }

}

//Ajax Call for Post Search
if (isset($_POST['search_load_post'])) {
    $search = $_POST['search_load_post'];
    $sql = "SELECT * FROM announcement_post ";
    if ($search != '') {
        $sql .= " WHERE   post_id  like '%$search%' or post_title like '%$search%' or post_body like '%$search%'
                        or post_date_time like '%$search%'
                
                        ";
    }
    $sql .= "ORDER BY post_id DESC";

    $stmt = $userObj->runQuery($sql);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($stmt->rowCount() > 0) {

        foreach ($result as $row) {
            $html = '

            <div class="card pb-2  mt-5 ml-3 mr-3 post-card" >

                <div class="card-header post-header"> 
                    <div class="row">

                        <div class="col text-left">
                            ' . $row['post_title'] . '
                        </div>

                        <div class="col text-right">

                            <a class="post-btn-anchor" href="#edit-post-modal" data-toggle="modal" data-post_id=' . $row['post_id'] . ' >
                                <button type="button" class="btn btn-danger  post-card-btn">
                                    <span> <i class="fas fa-edit"></i></span>
                                </button>
                            </a>

                            <a class="post-btn-anchor" href="#delete-post-modal" data-toggle="modal" data-post_id=' . $row['post_id'] . ' >
                                <button type="button" class="btn btn-danger  post-card-btn">
                                    <span> <i class="fas fa-trash-alt"></i></span>
                                </button>
                            </a>
                        </div>

                    </div>
                </div>

                <div class="card-body">
                    <h6 class="card-title post-title">Post written on ' . $row['post_date_time'] . '</h5>
                    <p class="card-text post-body">' . $row['post_body'] . '</p>
                </div>
            </div>

            ';

            echo $html;
        }


    } else {
        echo
            '  <div class="card pb-2  mt-5 ml-3 mr-3 post-card" >

                <div class="card-header post-header"> 
                    <div class="row">

                        <div class="col text-left">
                            No Result Found
                        </div>

                    </div>
                </div>

                <div class="card-body">
                    <h6 class="card-title post-title">No Result Found</h5>
                    <p class="card-text post-body">No Result Found</p>
                </div>
            </div>
        ';
    }

}

//Ajax Call for Post Search for User Page
if (isset($_POST['search_announcements'])) {
    $search = $_POST['search_announcements'];
    $sql = "SELECT * FROM announcement_post ";
    if ($search != '') {
        $sql .= " WHERE   post_id  like '%$search%' or post_title like '%$search%' or post_body like '%$search%'
                        or post_date_time like '%$search%'
                
                        ";
    }
    $sql .= "ORDER BY post_id DESC";

    $stmt = $userObj->runQuery($sql);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($stmt->rowCount() > 0) {

        foreach ($result as $row) {
            $html = '

            <div class="card pb-2  mt-5 ml-3 mr-3 post-card" >

                <div class="card-header post-header"> 
                    <div class="row">

                        <div class="col text-left">
                            ' . $row['post_title'] . '
                        </div>

                    </div>
                </div>

                <div class="card-body">
                    <h6 class="card-title post-title">Post written on ' . $row['post_date_time'] . '</h5>
                    <p class="card-text post-body">' . $row['post_body'] . '</p>
                </div>
            </div>

            ';

            echo $html;
        }


    } else {
        echo
            '  <div class="card pb-2  mt-5 ml-3 mr-3 post-card" >

                <div class="card-header post-header"> 
                    <div class="row">

                        <div class="col text-left">
                            No Result Found
                        </div>

                    </div>
                </div>

                <div class="card-body">
                    <h6 class="card-title post-title">No Result Found</h5>
                    <p class="card-text post-body">No Result Found</p>
                </div>
            </div>
        ';
    }

}

//Ajax Call for Checking IF Resident Username Already Exists 
if(isset($_POST['username_verify']))
{

    $username = $_POST['username_verify'];
 
    $query = "SELECT * FROM resident_info WHERE username='".$username."'";
    
    $stmt=$userObj->runQuery($query);
    $stmt->execute();
    $result=$stmt->fetchAll(PDO::FETCH_ASSOC);

    $response = '<div style="text-align:center" class = "alert alert-success alert-dismissible fade show col-md-6 offset-md-3" role="alert">
                    Username is Available
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>                               
                </div>';

    if($stmt->rowCount()>0)
    {
        $response ='<div style="text-align:center" class = "alert alert-danger alert-dismissible fade show col-md-6 offset-md-3" role="alert">
                        Username Already Taken
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>                               
                    </div>';
    }
 
    echo $response;
    die;
}

//Ajax Call for Disabling Submit button when Resident Username Already Exists
if(isset($_POST['username_verify_button']))
{

    $username = $_POST['username_verify_button'];
 
    $query = "SELECT * FROM resident_info WHERE username='".$username."'";
    
    $stmt=$userObj->runQuery($query);
    $stmt->execute();
    $result=$stmt->fetchAll(PDO::FETCH_ASSOC);

    $response = 0;

    if($stmt->rowCount()>0)
    {
        $response = 1;
    }
 
    echo $response;
    die;
}


//Ajax Call for Checking IF Resident Username Already Exists 
if (isset($_POST['username_verify_official'])) {

    $username = $_POST['username_verify_official'];

    $query = "SELECT * FROM official_info WHERE official_username='" . $username . "'";

    $stmt = $userObj->runQuery($query);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $response = '<div style="text-align:center" class = "alert alert-success alert-dismissible fade show col-md-6 offset-md-3" role="alert">
                    Username is Available
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>                               
                </div>';

    if ($stmt->rowCount() > 0) {
        $response = '<div style="text-align:center" class = "alert alert-danger alert-dismissible fade show col-md-6 offset-md-3" role="alert">
                        Username Already Taken
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>                               
                    </div>';
    }

    echo $response;
    die;
}

//Ajax Call for Disabling Submit button when Resident Username Already Exists
if (isset($_POST['username_verify_official_button'])) {

    $username = $_POST['username_verify_official_button'];

    $query = "SELECT * FROM official_info WHERE official_username='" . $username . "'";

    $stmt = $userObj->runQuery($query);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $response = 0;

    if ($stmt->rowCount() > 0) {
        $response = 1;
    }

    echo $response;
    die;
}

//Ajax Call for Login
if (isset($_POST['current_username']) && isset($_POST['current_password'])) {
    session_start();

    $username = $_POST['current_username'];
    $password = $_POST['current_password'];

    $admin_query = "SELECT * FROM official_info WHERE official_username = '$username' and official_password = '$password' ";
    $admin_stmt = $userObj->runQuery($admin_query);
    $admin_result = $admin_stmt->execute();

    $user_query = "SELECT * FROM resident_info WHERE username = '$username' and password = '$password' ";
    $user_stmt = $userObj->runQuery($user_query);
    $user_result = $user_stmt->execute();

    if ($admin_result and $user_result) {

        if ($admin_stmt->rowCount() == 1) {
            $user = $admin_stmt->fetch(PDO::FETCH_ASSOC);
            $_SESSION['session_login'] = $user;

            echo '0';
        } elseif ($user_stmt->rowCount() == 1) {
            $user = $user_stmt->fetch(PDO::FETCH_ASSOC);
            $_SESSION['session_login'] = $user;

            echo '1';
        } else {
            echo 'Account Not Found';
        }

    } else {
        echo 'Error';
    }


}

//Function for ajax call from documents delete modal to fetch documents information from database
if (isset($_POST['delete_load_document'])) {
    $id = $_POST['delete_load_document'];

    $document_arr = array();

    $qry = "SELECT * from documents WHERE id ='$id'";

    $stmt = $userObj->runQuery($qry);
    $result = $stmt->execute();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

        $document_title = $row['name'];
        $document_body = $row['description'];
        $document_date_time = $row['upload_date_time'];

        $document_arr[] = array
        (
            "document_title" => $document_title,
            "document_body" => $document_body,
            "document_date_time" => $document_date_time
        );

    }

    echo json_encode($document_arr);

}


// End of Ajax Calls






?>