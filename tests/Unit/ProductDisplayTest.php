<?php
use PHPUnit\Framework\TestCase;

class ProductDisplayIntegrationTest extends TestCase {
    private $pdo;

    protected function setUp(): void {
        $this->pdo = new PDO('sqlite::memory:');
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Create and seed products table
        $this->pdo->exec("
            CREATE TABLE products (
                product_id INTEGER PRIMARY KEY,
                pName TEXT,
                description TEXT,
                price REAL,
                category TEXT,
                images TEXT,
                is_deleted INTEGER DEFAULT 0
            );
        ");
        $this->pdo->exec("
            INSERT INTO products (pName, description, price, category, images, is_deleted)
            VALUES 
            ('Product A', 'Description A', 100, 'Category 1', '', 0),
            ('Product B', 'Description B', 150, 'Category 2', '', 0);
        ");
    }

    public function testFetchAndGroupProducts(): void {
        $stmt = $this->pdo->prepare("SELECT * FROM products WHERE is_deleted = 0");
        $stmt->execute();
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $categories = array_unique(array_column($products, 'category'));
        $this->assertEquals(['Category 1', 'Category 2'], $categories);
    }
}
