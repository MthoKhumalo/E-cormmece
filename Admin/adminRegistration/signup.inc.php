<?php

    if($_SERVER["REQUEST_METHOD"] === "POST"){

        $fName = $_POST["firstname"];
        $lName = $_POST["lastname"];
        $email = $_POST["email"];
        $phone = $_POST["phone"];
        $addr = $_POST["address"];
        $dep = $_POST["department"];
        $pwd = $_POST["password"];

        try{

            require_once "../../database/DBConn.inc.php";
            require_once "signup_model.inc.php";
            require_once "signup_contr.inc.php";

            // Get the database connection instance
            $pdo = DatabaseConnection::getInstance()->getConnection();

            // Array to store error messages for a specific function
            $errors = [];

            //error handlers
            if(input_empty($fName, $lName, $email, $addr, $dep, $pwd)){

                $errors["empty_input"] = "Fill in all fields";
            }

            /*if(email_validate($email)){
                $errors["invalid_email"] = "Invalid email used";
            }*/

            if(username_email_taken($pdo, $email)){

                $errors["email_taken"] = "Email already exists";
            }

            if (check_password_characters($pwd)) {

                $errors["character_pattern"] = "Password should contain 8 characters or more (e.g., fgM7hdfug@)";
            }

            // Call our session
            require_once(__DIR__ . '/../../Config/config.inc.php');

            // Check if the are errors inside the array
            if($errors){

                $_SESSION["errors_signup"] = $errors;

                $signupData = [

                    "firstName" => $fName,
                    "lastName"  => $lName,
                    "email" => $email,
                    "phone" => $phone,
                    "address" => $addr,
                    "department" => $dep

                ];

                $_SESSION["signup_data"] = $signupData;

                header("Location: ../registration.php");
                die();
            }
                
            //new customers addede to database
            new_customers($pdo, $fName, $lName, $email, $phone, $addr, $dep, $pwd);
                
            $pdo = null;
            $stmt = null;

            header("Location: ../../login.php");
            die();

        }catch(PDOException $e){

            die("Query Failed: ".$e->getMessage());
        }
    }else{
        
        header("Location: ../registration.php");
    }