<?php 
    try {

        require_once ('DBConn.inc.php');
        require_once('Config/config.inc.php');
        require_once('logins/login_view.inc.php');
        
        // Get the database connection instance
        $pdo = DatabaseConnection::getInstance()->getConnection();

        // Prepare SQL statement
        $query = "SELECT * FROM products WHERE is_deleted = 0";
        $stmt = $pdo->prepare($query);
        $stmt->execute();

        // Fetch products
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Check if there are any products
        if ($products) {
            
            $groupedProducts = [];
            
            // Group products by category
            foreach ($products as $product) {

                $category = $product["category"];
                $groupedProducts[$category][] = $product;
            }

            echo "<div class='category'>";
                foreach ($groupedProducts as $category => $productsInCategory) {

                    echo "<h2>$category</h2>"; // Output heading with category name

                    // Output each product within the category directly (no need to filter duplicates)
                    foreach ($productsInCategory as $product) {

                        echo "<div class='products'>";
                            echo "<div class='product'>";

                            // Display product 
                            // Display product image, name, description, price, and quantity
                            $imageData = $product["images"];

                            if (preg_match('%^[a-zA-Z0-9/+]*={0,2}$%', $imageData)) {

                                // Image is already base64-encoded, so no need to encode again
                                echo "<img src='data:image/jpeg;base64," . $imageData . "' alt='Image'>";

                            } else {

                                // Image is not base64-encoded, so encode it before displaying
                                echo "<img src='data:image/jpeg;base64," . base64_encode($imageData) . "' alt='Image'>";
                                
                            }
                            echo "<p>" . htmlspecialchars($product["pName"]). "</p>";
                            echo "<p>" . htmlspecialchars($product["description"]). "</p>";
                            $price = is_numeric($product['price']) ? (float)$product['price'] : 0.0;
                            echo "<p>R " . htmlspecialchars(number_format($price, 2)) . "</p>";

                            // Display Add to Cart button if the user is logged in
                            if (isset($_SESSION["user_id"])) {

                                echo "<form action='database/cart.inc.php' method='POST'>";

                                echo "<input type='hidden' name='product_id' value='" . htmlspecialchars($product["product_id"]) . "'>";
                                echo '<input type="hidden" name="action" value="increase">';
                                echo "<button class='add-to-cart' data-product-id='" . htmlspecialchars($product['product_id']) . "'>Add To Cart</button>";

                                echo "</form>";
                            }

                            echo "</div>";
                        echo "</div>";
                    }
                }
            echo "</div>";

        } else {

            echo "No products found";
        }
    } catch(PDOException $e) {

        die("Query Failed: " . $e->getMessage());
    }
