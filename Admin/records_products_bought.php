<?php

    require_once "../database/DBConn.inc.php";
    // Get the database connection instance
    $pdo = DatabaseConnection::getInstance()->getConnection();

    $sells = [];
    $error = '';
    $totalSales = 0;
    $averagePrice = 0;
    $completedSalesCount = 0;
    $highValueCustomerCount = 0;

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        try {
            $filters = [];
            $params = [];
            $query = "SELECT s.sell_id, CONCAT(c.firstName, ' ', c.lastName) AS customer_name, s.price, s.status 
                    FROM sell s 
                    JOIN customers c ON s.customer_id = c.customer_id 
                    WHERE 1=1";

            // Sell_id filter
            if (!empty($_POST['sell_id'])) {

                $query .= " AND s.sell_id = ?";
                $filters[] = $_POST['sell_id'];
            }

            // Customer name filter
            if (!empty($_POST['customer_name'])) {

                $query .= " AND (c.firstName LIKE ? OR c.lastName LIKE ?)";
                $nameFilter = "%" . $_POST['customer_name'] . "%";
                $filters[] = $nameFilter;
                $filters[] = $nameFilter;
            }

            // Price filter
            if (!empty($_POST['min_price']) && !empty($_POST['max_price'])) {

                $query .= " AND s.price BETWEEN ? AND ?";
                $filters[] = $_POST['min_price'];
                $filters[] = $_POST['max_price'];
            }

            // Status filter
            if (!empty($_POST['status'])) {

                $query .= " AND s.status = ?";
                $filters[] = $_POST['status'];
            }

            // Prepare and execute statement
            $stmt = $pdo->prepare($query);
            $stmt->execute($filters);
            $sells = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Calculate report data
            $totalSales = array_sum(array_column($sells, 'price'));
            $averagePrice = count($sells) ? $totalSales / count($sells) : 0;
            $completedSalesCount = count(array_filter($sells, fn($sell) => $sell['status'] === 'Complete'));
            $highValueCustomerCount = count(array_filter($sells, fn($sell) => $sell['price'] > 1000)); 

        } catch (Exception $e) {

            $error = "An error occurred while fetching sell records: " . $e->getMessage();
        }
    }
?>

<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Sell Records and Report</title>

        <style>

            body {
                font-family: Arial, sans-serif;
                background-color: #f4f4f4;
            }

            .container {
                width: 100%;
                margin: auto;
                background-color: white;
                padding: 20px;
                border-radius: 10px;
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            }

            h1 {
                text-align: center;
            }

            form,
            table {
                margin-bottom: 20px;
            }

            input,
            select,
            button {
                padding: 10px;
                margin: 10px;
                width: calc(100% - 40px);
                border: 1px solid #ccc;
                border-radius: 4px;
            }

            button {
                background-color: #28a745;
                color: white;
                cursor: pointer;
            }

            button:hover {
                background-color: #218838;
            }

            table {
                width: 100%;
                border-collapse: collapse;
            }

            th,
            td {
                padding: 10px;
                text-align: left;
                border-bottom: 1px solid #ddd;
            }

            th {
                background-color: #4CAF50;
                color: white;
            }

            .error {
                color: red;
                margin: 10px 0;
            }

            .report-section{
                width: 100%;
                margin: 20px 0;
                background-color: white;
                padding: 20px;
                border-radius: 10px;
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            }

        </style>
    </head>

    <body>

        <?php include 'adminHeader.php'; ?>

        <h1>Search Sell Records and View Report</h1>

        <div class="container">
            <form method="POST">

                <input type="text" name="sell_id" placeholder="Enter Sell ID">
                <input type="text" name="customer_name" placeholder="Enter Customer Name">
                <input type="number" name="min_price" placeholder="Minimum Price">
                <input type="number" name="max_price" placeholder="Maximum Price">
                <select name="status">
                    <option value="">--Select Status--</option>
                    <option value="Pending">Pending</option>
                    <option value="Accepted">Accepted</option>
                    <option value="Declined">Declined</option>
                    <option value="In Review">In Review</option>
                    <option value="Complete">Complete</option>
                    <option value="Declined after Review">Declined after Review</option>
                </select>

                <button type="submit">Search</button>

            </form>

            <?php if ($error): ?>

                <div class="error"><?= htmlspecialchars($error) ?></div>

            <?php endif; ?>

            <table>
                <thead>

                    <tr>
                        <th>Sell ID</th>
                        <th>Customer Name</th>
                        <th>Price</th>
                        <th>Status</th>
                    </tr>

                </thead>

                <tbody>
                    <?php if (!empty($sells)): ?>
                        <?php foreach ($sells as $sell): ?>

                            <tr>
                                <td><?= htmlspecialchars($sell['sell_id']) ?></td>
                                <td><?= htmlspecialchars($sell['customer_name']) ?></td>
                                <td><?= htmlspecialchars($sell['price']) ?></td>
                                <td><?= htmlspecialchars($sell['status']) ?></td>
                            </tr>

                        <?php endforeach; ?>
                    <?php else: ?>

                        <tr>
                            <td colspan="4">No sell records found based on the search criteria.</td>
                        </tr>

                    <?php endif; ?>
                    
                </tbody>
            </table>
        </div>

        <div class="report-section">

            <h2>Sales Report</h2>

            <table>

                <tr>
                    <th>Total Sales</th>
                    <td>R <?= number_format($totalSales, 2) ?></td>
                </tr>

                <tr>
                    <th>Average Price</th>
                    <td>R <?= number_format($averagePrice, 2) ?></td>
                </tr>

                <tr>
                    <th>Completed Sales Count</th>
                    <td><?= $completedSalesCount ?></td>
                </tr>

                <tr>
                    <th>High-Value Customers Count (Price > R1000 )</th>
                    <td><?= $highValueCustomerCount ?></td>
                </tr>

            </table>
        </div>
    </body>
</html>