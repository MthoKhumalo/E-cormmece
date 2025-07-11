<?php

    require_once('Config/config.inc.php');
    require_once('database/homeProduct/product_view.inc.php');
    
?>

<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Home</title>
        <link rel="stylesheet" href="CSS/style.css">
    </head>

    <body>
        
        <!--header-->
        <?php  include 'Header.php'; ?>
        <br>

        <div class="home-content">
        <div class="home-background"></div> <!-- Background image with blur -->
            <h1>Welcome to Computer Complex!</h1>
            <h2>Your Hub for All Things Digital!</h2>
            <h3><p>We are your g-to destination for all things technology. Whether you need relaible internet 
                access, expert computer and printer repairs, or high-quality parts for your deices, we`r got you covered!
            </p></h3>
        </div>

        <br>
        <!--content-->
        <?php include 'database/homeProduct/addHome.inc.php' ?>

        <!--footer-->
        <?php include 'Footer.php'?>

    </body>
</html>