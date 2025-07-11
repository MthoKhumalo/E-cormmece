<!DOCTYPE html>
<html lang="en">

    <head>
        <title> SERVICES </title>
        <link rel = "stylesheet" href = 'CSS/style.css'>                
    </head>

    <body>
        <!--header-->
        <?php  include 'Header.php'; ?>
        <br>
        <h2 style="padding-left: 50px">SERVICES</h2>
        <!--content-->
        <div class="body">
            <div class="service-options">
                
                    <!-- First Service -->
                <?php if(isset($_SESSION["user_id"])){?>
                    <div class="service-option">
                        <a href="repairs.php">
                            <img src="Images/service/repair.jpg" alt="Service 1">
                        </a>

                        <h2>Repairs</h2>
                        <p>
                            <ul>
                                <li>Phones</li>
                                <li>Tablets</li>
                                <li>Laptops</li>
                                <li>Cameras</li>
                                <li>Monitors</li>
                                <li>Hard Drives</li>
                                <li>PC/Laptop Ports</li>
                            </ul>
                        </p>
                    </div>
                <?php }else{ ?>

                    <div class="service-option">
                        <img src="Images/service/repair.jpg" alt="Service 1">

                        <h2>Repairs</h2>
                        <p>
                            <ul>
                                <li>Phones</li>
                                <li>Tablets</li>
                                <li>Laptops</li>
                                <li>Cameras</li>
                                <li>Monitors</li>
                                <li>Hard Drives</li>
                                <li>PC/Laptop Ports</li>
                            </ul>
                        </p>

                    </div>
                <?php } ?>

                    <!-- SECOND SERVICE -->
                <?php if(isset($_SESSION["user_id"])){?>
                    <div class="service-option">
                    
                        <a href="sell.php">
                            <img src="Images/service/sell.jpg" alt="Service 2">
                        </a>

                        <h2>Sell Products</h2>
                        <p>
                            <ul>
                                <li>We buy new/old productes :</li>
                                <li>Laptops</li>
                                <li>Drivers</li>
                                <li>Monitors</li>
                                <li>Motherboards</li>
                            </ul>
                        </p>
                    </div>
                <?php }else{ ?>
                    <div class="service-option">
                    
                        <img src="Images/service/sell.jpg" alt="Service 2">

                        <h2>Sell Products</h2>
                        <p>
                            <ul>
                                <li>We buy new/old productes :</li>
                                <li>Laptops</li>
                                <li>Drivers</li>
                                <li>Monitors</li>
                                <li>Motherboards</li>
                            </ul>
                        </p>
                    </div>
                <?php } ?>
                
                    <!-- THIRD SERVICE -->
                <?php if(isset($_SESSION["user_id"])){?>
                    <div class="service-option">

                        <a 
                        >
                            <img src="Images/service/intenert.jpg" alt="Service 3">
                        </a>

                        <h2>Internet Session</h2>
                        <p>
                            <ul>
                                <li>Book a session with us:</li><br>
                                <li>Comfortable sitting desk</li>
                                <li>We have reliable, speedy internet</li>
                                <li>AT A CHEAP PRICE!!</li>
                            </ul>
                        </p>
                    </div>
                <?php }else{ ?>
                    <div class="service-option">
                        
                        <img src="Images/service/intenert.jpg" alt="Service 3">

                        <h2>Internet Session</h2>
                        <p>
                            <ul>
                                <li>Book a session with us:</li><br>
                                <li>Comfortable sitting desk</li>
                                <li>We have reliable, speedy internet</li>
                                <li>AT A CHEAP PRICE!!</li>
                            </ul>
                        </p>
                    </div>
                <?php } ?>
                
            </div>
        </div>

        <!--footer-->
        <?php include 'Footer.php'?>
    </body>
</html>