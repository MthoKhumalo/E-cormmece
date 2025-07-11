<?php
use PHPUnit\Framework\TestCase;

class RemoveFromCartIntegrationTest extends TestCase {
    private $pdo;

    protected function setUp(): void {
        $this->pdo = new PDO('sqlite::memory:');
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Create and seed necessary tables
        $this->pdo->exec("
            CREATE TABLE carts (
                cart_id INTEGER PRIMARY KEY,
                customer_id INTEGER,
                status TEXT DEFAULT 'active'
            );
            CREATE TABLE cart_items (
                cart_item_id INTEGER PRIMARY KEY,
                cart_id INTEGER,
                product_id INTEGER,
                quantity INTEGER
            );
        ");

        // Seed data
        $this->pdo->exec("
            INSERT INTO carts (cart_id, customer_id, status) VALUES (1, 1, 'active');
            INSERT INTO cart_items (cart_id, product_id, quantity) VALUES (1, 1, 2);
        ");
    }

    public function testRemoveFromCart(): void {
        // Remove product from cart
        $stmt = $this->pdo->prepare("DELETE FROM cart_items WHERE cart_id = ? AND product_id = ?");
        $stmt->execute([1, 1]);

        // Verify removal
        $stmt = $this->pdo->prepare("SELECT * FROM cart_items WHERE cart_id = ?");
        $stmt->execute([1]);
        $cartItems = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $this->assertEmpty($cartItems, "Cart should be empty after removal");
    }
}
