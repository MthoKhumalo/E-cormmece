<?php

    require_once('product_contr.inc.php');
    require_once('product_view.inc.php');
    require_once(__DIR__ . '/../DBConn.inc.php');

    // Get the database connection instance
    $pdo = DatabaseConnection::getInstance()->getConnection();

    try {
        
        // Initialize controller
        $productController = new ProductController($pdo);

        // Fetch different categories of products
        $under5000Products = $productController->getProductsByPriceRange('lessThan5000');
        $over10000Products = $productController->getProductsByPriceRange('greaterThan10000');

        // Render views
        renderProducts($under5000Products, "Products Under R 5,000");
        renderProducts($over10000Products, "Products Over R 10,000");

    } catch (Exception $e) {

        echo "An error occurred while loading products.";
        error_log("Error loading products: " . $e->getMessage());
    }