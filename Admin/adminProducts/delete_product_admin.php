<?php

    session_start();
    require_once '../../database/DBConn.inc.php';

    // Get the database connection instance
    $pdo = DatabaseConnection::getInstance()->getConnection();

    if (!isset($_SESSION["user_id"])) {
        header("Location: ../login.php");
        exit();
    }

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $product_id = $_POST['product_id'];

        if ($product_id) {
            try {
                // Check the current `is_deleted` status to toggle it
                $query = "SELECT is_deleted FROM products WHERE product_id = ?";
                $stmt = $pdo->prepare($query);
                $stmt->execute([$product_id]);
                $product = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($product) {
                    // Toggle `is_deleted` status
                    $newStatus = $product['is_deleted'] == 1 ? 0 : 1;
                    $updateQuery = "UPDATE products SET is_deleted = ? WHERE product_id = ?";
                    $updateStmt = $pdo->prepare($updateQuery);
                    $updateStmt->execute([$newStatus, $product_id]);

                    header("Location: ../admin_product.php");
                    exit();
                } else {
                    echo "Product not found.";
                }
            } catch (PDOException $e) {
                die("Error: " . $e->getMessage());
            }
        } else {
            echo "Invalid product ID.";
        }
    } else {
        echo "Invalid request.";
    }