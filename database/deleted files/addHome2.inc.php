<?php

    require_once ('DBConn.inc.php');
    require_once('Config/config.inc.php');
    require_once('logins/login_view.inc.php');

    try {

        // Fetch products from the database
        $stmt = $pdo->query("SELECT * FROM products WHERE is_deleted = 0");
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

    } catch (PDOException $e) {

        echo "Connection failed: " . $e->getMessage();
    }

    echo "<div class='category'>";

        echo "<h2>Products Under R 5,000</h2>";
        echo "<ul>";
        
            $lessThan5000Count = 0;

            foreach ($products as $product) {

                if ($product['price'] < 5000 && $lessThan5000Count < 5) {

                    echo "<div class='products'>";
                        echo "<div class='product'>";

                            // Display product image, name, description, price, and quantity
                            
                            $imageData = $product["images"];

                            if (preg_match('%^[a-zA-Z0-9/+]*={0,2}$%', $imageData)) {
                                
                            // Image is already base64-encoded, so no need to encode again
                                echo "<img src='data:image/jpeg;base64," . $imageData . "' alt='Image'>";
                                
                            } else {
                                
                            // Image is not base64-encoded, so encode it before displaying
                                echo "<img src='data:image/jpeg;base64," . base64_encode($imageData) . "' alt='Image'>";
                            }
                            echo "<p>" . htmlspecialchars(string: $product["pName"]). "<p>";
                            echo "<p>" . htmlspecialchars(string: $product["description"]). "<p>";
                            echo "<p>R " . htmlspecialchars(string: $product["price"]). "<p>";

                            if(isset($_SESSION["user_id"])){

                                echo "<form action='database/cart.inc.php' method='POST'>";

                                    echo "<input type='hidden' name='product_id' value='" . htmlspecialchars(string: $product["product_id"]) . "'>";
                                    echo '<input type="hidden" name="action" value="increase">';
                                    echo "<button class='add-to-cart' data-product-id='<?=" .htmlspecialchars(string: $product['product_id']). "?>'>Add To Cart</button>";

                                echo "</form>";

                            }

                        echo "</div>";
                    echo "</div>";

                    $lessThan5000Count++;
                }
            }
        
        echo "</ul>";
    echo "</div>";

    echo '<div class="category">';

        echo "<h2>Products Over R 10,000</h2>";

        echo "<ul>";
            
            $greaterThan10000Count = 0;

            foreach ($products as $product) {

                if ($product['price'] > 10000 && $greaterThan10000Count < 5) {

                    echo "<div class='products'>";
                        echo "<div class='product'>";

                        // Display product image, name, description, price, and quantity
                        $imageData = $product["images"];

                        if (preg_match('%^[a-zA-Z0-9/+]*={0,2}$%', $imageData)) {

                            // Image is already base64-encoded, so no need to encode again
                            echo "<img src='data:image/jpeg;base64," . $imageData . "' alt='Image'>";

                        } else {

                            // Image is not base64-encoded, so encode it before displaying
                            echo "<img src='data:image/jpeg;base64," . base64_encode($imageData) . "' alt='Image'>";
                        }
                            echo "<p>" . htmlspecialchars(string: $product["pName"]). "<p>";
                            echo "<p>" . htmlspecialchars(string: $product["description"]). "<p>"; //home
                            echo "<p>R " . htmlspecialchars(string: $product["price"]). "<p>";

                            if(isset($_SESSION["user_id"])){

                                echo "<form action='database/cart.inc.php' method='POST'>";

                                    echo "<input type='hidden' name='product_id' value='" . htmlspecialchars(string: $product["product_id"]) . "'>";
                                    echo '<input type="hidden" name="action" value="increase">';
                                    echo "<button class='add-to-cart' data-product-id='<?=" .htmlspecialchars(string: $product['product_id']). "?>'>Add To Cart</button>";

                                echo "</form>";

                            }

                        echo "</div>";
                    echo "</div>";

                    $greaterThan10000Count++;
                }
            }
            
        echo "</ul>";
    echo "</div>";

    echo "<div class='category'>";

        echo "<h2>Computer Complex Catalog</h2>";

        echo "<ul>";
            
            $categoryProducts = [];

            foreach ($products as $product) {

                if (!isset($categoryProducts[$product['category']])) {

                    $categoryProducts[$product['category']] = $product;
                    echo "<div class='products'>";
                        echo "<div class='product'>";

                        // Display product image, name, description, price, and quantity
                        $imageData = $product["images"];

                        if (preg_match('%^[a-zA-Z0-9/+]*={0,2}$%', $imageData)) {

                            // Image is already base64-encoded, so no need to encode again
                            echo "<img src='data:image/jpeg;base64," . $imageData . "' alt='Image'>";

                        } else {

                            // Image is not base64-encoded, so encode it before displaying
                            echo "<img src='data:image/jpeg;base64," . base64_encode($imageData) . "' alt='Image'>";
                        }
                            echo "<p>" . htmlspecialchars(string: $product["category"]). "<p>";

                        echo "</div>";
                    echo "</div>";
                    
                }
            }
            
        echo "</ul>";
    echo "</div>";