<?php

    require_once('../../database/DBConn.inc.php');

    // Get the database connection instance
    $pdo = DatabaseConnection::getInstance()->getConnection();

    $data = json_decode(file_get_contents("php://input"), true);

    if (isset($data['id']) && isset($data['type']) && isset($data['value'])) {
        $id = $data['id'];
        $type = $data['type'];
        $value = $data['value'];

        $allowedFields = ['pName', 'description', 'price', 'quantity'];
        if (!in_array($type, $allowedFields)) {
            echo json_encode(['success' => false]);
            exit();
        }

        try {
            $query = "UPDATE products SET $type = :value WHERE product_id = :id";
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(':value', $value);
            $stmt->bindParam(':id', $id);
            $stmt->execute();

            echo json_encode(['success' => true]);
        } catch (PDOException $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid request']);
    }