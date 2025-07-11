<?php
    require_once('Config/config.inc.php');
    require_once('database/logins/login_view.inc.php');
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <title> Logging-In </title>
        <link rel = "stylesheet" href = 'CSS/style.css'>                
    </head>

    <body>

        <!--header-->
        <?php  include 'Header.php'; ?>

        <!--form-->
        <div class="forms">
            <form action="database/logins/login.inc.php" method="POST">

                <h2>Log-In</h2> <br><br>

                <div>
                    <input type="email" id="email" name="email" placeholder="Email">
                </div> <br>

                <div>
                    <input type="password" id="password" name="password" placeholder="Password">
                </div> <br>

                <button type="submit">LOG-IN</button>

                <!--Error handler messages-->
                <?php
                    check_login_errors();
                ?>

            </form>

        </div>

        <!--footer-->
        <?php include 'Footer.php'?>
    </body>
</html>