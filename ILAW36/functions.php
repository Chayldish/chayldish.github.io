<?php

    require_once ('dbConfig.php');

    class User
    {
        private $conn;

        //Function to construct database
        public function __construct()
        {
            $database = new Database();
            $db = $database->dbConnection();
            $this->conn = $db;
        }
    
        //Run query that is passed on parameter
        public function runQuery($sql)
        {
            $stmt = $this->conn->prepare($sql);
            return $stmt;
        }

        //Redirect to url that is passed on parameter
        public function redirect($url)
        {
            header("Location: $url");
        }   

        public function add_resident($first_name, $middle_name, $last_name, $suffix, $birthday, $alias, $sex, $civil_stat, $mobile_no, $email, $religion, $voter_stat, $username, $password, $fathers_last_name, $mothers_last_name, $mothers_first_name, $num_children, $purok, $barangay, $submission_date) {
            try {
                // Extract the last two digits of the year from the submission_date
                $year = date('y', strtotime($submission_date));
        
                // Query to get the last resident ID from the database
                $stmt = $this->conn->prepare("SELECT resident_id FROM resident_info ORDER BY resident_id DESC LIMIT 1");
                $stmt->execute();
                $last_resident = $stmt->fetch(PDO::FETCH_ASSOC);
        
                // Extract the numeric part of the last resident_id and increment it
                if (!$last_resident) {
                    // Start from 1 if no previous resident_id exists
                    $increment_id = 1;
                } else {
                    // Extract the number part of the last resident_id (after the hyphen) and increment it
                    $last_id_number = (int)substr($last_resident['resident_id'], -2);
                    $increment_id = $last_id_number + 1;
                }
        
                // Generate the new resident_id using the extracted year and incremented number
                $resident_id = $year . '-' . str_pad($increment_id, 2, '0', STR_PAD_LEFT);
        
                // Prepare the SQL statement with the new resident_id
                $stmt = $this->conn->prepare(
                    "INSERT INTO resident_info (resident_id, first_name, middle_name, last_name, suffix, birthday, alias, sex, civil_stat, mobile_no, email, religion, voter_stat, username, password, fathers_last_name, mothers_last_name, mothers_first_name, num_children, purok, barangay, submission_date) 
                    VALUES (:resident_id, :first_name, :middle_name, :last_name, :suffix, :birthday, :alias, :sex, :civil_stat, :mobile_no, :email, :religion, :voter_stat, :username, :password, :fathers_last_name, :mothers_last_name, :mothers_first_name, :num_children, :purok, :barangay, :submission_date)"
                );
        
                // Bind the parameters
                $stmt->bindParam(":resident_id", $resident_id);
                $stmt->bindParam(":first_name", $first_name);
                $stmt->bindParam(":middle_name", $middle_name);
                $stmt->bindParam(":last_name", $last_name);
                $stmt->bindParam(":suffix", $suffix);
                $stmt->bindParam(":birthday", $birthday);
                $stmt->bindParam(":alias", $alias);
                $stmt->bindParam(":sex", $sex);
                $stmt->bindParam(":civil_stat", $civil_stat);
                $stmt->bindParam(":mobile_no", $mobile_no);
                $stmt->bindParam(":email", $email);
                $stmt->bindParam(":religion", $religion);
                $stmt->bindParam(":voter_stat", $voter_stat);
                $stmt->bindParam(":username", $username);
                $stmt->bindParam(":password", $password);
                $stmt->bindParam(":fathers_last_name", $fathers_last_name);
                $stmt->bindParam(":mothers_last_name", $mothers_last_name);
                $stmt->bindParam(":mothers_first_name", $mothers_first_name);
                $stmt->bindParam(":num_children", $num_children);
                $stmt->bindParam(":purok", $purok); 
                $stmt->bindParam(":barangay", $barangay);
                $stmt->bindParam(":submission_date", $submission_date);
        
                // Execute the statement
                $stmt->execute();
        
                return $stmt;
            } catch (PDOException $e) {
                echo $e->getMessage();
                return false;
            }
        }
        
        

        // Function to add official info supplied from parameter
public function add_official($official_position, $official_first_name, $official_middle_name, $official_last_name, $official_sex, $official_contact_info, $official_email, $official_year, $official_username, $official_password) {
    try {
        $stmt = $this->conn->prepare(
            "INSERT INTO official_info (official_position, official_first_name, official_middle_name, official_last_name, official_sex, official_contact_info, official_email, official_year, official_username, official_password) 
            VALUES (:official_position, :official_first_name, :official_middle_name, :official_last_name, :official_sex, :official_contact_info, :official_email, :official_year, :official_username, :official_password)"
        );
        $stmt->bindParam(":official_position", $official_position);
        $stmt->bindParam(":official_first_name", $official_first_name);
        $stmt->bindParam(":official_middle_name", $official_middle_name);
        $stmt->bindParam(":official_last_name", $official_last_name);
        $stmt->bindParam(":official_sex", $official_sex);
        $stmt->bindParam(":official_contact_info", $official_contact_info);
        $stmt->bindParam(":official_email", $official_email); // Bind the email parameter
        $stmt->bindParam(":official_year", $official_year);
        $stmt->bindParam(":official_username", $official_username);
        $stmt->bindParam(":official_password", $official_password);
        $stmt->execute();

        return $stmt;
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}


       // Function to add a post to the database with an optional photo
public function add_post($post_title, $post_body, $post_date_time, $post_photo = null)
{
    try {
        $query = "INSERT INTO announcement_post (post_title, post_body, post_date_time, post_photo) 
                  VALUES (:post_title, :post_body, :post_date_time, :post_photo)";
        
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(":post_title", $post_title);
        $stmt->bindParam(":post_body", $post_body);
        $stmt->bindParam(":post_date_time", $post_date_time);
        $stmt->bindParam(":post_photo", $post_photo);
        
        $stmt->execute();
        
        return $stmt; // Return statement on successful execution
    } catch (PDOException $e) {
        echo $e->getMessage();
        return false; // Return false on failure
    }
}


        public function edit_resident($resident_id, $first_name, $middle_name, $last_name, $suffix, $birthday, $alias, $sex, $civil_stat, $mobile_no, $email, $religion, $voter_stat, $username, $password, $fathers_last_name, $mothers_last_name, $mothers_first_name, $num_children, $purok, $barangay, $stat) {
            try {
                $stmt = $this->conn->prepare(
                    "UPDATE resident_info 
                    SET first_name = :first_name, middle_name = :middle_name, last_name = :last_name,
                        suffix = :suffix, birthday = :birthday, alias = :alias,
                        sex = :sex, civil_stat = :civil_stat, mobile_no = :mobile_no,
                        email = :email, religion = :religion, voter_stat = :voter_stat,
                        username = :username, password = :password, fathers_last_name = :fathers_last_name, 
                        mothers_last_name = :mothers_last_name, mothers_first_name = :mothers_first_name, 
                        num_children = :num_children, purok = :purok, barangay = :barangay, stat = :stat
                    WHERE resident_id = :resident_id"
                );
        
                $stmt->bindParam(":resident_id", $resident_id);
                $stmt->bindParam(":first_name", $first_name);
                $stmt->bindParam(":middle_name", $middle_name);
                $stmt->bindParam(":last_name", $last_name);
                $stmt->bindParam(":suffix", $suffix);
                $stmt->bindParam(":birthday", $birthday);
                $stmt->bindParam(":alias", $alias);
                $stmt->bindParam(":sex", $sex);
                $stmt->bindParam(":civil_stat", $civil_stat);
                $stmt->bindParam(":mobile_no", $mobile_no);
                $stmt->bindParam(":email", $email);
                $stmt->bindParam(":religion", $religion);
                $stmt->bindParam(":voter_stat", $voter_stat);
                $stmt->bindParam(":username", $username);
                $stmt->bindParam(":password", $password);
                $stmt->bindParam(":fathers_last_name", $fathers_last_name);
                $stmt->bindParam(":mothers_last_name", $mothers_last_name);
                $stmt->bindParam(":mothers_first_name", $mothers_first_name);
                $stmt->bindParam(":num_children", $num_children);
                $stmt->bindParam(":purok", $purok); // Bind the new parameter for Purok
                $stmt->bindParam(":barangay", $barangay);
                $stmt->bindParam(":stat", $stat);
                $stmt->execute();
                
                return $stmt;
            } catch (PDOException $e) {
                echo $e->getMessage();
            }
        }
        
        // Function to edit official info supplied from parameter
public function edit_official($official_id, $official_position, $official_first_name, $official_middle_name, $official_last_name, $official_sex, $official_contact_info, $official_email, $official_year, $official_username, $official_password) {
    try {
        $stmt = $this->conn->prepare(
            "UPDATE official_info 
             SET official_position = :official_position, 
                 official_first_name = :official_first_name, 
                 official_middle_name = :official_middle_name,
                 official_last_name = :official_last_name, 
                 official_sex = :official_sex, 
                 official_contact_info = :official_contact_info,
                 official_email = :official_email,  -- Include the email field
                 official_year = :official_year,
                 official_username = :official_username, 
                 official_password = :official_password
             WHERE official_id = :official_id"
        );
        $stmt->bindParam(":official_id", $official_id);
        $stmt->bindParam(":official_position", $official_position);
        $stmt->bindParam(":official_first_name", $official_first_name);
        $stmt->bindParam(":official_middle_name", $official_middle_name);
        $stmt->bindParam(":official_last_name", $official_last_name);
        $stmt->bindParam(":official_sex", $official_sex);
        $stmt->bindParam(":official_contact_info", $official_contact_info);
        $stmt->bindParam(":official_email", $official_email); // Bind the email parameter
        $stmt->bindParam(":official_year", $official_year);
        $stmt->bindParam(":official_username", $official_username);
        $stmt->bindParam(":official_password", $official_password);
        
        $stmt->execute();
        return $stmt;
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}

public function edit_post($post_id, $post_title, $post_body, $post_date_time, $post_photo)
{
    try
    {
        $stmt = $this->conn->prepare(
            "UPDATE announcement_post 
             SET post_title = :post_title, 
                 post_body = :post_body, 
                 post_date_time = :post_date_time, 
                 post_photo = :post_photo
             WHERE post_id = :post_id"
        );

        $stmt->bindParam(":post_id", $post_id);
        $stmt->bindParam(":post_title", $post_title);
        $stmt->bindParam(":post_body", $post_body);
        $stmt->bindParam(":post_date_time", $post_date_time);
        $stmt->bindParam(":post_photo", $post_photo);

        $stmt->execute();
        return $stmt;
    }
    catch (PDOException $e)
    {
        echo $e->getMessage();
    }
}


        //Function to delete resident info supplied from parameter
        public function delete_resident($resident_id)
        {
            try
            {
                $stmt = $this->conn->prepare("DELETE FROM resident_info WHERE resident_id = :resident_id");
                $stmt->bindparam(":resident_id", $resident_id);
                $stmt->execute();
                return $stmt;

            }
            catch(PDOException $e)
            {
                  echo $e->getMessage();
            }
        }

        //Function to delete official info supplied from parameter
        public function delete_official($official_id)
        {
            try
            {
                $stmt = $this->conn->prepare("DELETE FROM official_info WHERE official_id = :official_id");
                $stmt->bindparam(":official_id", $official_id);
                $stmt->execute();
                return $stmt;

            }
            catch(PDOException $e)
            {
                  echo $e->getMessage();
            }
        }

        //Function to delete post info supplied from parameter
        public function delete_post($post_id)
        {
            try
            {
                $stmt = $this->conn->prepare
                                    (
                                        "DELETE FROM announcement_post WHERE post_id = :post_id"
                                    );
                $stmt->bindParam(":post_id", $post_id);
                $stmt->execute();
                return $stmt;
            }
            catch(PDOException $e)
            {
                echo $e->getMessage();
            }
        }

        //Function to delete document info supplied from parameter
        public function delete_document($id)
        {
            try
            {
                $stmt = $this->conn->prepare
                                    (
                                        "DELETE FROM documents WHERE id = :id"
                                    );
                $stmt->bindParam(":id", $id);
                $stmt->execute();
                return $stmt;
            }
            catch(PDOException $e)
            {
                echo $e->getMessage();
            }
        }

        //Function to delete document file from directory info supplied from parameter
        public function delete_document_file($filename)
        {
            $path = 'documents/'.$filename;
            if(unlink($path))
            {
                return true;
            }
            else
            {
                return false;
            }
           
            
        }

// Function to upload transaction for resident_id
public function upload_transaction($resident_id, $last_name, $middle_name, $first_name, $description, $submission_date)
{
    try
    {
        // Prepare the SQL query to insert only the resident_id
        $stmt = $this->conn->prepare("INSERT INTO transaction 
                                                        VALUES ('', ?, ?, ?, ?, ?, ?)");
        // Bind the resident_id to the query
        $stmt->bindParam(1, $resident_id);
        $stmt->bindParam(2, $last_name);
        $stmt->bindParam(3, $middle_name);
        $stmt->bindParam(4, $first_name);
        $stmt->bindParam(5, $description);
        $stmt->bindParam(6, $submission_date);

        // Execute the query
        $stmt->execute();

        // Redirect after successful insertion
        $this->redirect('admin_Receipts.php?documentUploaded'); 
    }
    catch(PDOException $e)
    {
        // Handle any errors
        echo $e->getMessage();
    }
}


        //Function to upload photo
        public function upload_document($file, $destination, $filename, $directory, $resident_id, $submission_date, $last_name, $middle_name, $first_name)
        {
            try
            {
                if (move_uploaded_file($file, $destination)) 
                {
                    $stmt = $this->conn->prepare("INSERT INTO uploads 
                                                        VALUES ('', ?, ?, ?, ?, ?, ?, ?)");
                    $stmt->bindParam(1, $filename);
                    $stmt->bindParam(2, $directory);
                    $stmt->bindParam(3, $resident_id);
                    $stmt->bindParam(4, $last_name);
                    $stmt->bindParam(5, $middle_name);
                    $stmt->bindParam(6, $first_name);
                    $stmt->bindParam(7, $submission_date);
                    $stmt->execute();
                    $this->redirect('user_Home.php?documentUploaded'); 
                } 
                else 
                {
                    $this->redirect('user_Home.php?documentUploadFailed'); 
                }
            }
            catch(PDOException $e)
            {
                echo $e->getMessage();
            }
        }
        

       //Function to upload documents
       public function upload_documents($file, $destination, $filename,  $description, $date_time, $directory)
       {
           try
           {
               if (move_uploaded_file($file, $destination)) 
               {
                   $stmt = $this->conn->prepare("INSERT INTO documents 
                                                       VALUES ('', ?, ?, ?, ? )");
                   $stmt->bindParam(1, $filename);
                   $stmt->bindParam(2, $description);
                   $stmt->bindParam(3, $date_time);
                   $stmt->bindParam(4, $directory);
                   $stmt->execute();
                   $this->redirect('admin_Documents.php?documentUploaded'); 
               } 
               else 
               {
                   $this->redirect('admin_Documents.php?documentUploadFailed'); 
               }
           }
           catch(PDOException $e)
           {
               echo $e->getMessage();
           }
       }

        
        



    }


?>