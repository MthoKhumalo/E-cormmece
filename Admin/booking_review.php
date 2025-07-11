<?php
    // Ensuring session connection
    require_once '../database/DBConn.inc.php'; 

    // Get the database connection instance
    $pdo = DatabaseConnection::getInstance()->getConnection();

    // SQL query to fetch booking details and customer name
    $sql = "SELECT bookings.booking_id, bookings.duration, bookings.booking_time, bookings.booked_date, bookings.status,
                CONCAT(customers.firstName, ' ', customers.lastName) AS customer_name
            FROM bookings
            JOIN customers ON bookings.customer_id = customers.customer_id"; // Join customers to get the name

    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $bookingDetails = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">

    <head>

        <title>Booking Review Page</title>

        <style>

           body {
                font-family: Arial, sans-serif;
            }

            .bookings {
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

        </style>

    </head>

    <body>

        <!-- Admin Header -->
        <?php include 'adminHeader.php'; ?>
        
        <h2 style="text-align: center;">Booking Reviews</h2>

        <div class="bookings">
            <?php if ($bookingDetails): ?>

                <table>

                    <thead>
                        <tr>

                            <th>Booking ID</th>
                            <th>Customer Name</th>
                            <th>Duration</th>
                            <th>Booking Time</th>
                            <th>Booked Date</th>
                            <th>Status</th>

                        </tr>
                    </thead>

                    <tbody>
                        <?php foreach ($bookingDetails as $booking): ?>

                            <tr>
                                
                                <td><?php echo htmlspecialchars($booking['booking_id']); ?></td>
                                <td><?php echo htmlspecialchars($booking['customer_name']); ?></td>
                                <td><?php echo htmlspecialchars($booking['duration']); ?></td>
                                <td><?php echo htmlspecialchars($booking['booking_time']); ?></td>
                                <td><?php echo htmlspecialchars($booking['booked_date']); ?></td>
                                <td><?php echo htmlspecialchars($booking['status']); ?></td>

                            </tr>

                        <?php endforeach; ?>
                    </tbody>

                </table>

            <?php else: ?>

                <p>No bookings found.</p>

            <?php endif; ?>
        </div>
    </body>
</html>
