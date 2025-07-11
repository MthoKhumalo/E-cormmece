<?php

    require_once('../Config/config.inc.php');
    require_once('adminRegistration/signup_view.inc.php');

?>

<!DOCTYPE html>
<html lang="en">
    
    <head>

        <title> Register </title>
        <link rel = "stylesheet" href = '../CSS/style.css'>

        <script>

            function validatePhoneNumber() {

                var phoneNumber = document.getElementById("phone").value;

                if (isNaN(phoneNumber)) {

                    alert("Phone number must be a number.");
                    return false;

                }

                if (phoneNumber.length !== 10) {

                    alert("Phone number must have exactly 10 digits.");
                    return false;

                }

                return true;
            }

        </script>         

    </head>

    <body>

        <!--header-->
        <?php  include 'adminHeader.php'; ?>

        <!--form-->
        <div class="forms">

            <form action="adminRegistration/signup.inc.php" method="POST" onsubmit="return validatePhoneNumber()">

                <h2>SIGN-UP</h2> <br><br>

                <?php signup_inputs() ?>
                
                <button type="submit">Register</button>

                <!--Error handlers messages-->
                <?php
                    check_signup_error();
                ?>

            </form>          
              
        </div>

    </body>
</html>