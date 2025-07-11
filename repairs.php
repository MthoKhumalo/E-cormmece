<?php
    require_once 'database/DBConn.inc.php';
    require_once "Config/config.inc.php";  

    // Get the database connection instance
    $pdo = DatabaseConnection::getInstance()->getConnection();

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Retrieve form data
        $description = htmlspecialchars($_POST['description']);
        
        // Check which radio button was selected (for day and time)
        $selected_day = '';
        $selected_time = '';

        if (isset($_POST['monday'])) {

            $selected_day = 'Monday';
            $selected_time = $_POST['monday'];

        } elseif (isset($_POST['tuesday'])) {

            $selected_day = 'Tuesday';
            $selected_time = $_POST['tuesday'];

        } elseif (isset($_POST['wednesday'])) {
            
            $selected_day = 'Wednesday';
            $selected_time = $_POST['wednesday'];

        } elseif (isset($_POST['thursday'])) {
            
            $selected_day = 'Thursday';
            $selected_time = $_POST['thursday'];

        } elseif (isset($_POST['friday'])) {
            
            $selected_day = 'Friday';
            $selected_time = $_POST['friday'];

        } elseif (isset($_POST['saturday'])) {
            
            $selected_day = 'Saturday';
            $selected_time = $_POST['saturday'];

        } 

        // Set date and time for booked_date field
        $booked_date = date('Y-m-d') . ' ' . $selected_time;

        // Handle image upload
        $target_file = file_get_contents($_FILES["image"]["tmp_name"]);
        // Convert images to base64
        $front_image_base64 = base64_encode($target_file);

        // Assuming customer_id is retrieved based on login session (example)
        $customer_id = $_SESSION['user_id']; 

        // Insert the data into the repairs table
        $sql = "INSERT INTO repairs (customer_id, image, description, booked_date) 
                VALUES (:customer_id, :image, :description, :booked_date)";

        $stmt = $pdo->prepare($sql);

        // Bind parameters
        $stmt->bindParam(':customer_id', $customer_id);
        $stmt->bindParam(':image', $front_image_base64);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':booked_date', $booked_date);

        if ($stmt->execute()) {

            echo "<script>
                    alert('Repair request submitted successfully');
                </script>";
                
        } else {
            echo "<script>
                    alert('Error submitting repair request');
                </script>";
        }

    }
?>

<!DOCTYPE html>
<html lang="en">
<html>

    <head>
        <title> Repairs </title>
        <link rel="stylesheet" href="CSS/style.css">
        <link rel="stylesheet" href="CSS/styles.css">
    </head>

    <body>

        <!--header-->
        <?php include 'Header.php'; ?>

        <!--content-->
        <div class="forms">
            <form action="repairs.php" method="post" enctype="multipart/form-data">

                <h2>REPAIRS</h2><br><br>

                <label for="image">Upload Image:</label>
                <input type="file" id="image" name="image" accept="image/*" required><br><br>

                <label for="description">Description:</label><br>
                <textarea id="description" name="description" rows="4" cols="50" required></textarea>

                <table>

                    <thead>
                        <tr>

                            <th>Day</th>
                            <th>08:00-10:00</th>
                            <th>12:00-14:00</th>
                            <th>16:00-18:00</th>

                        </tr>
                    </thead>

                    <tbody>

                        <tr>

                            <td>Monday</td>
                            <td><input type="radio" name="monday" value="08:00:00-10:00:00"></td>
                            <td><input type="radio" name="monday" value="12:00:00-14:00:00"></td>
                            <td><input type="radio" name="monday" value="16:00:00-18:00"></td>

                        </tr>

                        <tr>
                            <td>Tuesday</td>
                            <td><input type="radio" name="tuesday" value="08:00:00-10:00:00"></td>
                            <td><input type="radio" name="tuesday" value="12:00:00-14:00:00"></td>
                            <td><input type="radio" name="tuesday" value="16:00:00-17:00:00"></td>
                        </tr>

                        <tr>

                            <td>Wednesday</td>
                            <td><input type="radio" name="wednesday" value="08:00:00-10:00:00"></td>
                            <td><input type="radio" name="wednesday" value="12:00:00-14:00:00"></td>
                            <td><input type="radio" name="wednesday" value="16:00:00-18:00"></td>

                        </tr>

                        <tr>

                            <td>Thursday</td>
                            <td><input type="radio" name="thursday" value="08:00:00-10:00:00"></td>
                            <td><input type="radio" name="thursday" value="12:00:00-14:00:00"></td>
                            <td><input type="radio" name="thursday" value="16:00:00-18:00"></td>

                        </tr>

                        <tr>

                            <td>Friday</td>
                            <td><input type="radio" name="friday" value="08:00:00-10:00:00"></td>
                            <td><input type="radio" name="friday" value="12:00:00-14:00:00"></td>
                            <td><input type="radio" name="friday" value="16:00:00-18:00"></td>

                        </tr>

                        <tr>

                            <td>Saturday</td>
                            <td><input type="radio" name="saturday" value="08:00:00-10:00:00"></td>
                            <td><input type="radio" name="saturday" value="12:00:00-14:00:00"></td>
                            <td><input type="radio" name="saturday" value="16:00:00-18:00"></td>

                        </tr>

                    </tbody>
                </table>

                <button type="submit">Submit</button>

            </form>

        </div>

        <!--footer-->
        <?php include 'Footer.php'; ?>
        
    </body>
</html>
