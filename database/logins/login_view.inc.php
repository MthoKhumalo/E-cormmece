<?php

    declare (strict_types= 1); // type declaration set true

    function output_username(){

        if(isset($_SESSION["user_id"])){

            echo "<p>WELCOME : ". $_SESSION["user_name"]. "</p>";

        }else{

            echo "<p>LOGIN</p>";
        }
    }

    function check_login_errors(){

        if(isset($_SESSION["errors_login"])){

            $errors = $_SESSION["errors_login"];

            echo "<br>";

            foreach ($errors as $error){

                echo "<div class='error-message'>
                      <span class='close' onclick=\"this.parentElement.style.display='none';\">&times;
                      </span>" . htmlspecialchars($error) . "</div>" ;
            }

            unset($_SESSION["errors_login"]);

        }else if(isset($_GET['login']) && $_GET['login'] == "success"){
            
            echo '<br>';
            echo "<p>Logged sucessfully</p>";
        }
    }