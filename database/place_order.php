<?php 

require_once "DBConn.inc.php";
require_once "../Config/config.inc.php";

// Get the database connection instance
$pdo = DatabaseConnection::getInstance()->getConnection();

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    die();
}

$customer_id = $_SESSION["user_id"];

try {
    // Fetch the customer name
    $stmt = $pdo->prepare("SELECT firstName FROM customers WHERE customer_id = ?");
    $stmt->execute([$customer_id]);
    $customer = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$customer) {
        echo "Customer not found.";
        exit();
    }

    $customer_name = $customer['firstName'];

    // Fetch active cart
    $stmt = $pdo->prepare("SELECT cart_id FROM carts WHERE customer_id = ? AND status = 'active'");
    $stmt->execute([$customer_id]);
    $cart = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$cart) {
        echo "No active cart found.";
        exit();
    }

    $cart_id = $cart['cart_id'];

    // Calculate total price of cart
    $stmt = $pdo->prepare("SELECT SUM(products.price * cart_items.quantity) AS total_price 
                            FROM cart_items 
                            JOIN products ON cart_items.product_id = products.product_id 
                            WHERE cart_items.cart_id = ?");
    $stmt->execute([$cart_id]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $totalPrice = $result['total_price'];

    // Create a new order
    $order_date = date('Y-m-d');
    $stmt = $pdo->prepare("INSERT INTO orders (custumer_name, order_date, totalPrice, customer_id, cart_id) 
                            VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$customer_name, $order_date, $totalPrice, $customer_id, $cart_id]);
    $order_id = $pdo->lastInsertId();

    // Update product quantities in the products table
    $stmt = $pdo->prepare("SELECT product_id, quantity FROM cart_items WHERE cart_id = ?");
    $stmt->execute([$cart_id]);
    $cart_items = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($cart_items as $item) {
        $stmt = $pdo->prepare("UPDATE products SET Quantity = quantity - ? WHERE product_id = ?");
        $stmt->execute([$item['quantity'], $item['product_id']]);
    }

    // Optionally, you might want to clear the cart or update its status
    $stmt = $pdo->prepare("UPDATE carts SET status = 'completed' WHERE cart_id = ?");
    $stmt->execute([$cart_id]);

    // Redirect to payment page
    header("Location: payment.php?order_id=$order_id");
    die();

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    die();
}
