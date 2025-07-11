<?php

    declare (strict_types= 1); // type declaration set true

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
            $_SESSION["signup_data"]["firstName"] . '">';

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
                $_SESSION["signup_data"]["email"] . '" required>';
                
        } else {

            echo '<input type="email" id="email" name="email" placeholder="Email" required>';
        }

        // phone session
        if (isset($_SESSION["signup_data"]["phone"])) {

            echo '<input type="text" id="phone" name="phone" maxlength="10" pattern="[0-9]{10}" 
                placeholder="Contact" value="' . $_SESSION["signup_data"]["phone"] . '">';

        } else {

            echo '<input type="text" id="phone" name="phone" maxlength="10" pattern="[0-9]{10}" 
                placeholder="Contact" required>';

        }

        // address session
        if (isset($_SESSION["signup_data"]["address"])) {

            echo '<input type="text" id="address" name="address" placeholder="Address" value="' . 
                $_SESSION["signup_data"]["address"] . '">';

        } else {
            
            echo '<input type="text" id="address" name="address" placeholder="Address">';
        }

        // department options
        echo "   
        <label for='department'>Department:</label>
        <select id='department' name='department'>
            <option value='Manager'>Manager</option>
            <option value='Reception'>Reception</option>
        </select>";

        // password input
        echo '<input type="password" id="password" name="password" placeholder="Password">';
    }