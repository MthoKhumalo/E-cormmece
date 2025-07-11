<?php 

    declare (strict_types= 1);

    function check_search_errors(){

        if(isset($_SESSION["errors_search"])){

            $errors = $_SESSION["errors_search"];

            echo "<br>";

            foreach ($errors as $error){

                echo "<h3>". $error ."</h3><br>";
            }

            unset($_SESSION["errors_search"]);
        }
    }

    function search_products(array $products){

        echo "<h3>Search Results</h3>";

        if(empty($products)){

            echo "<div>";
                echo "<p> Category not Found!! </p>";
            echo "<div>";

        }else{
            
            if ($products) {

                $groupedProducts = [];

                foreach ($products as $product) {

                    $category = $product["category"];
                    $groupedProducts[$category][] = $product;
                }

                echo "<div class='category'>";
                    foreach ($groupedProducts as $category => $productsInCategory) {

                        echo "<h2>$category</h2>"; // Output heading with category name

                        // Extract unique product names within each category
                        $uniqueProductNames = array_unique(array_column($productsInCategory, 'pName'));

                        // Output each unique product within the category
                        foreach ($uniqueProductNames as $productName) {
                            
                            // Find the first occurrence of the product with this name within the category
                            $product = array_values(array_filter($productsInCategory, function($item) use ($productName) {

                                return $item['pName'] === $productName;
                            }))[0];
                
                            // Output each product as a div
                            echo "<div class='products'>";
                                echo "<div class='product'>";

                                    $imageData = $product["images"];

                                    if (preg_match('%^[a-zA-Z0-9/+]*={0,2}$%', $imageData)) {

                                        // Image is already base64-encoded, so no need to encode again
                                        echo "<img src='data:image/jpeg;base64," . $imageData . "' alt='Image'>";
                                        
                                    } else {

                                        // Image is not base64-encoded, so encode it before displaying
                                        echo "<img src='data:image/jpeg;base64," . base64_encode($imageData) . "' alt='Image'>";
                                        
                                    }
                                    echo "<p>" . htmlspecialchars($product["pName"]) . "</p>";
                                    echo "<p>" . htmlspecialchars($product["description"]) . "</p>";
                                    echo "<p>R " . htmlspecialchars((string) $product["price"]) . "</p>";


                                    // Display Add to Cart button if the user is logged in
                                    if (isset($_SESSION["user_id"])) {

                                        echo "<form action='../cart.inc.php' method='POST'>";

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

                echo "<h3> No products found </h3>";
            }
        }
    } 