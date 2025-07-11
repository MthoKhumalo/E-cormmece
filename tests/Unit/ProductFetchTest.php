<?php

    use PHPUnit\Framework\TestCase;

    require_once(__DIR__ . '/../../database/DBConn.inc.php');

    class ProductFetchTest extends TestCase
    {
        private $pdo;

        protected function setUp(): void
        {
            // Get the database connection
            $this->pdo = DatabaseConnection::getInstance()->getConnection();
        }

        public function testProductFetch()
        {
            try {
                $query = "SELECT * FROM products WHERE is_deleted = 0";
                $stmt = $this->pdo->prepare($query);
                $stmt->execute();

                $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

                // Assert that products are fetched successfully
                $this->assertNotEmpty($products, "No products were fetched.");
            } catch (PDOException $e) {
                $this->fail("Database query failed: " . $e->getMessage());
            }
        }
    }
