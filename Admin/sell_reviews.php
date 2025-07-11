<?php
    // Ensure session and database connection
    require_once '../database/DBConn.inc.php'; 

    // Get the database connection instance
    $pdo = DatabaseConnection::getInstance()->getConnection();

    // Fetch all sell requests that are 'In Review'
    $sql = "SELECT sell.sell_id, sell.customer_id, sell.image1, sell.image2, sell.image3, sell.description, sell.price, 
                customers.firstName AS customer_name
            FROM sell 
            JOIN customers ON sell.customer_id = customers.customer_id
            WHERE sell.status = 'In Review'";

    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $reviewRequests = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Handle Complete or Decline after Review action
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        $sell_id = $_POST['sell_id'];
        $action = $_POST['action'];

        // Update the status based on the action
        if ($action == 'complete') {

            $status = 'Complete';

        } elseif ($action == 'decline') {

            $status = 'Declined after Review';
        }

        $updateSQL = "UPDATE sell SET status = :status WHERE sell_id = :sell_id";
        $updateStmt = $pdo->prepare($updateSQL);
        $updateStmt->bindParam(':status', $status);
        $updateStmt->bindParam(':sell_id', $sell_id);

        if ($updateStmt->execute()) {

            echo "<script>alert('Sell request status updated to $status');</script>";
            header('Refresh:0');

        } else {

            echo "<script>alert('Error updating sell request status.');</script>";
        }
    }

?>

<!DOCTYPE html>
<html lang="en">

    <head>

        <title>Admin - Review Sell Requests</title>

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

            img {
                width: 100px;
                height: auto;
            }
            
            .btn {
                padding: 8px 16px;
                margin: 5px;
                cursor: pointer;
                border: none;
                border-radius: 5px;
            }

            .complete-btn {
                background-color: #4CAF50;
                color: white;
            }

            .decline-btn {
                background-color: #f44336;
                color: white;
            }

            .decline-btn:hover, .complete-btn:hover {
                opacity: 0.8;
            }

        </style>

    </head>

    <body>

        <!-- Admin Header -->
        <?php include 'adminHeader.php'; ?>
        
        <h2 style="text-align: center;">Review Sell Requests</h2>

        <div class="requests">
            <?php if ($reviewRequests): ?>
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
                        <?php foreach ($reviewRequests as $request): ?>

                            <tr>

                                <td><?php echo htmlspecialchars($request['sell_id']); ?></td>
                                <td><?php echo htmlspecialchars($request['customer_name']); ?></td>
                                <td><?php echo htmlspecialchars($request['description']); ?></td>
                                <td><?php echo htmlspecialchars($request['price']); ?></td>
                                <td>
                                    <img src="data:image/jpeg;base64,<?php echo $request['image1']; ?>" alt="Front Image">
                                    <img src="data:image/jpeg;base64,<?php echo $request['image2']; ?>" alt="Back Image">
                                    <img src="data:image/jpeg;base64,<?php echo $request['image3']; ?>" alt="Aerial Image">
                                </td>
                                <td>
                                    <!-- Complete Button -->
                                    <form action="sell_reviews.php" method="POST" style="display:inline-block;">

                                        <input type="hidden" name="sell_id" value="<?php echo htmlspecialchars($request['sell_id']); ?>">
                                        <input type="hidden" name="action" value="complete">
                                        <button type="submit" class="btn complete-btn">Complete</button>

                                    </form>

                                    <!-- Decline Button -->
                                    <form action="sell_reviews.php" method="POST" style="display:inline-block;">

                                        <input type="hidden" name="sell_id" value="<?php echo htmlspecialchars($request['sell_id']); ?>">
                                        <input type="hidden" name="action" value="decline">
                                        <button type="submit" class="btn decline-btn">Decline</button>

                                    </form>

                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

            <?php else: ?>

                <p>No sell requests for review.</p>

            <?php endif; ?>
            
        </div>
    </body>
</html>
