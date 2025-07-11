<?php

    require_once 'database/DBConn.inc.php';
    require_once "Config/config.inc.php";  

    // Get the database connection instance
    $pdo = DatabaseConnection::getInstance()->getConnection();

    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        // Get form data
        $description = htmlspecialchars($_POST['description']);
        $price = $_POST['price'];

        // Customer ID 
        $customer_id = $_SESSION['user_id'];  

        // Handle image upload 
        $front_image = file_get_contents($_FILES['front_image']['tmp_name']);
        $back_image = file_get_contents($_FILES['back_image']['tmp_name']);
        $aerial_image = file_get_contents($_FILES['aerial_image']['tmp_name']);

        // Convert images to base64
        $front_image_base64 = base64_encode($front_image);
        $back_image_base64 = base64_encode($back_image);
        $aerial_image_base64 = base64_encode($aerial_image);

        // Prepare SQL statement
        $sql = "INSERT INTO sell (customer_id, image1, image2, image3, description, price) 
                VALUES (:customer_id, :image1, :image2, :image3, :description, :price)";

        $stmt = $pdo->prepare($sql);

        // Bind parameters
        $stmt->bindParam(':customer_id', $customer_id);
        $stmt->bindParam(':image1', $front_image_base64);
        $stmt->bindParam(':image2', $back_image_base64);
        $stmt->bindParam(':image3', $aerial_image_base64);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':price', $price);

        // Execute statement
        if ($stmt->execute()) {

            echo "<script>
                    alert('Review request in progress');
                    window.location.href = 'sell.php';  
                </script>";

        } else {

            // Error handling
            echo "Error saving data. Please try again.";
        }
    }

?>

<!DOCTYPE html>
<html lang="en">
<html>

    <head>
        <title> Selling </title>
        <link rel="stylesheet" href="CSS/style.css">
    </head>

    <body>

        <!-- header -->
        <?php include 'Header.php'; ?>

        <h2 class="headings">Sell Products Form</h2>

        <div class="forms">

            <form action="sell.php" method="post" enctype="multipart/form-data">

                <div class="form-group">

                    <label for="front_image">Front Image:</label>
                    <input type="file" id="front_image" name="front_image" accept="image/*" required>

                </div><br>
                
                <div class="form-group">

                    <label for="back_image">Back Image:</label>
                    <input type="file" id="back_image" name="back_image" accept="image/*" required>

                </div><br>

                <div class="form-group">

                    <label for="aerial_image">Aerial Image:</label>
                    <input type="file" id="aerial_image" name="aerial_image" accept="image/*" required>

                </div><br>

                <div class="form-group">

                    <label for="description">Description:</label><br>
                    <textarea id="description" name="description" rows="4" cols="50" required></textarea>

                </div><br>

                <div class="form-group">

                    <label for="price">Price:</label>
                    <input type="number" id="price" name="price" min="0" step="0.01" required>

                </div><br>

                <button type="submit">Sell</button>

            </form>

        </div>
        
        <!-- footer -->
        <?php include 'Footer.php'; ?>
        
    </body>
</html>
