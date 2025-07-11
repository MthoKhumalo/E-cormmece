<?php

    // Include configuration and database connection files first
    require_once(__DIR__ . '/../../Config/config.inc.php'); // session configurations and session_start
    require_once(__DIR__ . '/../DBConn.inc.php'); // database connection setup
    require_once "login_model.inc.php";
    require_once "login_contr.inc.php";

    // Get the database connection instance
    $pdo = DatabaseConnection::getInstance()->getConnection();

    if($_SERVER["REQUEST_METHOD"] === "POST"){

        $email = $_POST["email"];
        $pwd = $_POST["password"];

        try{

            $errors = [];
            //error handlers
            if(is_input_empty($email, $pwd)){

                $errors["empty_input"] = "Fill in all fields";
            }
            
            $result = get_user($pdo, $email, 'admins');

            if($result){

                $userRole = 'admin';
                $department = $result['dep'];

            }else{
                
                $result = get_user($pdo, $email, 'customers');
                $userRole = 'customer';
            }
            

            if(is_username_wrong($result)){    
                
                $errors["wrong_email"] =  "Incorrect email";
            }
            
            if(!is_username_wrong($result) && is_password_wrong($pwd, $result['pwrd'])){    
                $errors["wrong_password"] = "Incorrect password!";
            }

            // Check if the are errors inside the array
            if($errors){

                $_SESSION["errors_login"] = $errors;
                
                header("Location: ../../login.php");
                die();
            }

            // Check user role and redirect accordingly
            if ($userRole === 'admin') {

                // Append the user id with session id
                $newSessionId = session_create_id();
                $sessionId = $newSessionId . "_" . htmlspecialchars($result["admin_id"]);
                session_id($sessionId);

                $_SESSION["user_id"] = $result["admin_id"];
                $_SESSION["user_name"] = htmlspecialchars($result["firstName"]);
                $_SESSION["user_role"] = $userRole;
                $_SESSION["department"] = $department;  
                $_SESSION["last_regeneration"] = time(); //Reset the time

                if (isset($_SESSION["department"]) && $_SESSION["department"] === 'Owner' || $_SESSION["department"] === 'Manager') {

                    header("Location: ../../Admin/admin_product.php?login=success");

                }else{

                    header("Location: ../../Admin/booking_review.php?login=success");
                }

            } else {

                // Append the user id with session id
                $newSessionId = session_create_id();
                $sessionId = $newSessionId . "_" . htmlspecialchars($result["customer_id"]);
                session_id($sessionId);

                $_SESSION["user_id"] = $result["customer_id"];
                $_SESSION["user_name"] = htmlspecialchars($result["firstName"]);
                $_SESSION["user_role"] = $userRole;
                $_SESSION["last_regeneration"] = time(); //Reset the time
                header("Location: ../../home.php?login=success");
                
            }

            $pdo = null;
            $stmt = null;
            die();

        }catch(PDOException $e){

            die("Query Failed: ".$e->getMessage());
        }

    }else{
        
        header("Location: ../../login.php");

    }