<?php

    declare(strict_types=1); // type declaration set true
    require_once(__DIR__ . '/../../Config/config.inc.php'); // session configurations and session_start


    function check_signup_error(){

        if(isset($_SESSION["errors_signup"])){

            $errors = $_SESSION["errors_signup"];

            echo "<br>";

            foreach ($errors as $error){

                echo "<div class='error-message'>
                      <span class='close' onclick=\"this.parentElement.style.display='none';\">&times;
                      </span>" . htmlspecialchars($error) . "</div>" ;
            }

            unset($_SESSION["errors_signup"]);
        }
    }


    function signup_inputs(){
        
        // firstName session
        if (isset($_SESSION["signup_data"]["firstName"])) {

            echo '<input type="text" id="firstname" name="firstname" placeholder="Name" value="' . 
            htmlspecialchars($_SESSION["signup_data"]["firstName"]?? '') . '">';

        }else {

             echo '<input type="text" id="firstname" name="firstname" placeholder="Name">';
        }

        // lastName session
        if (isset($_SESSION["signup_data"]["lastName"])) {

            echo '<input type="text" id="lastname" name="lastname" placeholder="Surname" value="' . 
                $_SESSION["signup_data"]["lastName"] . '">';

        } else {

            echo '<input type="text" id="lastname" name="lastname" placeholder="Surname">';
        }

        // email session
        if (isset($_SESSION["signup_data"]["email"]) && !isset($_SESSION["errors_signup"]["email_taken"])) {

            echo '<input type="email" id="email" name="email" placeholder="Email" value="' . 
                $_SESSION["signup_data"]["email"] . '">';

        } else {

            echo '<input type="email" id="email" name="email" placeholder="Email">';
        }

        // phone session
        if (isset($_SESSION["signup_data"]["phone"])) {

            echo '<input type="text" id="phone" name="phone" maxlength="10" pattern="[0-9]{10}" 
                placeholder="Contact" value="' . $_SESSION["signup_data"]["phone"] . '">';

        } else {

            echo '<input type="text" id="phone" name="phone" maxlength="10" pattern="[0-9]{10}" 
                placeholder="Contact">';
        }

        // address session
        if (isset($_SESSION["signup_data"]["address"])) {

            echo '<input type="text" id="address" name="address" placeholder="Address" value="' . 
                $_SESSION["signup_data"]["address"] . '">';

        } else {
            
            echo '<input type="text" id="address" name="address" placeholder="Address">';
        }
        
        echo '<input type="password" id="password" name="password" placeholder="Password">';
}

/*
declare(strict_types=1);

function check_signup_error() {
    if (isset($_SESSION["errors_signup"])) {
        $errors = $_SESSION["errors_signup"];

        echo "<br>";
        foreach ($errors as $error) {
            echo "<div class='error-message'>
                  <span class='close' onclick=\"this.parentElement.style.display='none';\">&times;</span>" . 
                  htmlspecialchars($error) . "</div>";
        }

        // Log errors for debugging
        error_log("Signup Errors: " . json_encode($errors));

        unset($_SESSION["errors_signup"]); // Clear error messages after displaying
    }
}

function signup_inputs() {
    $fields = [
        "firstName" => "Name",
        "lastName" => "Surname",
        "email" => "Email",
        "phone" => "Contact",
        "address" => "Address"
    ];

    foreach ($fields as $field => $placeholder) {
        $value = htmlspecialchars($_SESSION["signup_data"][$field] ?? '');
        $type = ($field === "email") ? "email" : "text";

        // Special handling for the phone field
        if ($field === "phone") {
            echo "<input type='tel' id='{$field}' name='{$field}' maxlength='10' pattern='[0-9]{10}' placeholder='{$placeholder}' value='{$value}'>";
        } else {
            echo "<input type='{$type}' id='{$field}' name='{$field}' placeholder='{$placeholder}' value='{$value}'>";
        }
    }

    // Password field (no session data should be retained for passwords)
    echo '<input type="password" id="password" name="password" placeholder="Password">';
}*/