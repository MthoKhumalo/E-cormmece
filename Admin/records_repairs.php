<?php

    require_once "../database/DBConn.inc.php";

    // Get the database connection instance
    $pdo = DatabaseConnection::getInstance()->getConnection();

    // Define empty search results and error messages
    $repairs = [];
    $error = '';
    $totalRepairs = 0;
    $statusCount = [];
    $bookedByDate = [];

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        try {
            $filters = [];
            $params = [];

            $query = "SELECT r.repair_id, CONCAT(c.firstName, ' ', c.lastName) AS customer_name, r.booked_date, r.status 
                        FROM repairs r 
                        JOIN customers c ON r.customer_id = c.customer_id 
                        WHERE 1=1";

            // Repair_id filter
            if (!empty($_POST['repair_id'])) {

                $query .= " AND r.repair_id = ?";
                $filters[] = $_POST['repair_id'];
            }

            // Customer name filter
            if (!empty($_POST['customer_name'])) {

                $query .= " AND (c.firstName LIKE ? OR c.lastName LIKE ?)";
                $nameFilter = "%" . $_POST['customer_name'] . "%";
                $filters[] = $nameFilter;
                $filters[] = $nameFilter;
            }

            // Booked_date filter
            if (!empty($_POST['booked_date'])) {

                $query .= " AND r.booked_date >= ?";
                $filters[] = $_POST['booked_date'];
            }

            // Status filter
            if (!empty($_POST['status'])) {

                $query .= " AND r.status = ?";
                $filters[] = $_POST['status'];
            }

            // Prepare and execute statement for filtered repairs
            $stmt = $pdo->prepare($query);
            $stmt->execute($filters);
            $repairs = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Query to calculate total number of repairs
            $totalRepairsQuery = "SELECT COUNT(*) AS totalRepairs FROM repairs";
            $stmt = $pdo->query($totalRepairsQuery);
            $totalRepairs = $stmt->fetchColumn();

            // Query to get the number of repairs by status
            $statusCountQuery = "SELECT status, COUNT(*) AS statusCount FROM repairs GROUP BY status";
            $stmt = $pdo->query($statusCountQuery);
            $statusCount = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Query to get repairs booked by date
            $bookedByDateQuery = "SELECT DATE(booked_date) AS date, COUNT(*) AS count FROM repairs GROUP BY DATE(booked_date)";
            $stmt = $pdo->query($bookedByDateQuery);
            $bookedByDate = $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (Exception $e) {

            $error = "An error occurred while fetching repairs: " . $e->getMessage();
        }
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Repair Records</title>

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

        form {
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

        .report-section {
            margin-top: 30px;
        }

        .report-section h2 {
            margin-bottom: 15px;
            color: #4CAF50;
        }
    </style>
</head>

    <body>

        <?php include 'adminHeader.php'; ?>

        <h1>Search Repair Records</h1>

        <div class="container">

            <form method="POST">

                <input type="text" name="repair_id" placeholder="Enter Repair ID">
                <input type="text" name="customer_name" placeholder="Enter Customer Name">
                <input type="date" name="booked_date" placeholder="Booked Date">
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
                        <th>Repair ID</th>
                        <th>Customer Name</th>
                        <th>Booked Date</th>
                        <th>Status</th>
                    </tr>

                </thead>

                <tbody>
                    <?php if (!empty($repairs)): ?>
                        <?php foreach ($repairs as $repair): ?>

                            <tr>
                                <td><?= htmlspecialchars($repair['repair_id']) ?></td>
                                <td><?= htmlspecialchars($repair['customer_name']) ?></td>
                                <td><?= htmlspecialchars($repair['booked_date']) ?></td>
                                <td><?= htmlspecialchars($repair['status']) ?></td>
                            </tr>

                        <?php endforeach; ?>
                    <?php else: ?>

                        <tr>
                            <td colspan="4">No repairs found based on the search criteria.</td>
                        </tr>

                    <?php endif; ?>
                </tbody>

            </table>
        </div>

        <div class="report-section">
            <h2>Business Insights</h2>

            <p><strong>Total Repairs: </strong><?= htmlspecialchars($totalRepairs) ?></p>

            <h3>Repairs by Status:</h3>
            <table>
                <thead>
                    <tr>
                        <th>Status</th>
                        <th>Count</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($statusCount as $status): ?>
                        <tr>
                            <td><?= htmlspecialchars($status['status']) ?></td>
                            <td><?= htmlspecialchars($status['statusCount']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <h3>Repairs Booked Over Time:</h3>
            <table>
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Count</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($bookedByDate as $date): ?>
                        <tr>
                            <td><?= htmlspecialchars($date['date']) ?></td>
                            <td><?= htmlspecialchars($date['count']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

        </div>
    </body>
</html>