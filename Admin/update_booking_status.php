<?php

    require_once "../database/DBConn.inc.php";

    // Get the database connection instance
    $pdo = DatabaseConnection::getInstance()->getConnection();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $booking_id = $_POST['booking_id'];
        $status = $_POST['status'];

        try {
            // Update booking status in the database
            $stmt = $pdo->prepare("UPDATE bookings SET status = ? WHERE booking_id = ?");
            $stmt->execute([$status, $booking_id]);

            echo "Success"; // Response to the JavaScript for success

        } catch (PDOException $e) {

            http_response_code(500); // Server error response
            echo "Error: " . $e->getMessage();
            
        }
    }
