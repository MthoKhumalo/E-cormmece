<!DOCTYPE html>
<html lang="en">

    <head>
    <title> PRODUCTS </title>
        <link rel = "stylesheet" href = 'CSS/style.css'>     
        
        <!--  jQuery library -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="Javascript/cart.js"></script>
        
    </head>

    <body>
        
        <!--header-->
        <?php  include 'Header.php'; ?>

        <div>
            <ul>
                <?php include 'searching.php' ?> <br>
            </ul>
        </div>

        <!--content-->
        <?php
            if (isset($_SESSION["search_results"])) {

                $products = $_SESSION["search_results"];
                search_products(products: $products);
                unset($_SESSION["search_results"]);

            }else{
                
                check_search_errors();
                include 'database/product.inc.php';
            }
        ?>
        
        <!--footer-->
        <?php include 'Footer.php'?>

    </body>  
</html>