<?php

    // Include configuration and database connection files first
    require_once(__DIR__ . '/../../Config/config.inc.php'); // session configurations and session_start
    require_once(__DIR__ . '/../DBConn.inc.php'); // database connection setup
    require_once "signup_model.inc.php";
    require_once "signup_contr.inc.php";

    // Get the database connection instance
    $pdo = DatabaseConnection::getInstance()->getConnection();

    // Handle POST request
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $fName = $_POST["firstname"];
        $lName = $_POST["lastname"];
        $email = $_POST["email"];
        $phone = $_POST["phone"];
        $addr = $_POST["address"];
        $pwd = $_POST["password"];

        try {
            // Array to store error messages
            $errors = [];

            // Error handlers
            if (input_empty($fName, $lName, $email, $addr, $pwd)) {
                $errors["empty_input"] = "Fill in all fields";
            }

            /* Uncomment and use if email validation is needed
            if (email_validate($email)) {
                $errors["invalid_email"] = "Invalid email used";
            }
            */

            if (username_email_taken($pdo, $email)) {
                $errors["email_taken"] = "Email already exists";
            }

            if (check_password_characters($pwd)) {
                $errors["character_pattern"] = "Password should contain 8 characters or more (e.g., fgM7hdfug@)";
            }

            // Check if there are errors
            if ($errors) {
                $_SESSION["errors_signup"] = $errors;

                $signupData = [
                    "firstName" => $fName,
                    "lastName"  => $lName,
                    "email"     => $email,
                    "phone"     => $phone,
                    "address"   => $addr,
                ];

                $_SESSION["signup_data"] = $signupData;

                // Redirect to registration page with errors
                header("Location: ../../registration.php");
                exit();
            }

            // Add new customer to the database
            new_customers($pdo, $fName, $lName, $email, $phone, $addr, $pwd);

            // Redirect to login page after successful signup
            header("Location: ../../login.php");
            exit();

        } catch (PDOException $e) {
            die("Query Failed: " . $e->getMessage());
        }
    } else {
        // Redirect to registration page if not a POST request
        header("Location: ../../registration.php");
        exit();
    }
