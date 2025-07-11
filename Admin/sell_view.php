<?php
    // Ensure session and database connection
    require_once '../database/DBConn.inc.php'; 
    
    // Get the database connection instance
    $pdo = DatabaseConnection::getInstance()->getConnection();

    // Fetch only pending sell requests from the database
    $sql = "SELECT sell.sell_id, sell.customer_id, sell.image1, sell.image2, sell.image3, sell.description, sell.price, 
                customers.firstName AS customer_name
            FROM sell 
            JOIN customers ON sell.customer_id = customers.customer_id
            WHERE sell.status = 'Pending'";  // Fetch only pending requests

    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $sellRequests = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Handle the Decline action
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['decline_id'])) {

        $sell_id = $_POST['decline_id'];

        // Delete the record from the database
        $deleteSQL = "DELETE FROM sell WHERE sell_id = :sell_id";
        $deleteStmt = $pdo->prepare($deleteSQL);
        $deleteStmt->bindParam(':sell_id', $sell_id);

        if ($deleteStmt->execute()) {

            echo "<script>alert('Sell request declined and record deleted.');</script>";
            header('Refresh:0');
            exit();

        } else {

            echo "<script>alert('Error in deleting the request.');</script>";
        }
    }

    // Handle the Accept action
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['accept_id'])) {

        $sell_id = $_POST['accept_id'];

        // Update the record status to 'In Review'
        $updateSQL = "UPDATE sell SET status = 'In Review' WHERE sell_id = :sell_id";
        $updateStmt = $pdo->prepare($updateSQL);
        $updateStmt->bindParam(':sell_id', $sell_id);

        if ($updateStmt->execute()) {

            echo "<script>alert('Sell request accepted and moved to review.');</script>";
            header('Refresh:0'); 
            exit(); 

        } else {

            echo "<script>alert('Error in accepting the request.');</script>";
        }
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Admin - View Sell Requests</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        .requests {
            width: 80%;
            margin: 20px auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th, td {
            padding: 15px;
            text-align: left;
            border: 1px solid #ddd;
        }

        th {
            background-color: #f2f2f2;
        }

        .images-container {
            display: flex;
            gap: 10px;
        }

        .images-container img {
            width: 100px;  /* Ensures uniform size */
            height: 100px; /* Fixed height to keep images equal */
            object-fit: cover; /* Makes sure the images are cropped evenly */
            border-radius: 5px; /* Optional, for rounded corners */
        }

        .btn {
            padding: 8px 16px;
            margin: 5px;
            cursor: pointer;
            border: none;
            border-radius: 5px;
        }

        .accept-btn {
            background-color: #4CAF50;
            color: white;
        }

        .decline-btn {
            background-color: #f44336;
            color: white;
        }

        .decline-btn:hover, .accept-btn:hover {
            opacity: 0.8;
        }
    </style>
</head>
<body>
    <!-- Admin Header -->
    <?php include 'adminHeader.php'; ?>
    
    <h2 style="text-align: center;">Sell Requests</h2>

    <div class="requests">
        <?php if ($sellRequests): ?>
            <table>
                <thead>
                    <tr>
                        <th>Sell ID</th>
                        <th>Customer Name</th>
                        <th>Description</th>
                        <th>Price</th>
                        <th>Images</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($sellRequests as $request): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($request['sell_id']); ?></td>
                            <td><?php echo htmlspecialchars($request['customer_name']); ?></td>
                            <td><?php echo htmlspecialchars($request['description']); ?></td>
                            <td><?php echo htmlspecialchars($request['price']); ?></td>
                            <td>
                                <div class="images-container">
                                    <img src="data:image/jpeg;base64,<?php echo $request['image1']; ?>" alt="Front Image">
                                    <img src="data:image/jpeg;base64,<?php echo $request['image2']; ?>" alt="Back Image">
                                    <img src="data:image/jpeg;base64,<?php echo $request['image3']; ?>" alt="Aerial Image">
                                </div>
                            </td>
                            <td>
                                <!-- Accept Button -->
                                <form action="sell_view.php" method="POST" style="display:inline-block;">
                                    <input type="hidden" name="accept_id" value="<?php echo htmlspecialchars($request['sell_id']); ?>">
                                    <button type="submit" class="btn accept-btn">Accept</button>
                                </form>

                                <!-- Decline Button -->
                                <form action="sell_view.php" method="POST" style="display:inline-block;">
                                    <input type="hidden" name="decline_id" value="<?php echo htmlspecialchars($request['sell_id']); ?>">
                                    <button type="submit" class="btn decline-btn">Decline</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No sell requests found.</p>
        <?php endif; ?>
    </div>
</body>
</html>

