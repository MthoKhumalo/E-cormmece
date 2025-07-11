<?php
    require_once('Config/config.inc.php');
    require_once('database/logins/login_view.inc.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Header</title>
    <link rel="stylesheet" href="CSS/style.css">
</head>
<body>

<div id="nav_container">
<nav class="navbar">
    <header>

        <div class="logo">
          <a href="Home.php"><img src="Images/ComputerComplex_pics/Logo.jpeg" width="200" height="100" alt="LOGO"></a>
          <span class="word-computer">Computer</span>
          <span class="word-complex">Complex</span>
        </div>

        <ul class="nav-links">

          <li><a href="home.php">HOME</a></li>

          <li><a href="products.php">PRODUCTS</a></li>

          <li class="service_dropdown">
            <a href="services.php">SERVICES</a>
              <?php if(isset($_SESSION["user_id"])){?>
                <ul class="dropdown-menu">

                  <li><a href="repairs.php">Repairs</a></li>
                  <li><a href="sell.php">Sell Products</a></li>
                  <li><a href="booking.php">Internet Session</a></li>
                  
                </ul>
              <?php } ?>
          </li>

          <li><a href="About.php">ABOUT</a></li>

        </ul>
      
        <div class="cart-icon">
          <?php if(isset($_SESSION["user_id"])){ 
              output_username(); ?>
              <a href="cart.php" class="cart-link"><img src="Images/cart.png" alt="Shopping Cart"></a>
              <div class="profile-dropdown">
                <img src="Images/profile.png" alt="Profile" class="profile-icon">
                <div class="dropdown-content">
                  <a href="profile.php">Profile</a>
                  <a href="logout.php">Logout</a>
                </div>
              </div>
          <?php } else { 
              output_username(); ?>
              <div class="profile-dropdown">
                <img src="Images/profile.png" alt="Profile" class="profile-icon">
                <div class="dropdown-content">
                  <a href="Registration.php">Sign-Up</a>
                  <a href="Login.php">Log-In</a>
                </div>
              </div>
          <?php } ?>
        </div>

    </header>
</nav>
</div>

</body>
</html>