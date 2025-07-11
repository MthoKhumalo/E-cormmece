<?php

    declare (strict_types= 1);

    class ProductModel {
        private $pdo;

        public function __construct($pdo) {

            $this->pdo = $pdo;
        }

        public function getProducts($priceCondition = null, $limit = 5) {

            try {

                $query = "SELECT product_id, pName, description, price, category, images FROM products WHERE is_deleted = 0";
                
                if ($priceCondition === 'lessThan5000') {

                    $query .= " AND price < 5000";
                } elseif ($priceCondition === 'greaterThan10000') {

                    $query .= " AND price > 10000";
                }

                $query .= " LIMIT :limit";
                $stmt = $this->pdo->prepare($query);
                $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
                $stmt->execute();
                return $stmt->fetchAll(PDO::FETCH_ASSOC);

            } catch (PDOException $e) {
                
                error_log("Database error: " . $e->getMessage());
                return [];
            }
        }
    }