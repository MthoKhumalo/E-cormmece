<?php

    declare (strict_types= 1);
    function renderProducts($products, $title) {

        echo "<div class='category'>";
            echo "<h2>$title</h2>";
            echo "<ul>";

                foreach ($products as $product) {

                    echo "<div class='products'>";
                        echo "<div class='product'>";
                        
                        $imageData = $product["images"];
                        if (preg_match('%^[a-zA-Z0-9/+]*={0,2}$%', $imageData)) {
                            echo "<img src='data:image/jpeg;base64," . $imageData . "' alt='Image'>";
                        } else {
                            echo "<img src='data:image/jpeg;base64," . base64_encode($imageData) . "' alt='Image'>";
                        }
                            echo "<p>" . htmlspecialchars($product['pName']) . "</p>";
                            echo "<p>" . htmlspecialchars($product['description']) . "</p>";
                            $price = is_numeric($product['price']) ? (float)$product['price'] : 0.0;
                            echo "<p>R " . htmlspecialchars(number_format($price, 2)) . "</p>";
                            
                            if(isset($_SESSION["user_id"])){

                                echo "<form action='../cart.inc.php' method='POST'>";

                                    echo "<input type='hidden' name='product_id' value='" . htmlspecialchars(string: $product["product_id"]) . "'>";
                                    echo '<input type="hidden" name="action" value="increase">';
                                    echo "<button class='add-to-cart' data-product-id='<?=" .htmlspecialchars(string: $product['product_id']). "?>'>Add To Cart</button>";

                                echo "</form>";

                            }

                        echo "</div>";
                    echo "</div>";
                }

            echo "</ul>";
        echo "</div>";
    }