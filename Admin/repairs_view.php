<?php

    require_once '../database/DBConn.inc.php';
    require_once "../Config/config.inc.php";  

    // Get the database connection instance
    $pdo = DatabaseConnection::getInstance()->getConnection();

    // Check if an admin action was taken (accept or decline)
    if (isset($_POST['action']) && isset($_POST['repair_id'])) {

        $repair_id = $_POST['repair_id'];
        $action = $_POST['action'];
        
        // Update status based on the action
        if ($action == 'accept') {

            // Change status to 'In Review' instead of 'Accepted'
            $sql = "UPDATE repairs SET status = 'In Review' WHERE repair_id = :repair_id";

        } elseif ($action == 'decline') {

            $sql = "UPDATE repairs SET status = 'Declined' WHERE repair_id = :repair_id";
        }

        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':repair_id', $repair_id);

        if ($stmt->execute()) {

            echo "<script>alert('Repair request updated successfully');</script>";

        } else {

            echo "<script>alert('Error updating repair request');</script>";
        }
    }

    // Fetch all pending repair requests
    $sql = "SELECT * FROM repairs WHERE status = 'Pending'";
    $stmt = $pdo->query($sql);
    $repairs = $stmt->fetchAll();

?>

<!DOCTYPE html>
<html lang="en">
<html>
    <head>

        <title>Admin Repairs View</title>

        <style>

            body {
                font-family: Arial, sans-serif;
                background-color: #f4f4f4;
                margin: 0;
                padding: 0;
            }

            .container {
                width: 80%;
                margin: 20px auto;
                background-color: #fff;
                padding: 20px;
                border-radius: 10px;
                box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            }

            h2 {
                text-align: center;
                color: #333;
            }

            table {
                width: 100%;
                border-collapse: collapse;
                margin-bottom: 20px;
            }

            table, th, td {
                border: 1px solid #ddd;
                padding: 8px;
            }

            th {
                background-color: #f2f2f2;
                text-align: left;
            }

            img {
                width: 100px;
                height: auto;
            }

            button {
                padding: 8px 12px;
                color: white;
                border: none;
                border-radius: 4px;
                cursor: pointer;
                margin-right: 5px;
            }

            .btn-accept {
                background-color: #4CAF50;
            }

            .btn-decline {
                background-color: #f44336;
            }

            button:hover {
                opacity: 0.8;
            }

        </style>

    </head>

    <body>
        <!-- Include Admin Header -->
        <?php include 'AdminHeader.php'; ?>

        <div class="container">

            <h2>Pending Repair Requests</h2>

            <table>

                <thead>
                    <tr>

                        <th>Repair ID</th>
                        <th>Customer ID</th>
                        <th>Description</th>
                        <th>Image</th>
                        <th>Booked Date</th>
                        <th>Actions</th>

                    </tr>
                </thead>

                <tbody>

                    <?php foreach ($repairs as $repair): ?>
                    <tr>

                        <td><?php echo $repair['repair_id']; ?></td>
                        <td><?php echo $repair['customer_id']; ?></td>
                        <td><?php echo htmlspecialchars($repair['description']); ?></td>
                        <td>
                            <img src="data:image/jpeg;base64,<?php echo $repair['image']; ?>" alt="Repair Image">
                        </td>
                        <td><?php echo $repair['booked_date']; ?></td>
                        <td>
                            <form method="post" action="repairs_view.php">

                                <input type="hidden" name="repair_id" value="<?php echo $repair['repair_id']; ?>">
                                <!-- Updated button logic: Accept sets status to 'In Review' -->
                                <button type="submit" name="action" value="accept" class="btn-accept">Accept</button>
                                <button type="submit" name="action" value="decline" class="btn-decline">Decline</button>

                            </form>
                        </td>

                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

        </div>
    </body>
</html>
