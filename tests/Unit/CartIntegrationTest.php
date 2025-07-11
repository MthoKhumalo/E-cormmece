<?php
use PHPUnit\Framework\TestCase;

class CartIntegrationTest extends TestCase {
    private $pdo;

    protected function setUp(): void {
        $this->pdo = new PDO('sqlite::memory:');
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Create tables and seed data
        $this->pdo->exec("
            CREATE TABLE carts (
                cart_id INTEGER PRIMARY KEY AUTOINCREMENT,
                customer_id INTEGER,
                cart_created TEXT,
                status TEXT DEFAULT 'active'
            );
        ");
        $this->pdo->exec("
            CREATE TABLE cart_items (
                item_id INTEGER PRIMARY KEY AUTOINCREMENT,
                cart_id INTEGER,
                product_id INTEGER,
                quantity INTEGER DEFAULT 1
            );
        ");
        $this->pdo->exec("
            CREATE TABLE products (
                product_id INTEGER PRIMARY KEY,
                pName TEXT,
                price REAL,
                Quantity INTEGER
            );
        ");
        $this->pdo->exec("
            INSERT INTO products (product_id, pName, price, Quantity)
            VALUES (1, 'Product A', 100, 10);
        ");
    }

    public function testAddProductToCart(): void {
        $this->pdo->exec("INSERT INTO carts (customer_id, cart_created, status) VALUES (1, '2024-11-01', 'active')");
        $cart_id = $this->pdo->lastInsertId();

        $stmt = $this->pdo->prepare("INSERT INTO cart_items (cart_id, product_id, quantity) VALUES (?, ?, 1)");
        $stmt->execute([$cart_id, 1]);

        $stmt = $this->pdo->prepare("SELECT * FROM cart_items WHERE cart_id = ?");
        $stmt->execute([$cart_id]);
        $cartItems = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $this->assertCount(1, $cartItems);
    }

    public function testRemoveProductFromCart(): void {
        $this->pdo->exec("INSERT INTO carts (customer_id, cart_created, status) VALUES (1, '2024-11-01', 'active')");
        $cart_id = $this->pdo->lastInsertId();
        $this->pdo->exec("INSERT INTO cart_items (cart_id, product_id, quantity) VALUES ($cart_id, 1, 1)");

        $stmt = $this->pdo->prepare("DELETE FROM cart_items WHERE cart_id = ? AND product_id = ?");
        $stmt->execute([$cart_id, 1]);

        $stmt = $this->pdo->prepare("SELECT * FROM cart_items WHERE cart_id = ?");
        $stmt->execute([$cart_id]);
        $cartItems = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $this->assertEmpty($cartItems);
    }
}
