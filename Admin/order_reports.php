<?php

    require_once "../database/DBConn.inc.php";

    // Get the database connection instance
    $pdo = DatabaseConnection::getInstance()->getConnection();

    // Initialize filter variables
    $order_id = $start_date = $end_date = $min_price = "";
    $where_clauses = [];
    $params = [];

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Filter by Order ID
        if (!empty($_POST['order_id'])) {
            $order_id = $_POST['order_id'];
            $where_clauses[] = "orders.order_id = ?";
            $params[] = $order_id;
        }

        // Filter by Date Range
        if (!empty($_POST['start_date']) && !empty($_POST['end_date'])) {
            $start_date = $_POST['start_date'];
            $end_date = $_POST['end_date'];
            $where_clauses[] = "orders.order_date BETWEEN ? AND ?";
            $params[] = $start_date;
            $params[] = $end_date;
        }

        // Filter by Minimum Price
        if (!empty($_POST['min_price'])) {
            $min_price = $_POST['min_price'];
            $where_clauses[] = "orders.totalPrice >= ?";
            $params[] = $min_price;
        }
    }

    // Base query to fetch filtered orders
    $sql = "SELECT orders.order_id, CONCAT(customers.firstName, ' ', customers.lastName) AS customer_name, 
            orders.order_date, orders.totalPrice, payments.payment_type, orders.cart_id
            FROM orders 
            JOIN customers ON orders.customer_id = customers.customer_id
            JOIN payments ON orders.order_id = payments.order_id";

    if (!empty($where_clauses)) {
        $sql .= " WHERE " . implode(" AND ", $where_clauses);
    }

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Report: Calculate total orders and total price
    $total_orders = count($orders);
    $total_price = 0;

    foreach ($orders as $order) {
        $total_price += $order['totalPrice'];
    }

    if (isset($_GET['cart_id'])) {
        $cart_id = $_GET['cart_id'];
        $sql = "SELECT ci.quantity, p.pName, p.price, p.description 
                FROM cart_items ci 
                JOIN products p ON ci.product_id = p.product_id 
                WHERE ci.cart_id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$cart_id]);
        $items = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($items) {
            foreach ($items as $item) {
                echo "<li>{$item['pName']} (Qty: {$item['quantity']}) - R {$item['price']} <br> {$item['description']}</li>";
            }
        } else {
            echo "<li>No items found in this cart.</li>";
        }
        exit;
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Review</title>
    <style>
        /* Table and Button Styles */
        table {
            width: 100%;
            border-collapse: collapse;
        }

        table, th, td {
            border: 1px solid black;
            padding: 10px;
        }

        th {
            background-color: #f2f2f2;
        }

        .btn-view-items {
            background-color: #4CAF50;
            color: white;
            padding: 5px 10px;
            border: none;
            cursor: pointer;
            border-radius: 4px;
        }

        .btn-view-items:hover {
            background-color: #45a049;
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.4);
            overflow: auto;
            padding-top: 60px;
        }

        .modal-content {
            background-color: #fefefe;
            margin: 5% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover, .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }

        /* Report Section Styles */
        .report-section {
            margin-top: 30px;
        }

        .report-section h2 {
            margin-bottom: 15px;
            color: #4CAF50;
        }

        .report-section p {
            padding: 10px;
            margin: 10px;
            width: calc(30% - 40px);
            border: 1px solid #ccc;
            border-radius: 4px;
        }
    </style>
</head>
<body>

    <?php include 'adminHeader.php'; ?>

    <h1>Order Records</h1>

    <!-- Filter Form -->
    <header>
        <nav>
            <form method="POST">
                <ul>
                    <li>
                        <label for="order_id">Order ID:</label>
                        <input type="text" id="order_id" name="order_id" value="<?php echo htmlspecialchars($order_id); ?>">
                    </li>
                    <li>
                        <label for="start_date">Start Date:</label>
                        <input type="date" id="start_date" name="start_date" value="<?php echo htmlspecialchars($start_date); ?>">
                    </li>
                    <li>
                        <label for="end_date">End Date:</label>
                        <input type="date" id="end_date" name="end_date" value="<?php echo htmlspecialchars($end_date); ?>">
                    </li>
                    <li>
                        <label for="min_price">Minimum Price:</label>
                        <input type="number" id="min_price" name="min_price" step="0.01" value="<?php echo htmlspecialchars($min_price); ?>">
                    </li>
                    <li><button type="submit">Search</button></li>
                </ul>
            </form>
        </nav>
    </header>

    <!-- Orders Table -->
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Customer Name</th>
                    <th>Order Date</th>
                    <th>Total Price</th>
                    <th>Payment Type</th>
                    <th>Items</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($orders)): ?>
                    <?php foreach ($orders as $order): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($order['order_id']); ?></td>
                            <td><?php echo htmlspecialchars($order['customer_name']); ?></td>
                            <td><?php echo htmlspecialchars($order['order_date']); ?></td>
                            <td>R <?php echo htmlspecialchars($order['totalPrice']); ?></td>
                            <td><?php echo htmlspecialchars($order['payment_type']); ?></td>
                            <td>
                                <button class="btn-view-items" onclick="fetchCartItems(<?php echo $order['cart_id']; ?>)">
                                    View Items
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6">No orders found</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Modal for displaying cart items -->
    <div id="cart-items-modal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <h2>Cart Items</h2>
            <ul id="cart-items-list"></ul>
        </div>
    </div>

    <!-- Report Section (Visible for Owner/Manager) -->
    <?php if (isset($_SESSION["department"]) && ($_SESSION["department"] === 'Owner' || $_SESSION["department"] === 'Manager')): ?>
        <div class="report-section">
            <h2>Order Report</h2>
            <p><strong>Total Orders:</strong> <?php echo $total_orders; ?></p>
            <p><strong>Total Price:</strong> R <?php echo number_format($total_price, 2); ?></p>
        </div>
    <?php endif; ?>

    <script>
        function fetchCartItems(cartId) {
            const modal = document.getElementById("cart-items-modal");
            const cartItemsList = document.getElementById("cart-items-list");

            // Open modal and display loading
            modal.style.display = "block";
            cartItemsList.innerHTML = "<li>Loading items...</li>";

            // Fetch cart items via AJAX
            const xhr = new XMLHttpRequest();
            xhr.open("GET", "<?php echo $_SERVER['PHP_SELF']; ?>?cart_id=" + cartId, true);
            xhr.onload = function() {
                if (xhr.status === 200) {
                    cartItemsList.innerHTML = xhr.responseText;
                } else {
                    cartItemsList.innerHTML = "<li>Error fetching items.</li>";
                }
            };
            xhr.send();
        }

        function closeModal() {
            document.getElementById("cart-items-modal").style.display = "none";
        }
    </script>

</body>
</html>
