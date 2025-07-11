<?php 
    try {

        include('../database/DBConn.inc.php');
        require_once('../Config/config.inc.php');
        require_once('../database/logins/login_view.inc.php');

        // Get the database connection instance
        $pdo = DatabaseConnection::getInstance()->getConnection();

        // Prepare SQL statement to fetch products, including deleted ones
        $query = "SELECT * FROM products";
        $stmt = $pdo->prepare($query);
        $stmt->execute();

        // Fetch products
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($products) {

            $groupedProducts = [];

            // Group products by category
            foreach ($products as $product) {

                $category = $product["category"];
                $groupedProducts[$category][] = $product;
            }

            // Display categories and products
            echo "<div class='category'>";
            
                foreach ($groupedProducts as $category => $productsInCategory) {

                    echo "<h2>$category</h2>";

                    foreach ($productsInCategory as $product) {
                        
                        // Apply "deleted" class if product is marked as deleted
                        $isDeleted = $product["is_deleted"] == 1;
                        $deletedClass = $isDeleted ? "deleted-product" : "";
                        $toggleText = $isDeleted ? "Restore" : "Remove";
                        
                        echo "<div class='products $deletedClass'>";
                            echo "<div class='product'>";
                            
                                // Display product image
                                $imageData = $product["images"];
                                if (preg_match('%^[a-zA-Z0-9/+]*={0,2}$%', $imageData)) {
                                    echo "<img src='data:image/jpeg;base64," . $imageData . "' alt='Image'>";
                                } else {
                                    echo "<img src='data:image/jpeg;base64," . base64_encode($imageData) . "' alt='Image'>";
                                }

                                // Product name, description, price, and quantity as editable fields
                                echo "<p class='editable' data-type='pName' data-id='" . htmlspecialchars($product["product_id"]) . "'>Name: " . htmlspecialchars($product["pName"]) . "</p>";
                                echo "<p class='editable' data-type='description' data-id='" . htmlspecialchars($product["product_id"]) . "'>Info: " . htmlspecialchars($product["description"]) . "</p>";
                                $price = is_numeric($product['price']) ? (float)$product['price'] : 0.0;
                                echo "<p class='editable' data-type='price' data-id='" . htmlspecialchars($product["product_id"]) . "'>
                                    Price: R " . htmlspecialchars(number_format($price, 2)) . "</p>";

                                echo "<p class='editable' data-type='quantity' data-id='" . htmlspecialchars($product["product_id"]) . "'>Quantity: " . htmlspecialchars($product["Quantity"]) . "</p>";

                                // Show deleted tag if product is marked as deleted
                                if ($isDeleted) {
                                    echo "<span class='deleted-tag'>Deleted</span>";
                                }

                                // Button to toggle deletion status
                                echo "<form action='adminProducts/delete_product_admin.php' method='POST' class='delete-form'>";
                                    echo "<input type='hidden' name='product_id' value='" . htmlspecialchars($product["product_id"]) . "'>";
                                    echo "<button type='submit'>$toggleText</button>";
                                echo "</form>";

                            echo "</div>";
                        echo "</div>";
                    }
                }
            echo "</div>";

        } else {

            echo "No products found";
        }
    } catch (PDOException $e) {

        die("Query Failed: " . $e->getMessage());
    }
