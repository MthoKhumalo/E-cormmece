<?php

    require_once '../../database/DBConn.inc.php';
    require_once '../../Config/config.inc.php';

    // Get the database connection instance
    $pdo = DatabaseConnection::getInstance()->getConnection();

    if ($_SERVER["REQUEST_METHOD"] === "POST") {

        $productName = $_POST['product_name'];
        $description = $_POST['description'];
        $price = $_POST['price'];
        $quantity = $_POST['quantity'];
        $category = $_POST['category'];

        if (!isset($_POST['confirm_changes']) || $_POST['confirm_changes'] !== 'yes') {
            echo "Please confirm the changes before submitting.";
            exit();
        }

        // Handle file upload
        if (isset($_FILES['images']) && $_FILES['images']['error'][0] == UPLOAD_ERR_OK) {
            
            $image = $_FILES['images']['tmp_name'][0];
            $imageType = pathinfo($_FILES['images']['name'][0], PATHINFO_EXTENSION);

            if ($imageType === 'jpg' || $imageType === 'jpeg' || $imageType === 'png') {
                $imageData = file_get_contents($image);
                $imageBase64 = base64_encode($imageData);
            } else {
                echo "Unsupported image format. Please upload JPEG or PNG images.";
                exit();
            }
        } else {
            echo "Image upload failed.";
            exit();
        }

        try {
            $checkQuery = "SELECT product_id FROM products WHERE pName = :pName";
            $checkStmt = $pdo->prepare($checkQuery);
            $checkStmt->bindParam(':pName', $productName);
            $checkStmt->execute();
            $existingProduct = $checkStmt->fetch(PDO::FETCH_ASSOC);

            if ($existingProduct) {
                $updateQuery = "UPDATE products 
                                SET description = :description, price = :price, Quantity = :quantity, category = :category, images = :images 
                                WHERE product_id = :product_id";
                $updateStmt = $pdo->prepare($updateQuery);
                $updateStmt->bindParam(':description', $description);
                $updateStmt->bindParam(':price', $price);
                $updateStmt->bindParam(':quantity', $quantity);
                $updateStmt->bindParam(':category', $category);
                $updateStmt->bindParam(':images', $imageBase64);
                $updateStmt->bindParam(':product_id', $existingProduct['product_id']);
                $updateStmt->execute();

                echo "<script type='text/javascript'>
                        alert('Product updated successfully!');
                        window.location.href = '../addProducts.php';
                    </script>";
            } else {
                $insertQuery = "INSERT INTO products (pName, description, price, Quantity, category, images) 
                                VALUES (:pName, :description, :price, :quantity, :category, :images)";
                $insertStmt = $pdo->prepare($insertQuery);
                $insertStmt->bindParam(':pName', $productName);
                $insertStmt->bindParam(':description', $description);
                $insertStmt->bindParam(':price', $price);
                $insertStmt->bindParam(':quantity', $quantity);
                $insertStmt->bindParam(':category', $category);
                $insertStmt->bindParam(':images', $imageBase64);
                $insertStmt->execute();

                echo "<script type='text/javascript'>
                        alert('Product has been successfully added!');
                        window.location.href = '../addProducts.php';
                    </script>";
            }

        } catch (PDOException $e) {
            die("Error: " . $e->getMessage());
        }

    } else {
        echo "Invalid request method.";
    }