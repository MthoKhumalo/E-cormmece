<?php

    require_once "database/DBConn.inc.php";
    require_once "Config/config.inc.php";

    // Get the database connection instance
    $pdo = DatabaseConnection::getInstance()->getConnection();

    if (!isset($_SESSION["user_id"])) {

        header("Location: login.php");
        die();
    }

    $customer_id = $_SESSION["user_id"];

    try {

        $stmt = $pdo->prepare("SELECT cart_id FROM carts WHERE customer_id = ? AND status = 'active'");
        $stmt->execute([$customer_id]);
        $cart = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($cart) {

            $cart_id = $cart['cart_id'];

            $stmt = $pdo->prepare("SELECT products.product_id, products.pName, products.description, products.price, products.images, cart_items.quantity 
                                FROM cart_items 
                                JOIN products ON cart_items.product_id = products.product_id
                                WHERE cart_items.cart_id = ?");
            $stmt->execute([$cart_id]);
            $cart_items = $stmt->fetchAll(PDO::FETCH_ASSOC);

        } else {

            $cart_items = [];
        }

        // Calculate total items in cart
        $totalItems = array_sum(array_column($cart_items, 'quantity'));

    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
?>

<!DOCTYPE html>
<html lang="en">

    <head>
        <title> Cart </title>
        <link rel="stylesheet" href="CSS/style.css">
    </head>

    <body>
        <!--header-->
        <?php include 'Header.php'; ?>

        <!--content-->
        <h1>Shopping Cart</h1>

        <!-- Display error message if it exists -->
        <?php if (isset($_SESSION['error_message'])) { ?>
            <p style="color: red;"><?php echo $_SESSION['error_message']; unset($_SESSION['error_message']); ?></p>
        <?php } ?>

        <?php if (empty($cart_items)) { ?>
            
            <p>Your cart is empty.</p>

        <?php } else { 

            $totalAmount = 0; ?>

            <?php foreach ($cart_items as $item) { ?>
                <div class='cart-item'>

                    <?php // Display product image, name, description, price, and quantity
                            $imageData = $item["images"];

                            if (preg_match('%^[a-zA-Z0-9/+]*={0,2}$%', $imageData)) {
                                
                                // Image is already base64-encoded, so no need to encode again
                                echo "<img src='data:image/jpeg;base64," . $imageData . "' alt='Image'>";

                            } else {

                                // Image is not base64-encoded, so encode it before displaying
                                echo "<img src='data:image/jpeg;base64," . base64_encode($imageData) . "' alt='Image'>";
                            } ?>

                    <p><?php echo htmlspecialchars($item["pName"]); ?></p>
                    
                    <div class='cart-item-info'>
                        <div class='cart-item-details'>

                            <p><?php echo htmlspecialchars($item["description"]); ?></p>
                            <p>R <?php echo htmlspecialchars($item["price"]); ?></p>

                        </div>

                        <div class='cart-item-actions'>
                            
                            <!-- increase quantity button -->
                            <form action="database/cart.inc.php" method="POST">

                                <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($item["product_id"]); ?>">
                                <input type="hidden" name="action" value="increase">
                                <button type="submit">+</button>

                            </form>

                            <p>Quantity: <?php echo htmlspecialchars($item["quantity"]); ?></p>

                            <!-- decrease quantity button -->
                            <form action="database/cart.inc.php" method="POST">

                                <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($item["product_id"]); ?>">
                                <input type="hidden" name="action" value="decrease">
                                <button type="submit">-</button>

                            </form>

                            <!-- remove item -->
                            <form action="database/remove_from_cart.inc.php" method="POST">

                                <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($item["product_id"]); ?>">
                                <button type="submit">Remove</button>

                            </form>

                        </div>
                    </div>
                </div>

                <!-- Calculate the total amount of items in cart --> 
                <?php $totalAmount += htmlspecialchars($item['price']) * $item['quantity']; ?>

            <?php } ?>

            <!-- Display total amount -->
            <div id='total-amount'>
                Total Amount: R <span id='total'><?php echo $totalAmount; ?></span>
            </div><br>

            <!-- Place order button -->
            <div class='cart-item-actions'>
                <form action="database/place_order.php" method="POST">
                    <button type="submit">PLACE ORDER</button>
                </form>
            </div><br>

        <?php } ?>

        <!--footer-->
        <?php include 'Footer.php'?>

    </body>
</html>