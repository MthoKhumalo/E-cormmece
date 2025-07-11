<?php

    declare (strict_types= 1); // type declaration set true

    function input_empty(string $fName, string $lName, string $email, string $addr, string $dep, string $pwd){
        if(empty($fName) || empty($lName) || empty($email) || empty($addr) || empty($dep) || empty($pwd)){    

            return true;

        }else{

            return false;
        }
    }

    /*function email_validate(string $email){
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)){    
            return true;
        }else{
            return false;
        }
    }*/

    function username_email_taken(object $pdo, string $email){

        if(get_username_email($pdo, $email)){    

            return true;

        }else{

            return false;
        }
    }

    function check_password_characters(string $pwd) {
        // Checking for uppercase and lowercase
        if (!preg_match("/[A-Z]/", $pwd) || !preg_match("/[a-z]/", $pwd)) {

            return true;
        }
    
        // Checking if the password has at least 8 characters
        if (strlen($pwd) < 8) {

            return true;
        }
    
        // Checking for a non-character value
        if (!preg_match("/[A-Za-z]/", $pwd)) {

            return true;
        }
    
        return false;
    }

    function new_customers(object $pdo, string $fName, string $lName, string $email, int $phone, string $addr, string $dep, string $pwd){
        
        set_customer($pdo, $fName, $lName, $email, $phone, $addr, $dep, $pwd);
    }