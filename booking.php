<?php

    // Ensure the session is active
    require_once "Config/config.inc.php"; //Session

    if (session_status() !== PHP_SESSION_ACTIVE) {
        die("Session not active.");
    }

    // Get the database connection instance
    require_once "database/DBConn.inc.php";

    $pdo = DatabaseConnection::getInstance()->getConnection();

    // Handle form submission
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $customer_id = $_SESSION['user_id'];
        $duration = $_POST['duration'];
        $booking_time = $_POST['booking_time'];
        $booked_date = $_POST['booked_date'];

        try {

            // Insert new booking into the database
            $stmt = $pdo->prepare("INSERT INTO bookings (customer_id, duration, booking_time, booked_date) VALUES (?, ?, ?, ?)");
            $stmt->execute([$customer_id, $duration, $booking_time, $booked_date]);

            /* Response back to user (Javascript Alert)
            echo "<script>
                    alert('Booking successfully submitted!');
                    window.location.href = 'booking.php';  
                </script>";*/
            exit();
            
        } catch (Exception $e) {
            
            error_log("Booking error: " . $e->getMessage());
            echo "<script>
                    alert('Booking failed: " . $e->getMessage() . "');
                    window.location.href = 'booking.php';    
                </script>";
            }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Internet Session Booking Form</title>

    <link rel="stylesheet" href="CSS/style.css">
        <link rel="stylesheet" href="CSS/styles.css">

    <style>
        .form {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .booking-container {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
            width: 400px;
        }

        h1 {
            font-size: 24px;
            margin-bottom: 20px;
        }

        label {
            margin: 10px 0;
            display: block;
        }

        select, input[type="date"] {
            width: 100%;
            padding: 8px;
            margin: 10px 0;
            border-radius: 4px;
            border: 1px solid #ccc;
        }

        button {
            background-color: #4CAF50;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background-color: #45a049;
        }
    </style>
</head>
    <body>

        <!--header-->
        <?php include 'Header.php'; ?>
        <div class="form">
            <div class="booking-container">

                <!--content-->
                <h1>Internet Session Booking</h1>
                <form method="POST">
                    <label for="duration">Select Duration:</label>
                    <select name="duration" id="duration" required>
                        <option value="20min">R10 for 20 minutes</option>
                        <option value="1hr30min">R50 for 1 hour 30 minutes</option>
                        <option value="2hr">R100 for 2 hours</option>
                    </select>

                    <label for="booking_time">Booking Time:</label>
                    <select name="booking_time" id="booking_time" required>
                        <option value="8:00-10:00">8:00 - 10:00</option>
                        <option value="10:00-12:00">10:00 - 12:00</option>
                        <option value="12:00-14:00">12:00 - 14:00</option>
                        <option value="14:00-16:00">14:00 - 16:00</option>
                    </select>

                    <label for="booked_date">Booking Date:</label>
                    <input type="date" name="booked_date" id="booked_date" required>

                    <button type="submit">Book Now</button>
                </form>
            </div>
        </div>

        <script>
            // Limit booking dates to the upcoming week
            window.onload = function() {
                const today = new Date();
                const maxDate = new Date();
                maxDate.setDate(today.getDate() + 7); // Set max date to 7 days from today

                // Format date to YYYY-MM-DD for input[type="date"]
                const formatDate = (date) => {
                    const d = new Date(date);
                    let month = '' + (d.getMonth() + 1);
                    let day = '' + d.getDate();
                    const year = d.getFullYear();

                    if (month.length < 2) month = '0' + month;
                    if (day.length < 2) day = '0' + day;

                    return [year, month, day].join('-');
                }

                const dateInput = document.getElementById('booked_date');
                dateInput.setAttribute('min', formatDate(today));  // Today's date
                dateInput.setAttribute('max', formatDate(maxDate)); // 7 days from today
            };
        </script>

    </body>
</html>
