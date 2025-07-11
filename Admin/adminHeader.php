<?php 
    require_once('../Config/config.inc.php');
    require_once('../database/logins/login_view.inc.php');
?>

<!DOCTYPE html>
<html lang="en">
  
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Header</title>
    <link rel="stylesheet" href="../CSS/admin_style.css">
</head>


<body>

    <header>

        <div class="logo">
            <a href="admin_product.php"><img src="../Images/ComputerComplex_pics/Logo.jpeg" width="200" height="100" alt="LOGO"></a> 
            <span class="word-computer">Computer</span>
          <span class="word-complex">Complex</span>
        </div>

        <nav class="navbar2">
            <ul>
                <li>REQUESTS
                    <div class="dropdown-content">
                        <a href="sell_view.php">SELL</a>
                        <a href="repairs_view.php">REPAIR</a>
                        <a href="bookings.php">BOOKING</a>
                    </div>
                </li>

                <li>REVIEW
                    <div class="dropdown-content">
                        <?php
                        // Restrict access based on department
                        if (isset($_SESSION["department"]) && ($_SESSION["department"] === 'Owner' || $_SESSION["department"] === 'Manager')) { ?>
                            <a href="sell_reviews.php">SELL</a>
                        <?php } ?>
                        <a href="repairs_review.php">REPAIR</a>
                        <a href="booking_review.php">BOOKING</a>
                    </div>
                </li>
                
                <?php
                // Restrict access based on department
                if (isset($_SESSION["department"]) && ($_SESSION["department"] === 'Owner' || $_SESSION["department"] === 'Manager')) { ?>
                    <li>PRODUCT
                        <div class="dropdown-content">
                            <a href="addProducts.php">ADD PRODUCTS</a>
                            <a href="admin_product.php">UPDATE PRODUCTS</a>
                        </div>
                    </li> 

                    <li>RECORDS
                        <div class="dropdown-content">
                            <a href="records_products_bought.php">SELL RECORDS</a>
                            <a href="records_repairs.php">REPAIR RECORDS</a>
                            <a href="records_bookings.php">BOOKING RECORDS</a>
                        </div>
                    </li>
                <?php } ?>

                <li><a href="order_reports.php">ORDERS</a></li>
            </ul>
        </nav>

                  
            <ul>
                <li class="cart">
                    <div class="icon">
                        <img src="../Images/profile.png" alt="Login">
                        <div class="dropdown-content">
                            <a href="Registration.php">Sign-Up</a>
                            <a href="../logout.php">Logout</a>
                        </div>
                    </div>
                </li>
            </ul>
        
        
    </header>
</body>
</html>