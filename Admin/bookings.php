<?php
    require_once "../database/DBConn.inc.php";

    // Get the database connection instance
    $pdo = DatabaseConnection::getInstance()->getConnection();

    // Fetch all bookings with 'pending' status
    $statement = $pdo->query("SELECT * FROM bookings WHERE status = 'pending'");
    $bookings = $statement->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

    <head>

        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Admin Bookings</title>
        <style>
            
            .body2 {
                font-family: Arial, sans-serif;
                background-color: #f2f2f2;
            }

            .container {
                width: 80%;
                margin: 0 auto;
                background-color: #fff;
                padding: 20px;
                border-radius: 10px;
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            }

            h1 {
                text-align: center;
                margin-bottom: 20px;
            }

            table {
                width: 100%;
                border-collapse: collapse;
                margin-bottom: 500px;
            }

            table, th, td {
                border: 1px solid #ddd;
                padding: 8px;
            }

            th {
                background-color: #f2f2f2;
                text-align: left;
            }

            button {
                padding: 8px 12px;
                color: white;
                border: none;
                border-radius: 4px;
                cursor: pointer;
            }

            .btn-accept {
                background-color: #4CAF50;
            }

            .btn-decline {
                background-color: #f44336;
            }

            button:hover {
                opacity: 0.9;
            }
        </style>
    </head>
    <body>
        <!-- Include admin header -->
        <?php include 'adminHeader.php'; ?>

        <h1 style="text-align: center;">Booking Requests</h1>

        <div class="body2">
            <div class="container">
                
                <table>

                    <thead>
                        <tr>

                            <th>Booking ID</th>
                            <th>Customer ID</th>
                            <th>Duration</th>
                            <th>Booking Time</th>
                            <th>Booking Date</th>
                            <th>Actions</th>

                        </tr>
                    </thead>
                    
                    <tbody id="booking-table">
                        <?php foreach ($bookings as $booking): ?>

                            <tr id="booking-<?php echo $booking['booking_id']; ?>">

                                <td><?php echo $booking['booking_id']; ?></td>
                                <td><?php echo $booking['customer_id']; ?></td>
                                <td><?php echo $booking['duration']; ?></td>
                                <td><?php echo $booking['booking_time']; ?></td>
                                <td><?php echo $booking['booked_date']; ?></td>
                                <td>
                                    <button class="btn-accept" onclick="updateBookingStatus(<?php echo $booking['booking_id']; ?>, 'accepted')">Accept</button>
                                    <button class="btn-decline" onclick="updateBookingStatus(<?php echo $booking['booking_id']; ?>, 'declined')">Decline</button>
                                </td>

                            </tr>
                        <?php endforeach; ?>
                    </tbody>

                </table>
            </div>
        </div>

        <script>
            // JavaScript function to update booking status and show messages
            function updateBookingStatus(bookingId, status) {

                const confirmed = confirm(`Are you sure you want to mark this booking as '${status}'?`);
                if (!confirmed) return;

                const xhr = new XMLHttpRequest();
                xhr.open('POST', 'update_booking_status.php', true);
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

                xhr.onload = function () {

                    if (this.status === 200) {

                        alert(`Booking has been ${status}.`);

                        // Remove the booking from the table
                        const row = document.getElementById(`booking-${bookingId}`);
                        row.parentNode.removeChild(row);

                    } else {

                        alert('Error updating booking status. Please try again.');
                    }
                };
                
                xhr.send(`booking_id=${bookingId}&status=${status}`);
            }

        </script>

    </body>
</html>
