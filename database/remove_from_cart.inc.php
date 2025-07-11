<?php

    require_once "DBConn.inc.php";
    require_once "../Config/config.inc.php";
    require_once('logins/login_view.inc.php');

    // Get the database connection instance
    $pdo = DatabaseConnection::getInstance()->getConnection();

    if (!isset($_SESSION["user_id"])) {

        header("Location: ../login.php");
        exit();
    }

    $customer_id = $_SESSION["user_id"];
    $product_id = $_POST['product_id'];

    try {

        // Start transaction
        $pdo->beginTransaction();

        // Fetch the active cart for the customer
        $stmt = $pdo->prepare("SELECT cart_id FROM carts WHERE customer_id = ? AND status = 'active'");
        $stmt->execute([$customer_id]);
        $cart = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($cart) {

            $cart_id = $cart['cart_id'];

            // Delete the product from the cart
            $stmt = $pdo->prepare("DELETE FROM cart_items WHERE cart_id = ? AND product_id = ?");
            $stmt->execute([$cart_id, $product_id]);

            // Commit transaction
            $pdo->commit();

            // Redirect to the cart page
            header("Location: ../cart.php");
            die();

        } else {

            throw new Exception("No active cart found.");
        }

    } catch (PDOException $e) {

        // Rollback transaction if something failed
        $pdo->rollBack();
        echo "Error: " . $e->getMessage();

    } catch (Exception $e) {
        
        echo "Error: " . $e->getMessage();
    }