<?php
use PHPUnit\Framework\TestCase;

class PaymentIntegrationTest extends TestCase {
    private $pdo;

    protected function setUp(): void {
        $this->pdo = new PDO('sqlite::memory:');
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Create and seed necessary tables
        $this->pdo->exec("
            CREATE TABLE orders (
                order_id INTEGER PRIMARY KEY,
                custumer_name TEXT,
                order_date TEXT,
                totalPrice REAL,
                customer_id INTEGER,
                cart_id INTEGER
            );
            CREATE TABLE payments (
                payment_id INTEGER PRIMARY KEY,
                payment_type TEXT,
                order_id INTEGER
            );
            CREATE TABLE carts (
                cart_id INTEGER PRIMARY KEY,
                status TEXT DEFAULT 'active'
            );
        ");

        // Seed data
        $this->pdo->exec("
            INSERT INTO orders (order_id, custumer_name, order_date, totalPrice, customer_id, cart_id)
            VALUES (1, 'John Doe', '2024-11-01', 200.0, 1, 1);
            INSERT INTO carts (cart_id, status) VALUES (1, 'active');
        ");
    }

    public function testCompletePayment(): void {
        // Add payment
        $stmt = $this->pdo->prepare("INSERT INTO payments (payment_type, order_id) VALUES (?, ?)");
        $stmt->execute(['Credit Card', 1]);

        // Verify payment
        $stmt = $this->pdo->prepare("SELECT * FROM payments WHERE order_id = ?");
        $stmt->execute([1]);
        $payment = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->assertNotEmpty($payment, "Payment should be recorded");

        // Update cart status
        $stmt = $this->pdo->prepare("UPDATE carts SET status = 'completed' WHERE cart_id = ?");
        $stmt->execute([1]);

        // Verify cart status
        $stmt = $this->pdo->prepare("SELECT status FROM carts WHERE cart_id = ?");
        $stmt->execute([1]);
        $cart = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->assertEquals('completed', $cart['status'], "Cart status should be 'completed'");
    }
}
