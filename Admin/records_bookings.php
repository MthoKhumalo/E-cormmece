<?php

    require_once "../database/DBConn.inc.php"; 

    // Get the database connection instance
    $pdo = DatabaseConnection::getInstance()->getConnection();

    $bookings = [];
    $error = '';
    $totalBookings = 0;
    $totalDuration = 0;
    $averageDuration = 0;

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        try {

            $filters = [];
            $params = [];

            $query = "SELECT b.booking_id, CONCAT(c.firstName, ' ', c.lastName) AS customer_name, b.duration, b.booking_time, b.booked_date, b.status 
                    FROM bookings b 
                    JOIN customers c ON b.customer_id = c.customer_id 
                    WHERE 1=1";

            // Booking_id filter
            if (!empty($_POST['booking_id'])) {
                $query .= " AND b.booking_id = ?";
                $filters[] = $_POST['booking_id'];
            }

            // Customer name filter
            if (!empty($_POST['customer_name'])) {
                $query .= " AND (c.firstName LIKE ? OR c.lastName LIKE ?)";
                $nameFilter = "%" . $_POST['customer_name'] . "%";
                $filters[] = $nameFilter;
                $filters[] = $nameFilter;
            }

            // Booking_time filter
            if (!empty($_POST['booking_time'])) {
                $query .= " AND b.booking_time = ?";
                $filters[] = $_POST['booking_time'];
            }

            /* // Booked_date filter
            if (!empty($_POST['booked_date'])) {
                $query .= " AND b.booked_date >= ?";
                $filters[] = $_POST['booked_date'];
            }*/

            // Status filter
            if (!empty($_POST['status'])) {
                $query .= " AND b.status = ?";
                $filters[] = $_POST['status'];
            }

            // Prepare and execute statement (without min_duration)
            $stmt = $pdo->prepare($query);
            $stmt->execute($filters);
            $allBookings = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Function to convert duration to minutes
            function convertDurationToMinutes($duration) {
                if (strpos($duration, 'min') !== false) {
                    return (int)$duration; // Convert '20min' to 20
                } elseif (strpos($duration, 'hr') !== false) {
                    // Extract hours and minutes if present, e.g., '1hr30min'
                    preg_match('/(\d+)hr/', $duration, $hours);
                    preg_match('/(\d+)min/', $duration, $minutes);

                    $totalMinutes = 0;
                    if (!empty($hours)) {
                        $totalMinutes += (int)$hours[1] * 60; // Convert hours to minutes
                    }
                    if (!empty($minutes)) {
                        $totalMinutes += (int)$minutes[1];
                    }
                    return $totalMinutes;
                }
                return 0; // Return 0 if format is unexpected
            }

            // Apply min_duration filter after fetching results
            $minDuration = !empty($_POST['min_duration']) ? (int)$_POST['min_duration'] : 0;
            $bookings = array_filter($allBookings, function($booking) use ($minDuration) {
                $durationInMinutes = convertDurationToMinutes($booking['duration']);
                return $durationInMinutes >= $minDuration;
            });

            // Calculate the total duration and count total bookings
            $totalBookings = count($bookings);
            $totalDuration = 0;

            foreach ($bookings as $booking) {
                $totalDuration += convertDurationToMinutes($booking['duration']);
            }

            // Calculate the average duration
            if ($totalBookings > 0) {
                $averageDuration = $totalDuration / $totalBookings;
            }

        } catch (Exception $e) {
            $error = "An error occurred while fetching bookings: " . $e->getMessage();
        }
    }
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Records</title>

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

        input, select, button {
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

        th, td {
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

        .report-section p {
            padding: 10px;
            margin: 10px;
            width: calc(35% - 40px);
            border: 1px solid #ccc;
            border-radius: 4px;
        }
    </style>
</head>

<body>

    <?php include 'adminHeader.php'; ?>

    <h1>Search Booking Records</h1>

    <div class="container">

        <form method="POST">
            <input type="text" name="booking_id" placeholder="Enter Booking ID">
            <input type="text" name="customer_name" placeholder="Enter Customer Name">
            <input type="number" name="min_duration" placeholder="Minimum Duration (minutes)">
            <!--<input type="date" name="booked_date" placeholder="Booked Date">-->
            <select name="status">
                <option value="">--Select Status--</option>
                <option value="pending">Pending</option>
                <option value="accepted">Accepted</option>
                <option value="declined">Declined</option>
            </select>

            <button type="submit">Search</button>
        </form>

        <?php if ($error): ?>
            <div class="error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

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
                <?php if (!empty($bookings)): ?>
                    <?php foreach ($bookings as $booking): ?>
                        <tr>
                            <td><?= htmlspecialchars($booking['booking_id']) ?></td>
                            <td><?= htmlspecialchars($booking['customer_name']) ?></td>
                            <td><?= htmlspecialchars($booking['duration']) ?></td>
                            <td><?= htmlspecialchars($booking['booking_time']) ?></td>
                            <td><?= htmlspecialchars($booking['booked_date']) ?></td>
                            <td><?= htmlspecialchars($booking['status']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6">No bookings found based on the search criteria.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    
    <div class="report-section">
        <?php if ($totalBookings > 0): ?>
        <h2>Booking Summary</h2>
        <p><strong>Total Number of Bookings:</strong> <?= $totalBookings ?></p>
        <p><strong>Total Duration of Bookings:</strong> <?= $totalDuration ?> minutes</p>
        <p><strong>Average Booking Duration:</strong> <?= number_format($averageDuration, 2) ?> minutes</p>
        <?php endif; ?>
    </div>

</body>
</html>
