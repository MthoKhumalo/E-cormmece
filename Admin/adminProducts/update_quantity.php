<?php

    try {

        require_once '../../database/DBConn.inc.php';
        require_once('../../Config/config.inc.php');

        // Get the database connection instance
        $pdo = DatabaseConnection::getInstance()->getConnection();

        if ($_SERVER["REQUEST_METHOD"] === "POST") {

            // Get the product ID and new quantity from the form
            $product_id = $_POST['product_id'];
            $added_quantity = $_POST['quantity'];

            // Prepare the update query and add the input quantity to the current value
            $query = "UPDATE products SET Quantity = quantity + :added_quantity WHERE product_id = :product_id";
            $stmt = $pdo->prepare($query);

            $stmt->bindParam(':added_quantity', $added_quantity, PDO::PARAM_INT);
            $stmt->bindParam(':product_id', $product_id, PDO::PARAM_INT);

            if ($stmt->execute()) {

                header("Location: ../admin_product.php");
                exit();

            } else {

                echo "Failed to update quantity.";
            }
        }
    } catch (PDOException $e) {

        die("Query Failed: " . $e->getMessage());
    }
