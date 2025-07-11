<?php
use PHPUnit\Framework\TestCase;

class PlaceOrderIntegrationTest extends TestCase {
    private $pdo;

    protected function setUp(): void {
        $this->pdo = new PDO('sqlite::memory:');
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Create and seed necessary tables
        $this->pdo->exec("
            CREATE TABLE customers (
                customer_id INTEGER PRIMARY KEY,
                firstName TEXT
            );
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
            CREATE TABLE products (
                product_id INTEGER PRIMARY KEY,
                pName TEXT,
                price REAL,
                Quantity INTEGER
            );
            CREATE TABLE orders (
                order_id INTEGER PRIMARY KEY,
                custumer_name TEXT,
                order_date TEXT,
                totalPrice REAL,
                customer_id INTEGER,
                cart_id INTEGER
            );
        ");

        // Seed data
        $this->pdo->exec("
            INSERT INTO customers (customer_id, firstName) VALUES (1, 'John Doe');
            INSERT INTO carts (cart_id, customer_id, status) VALUES (1, 1, 'active');
            INSERT INTO products (product_id, pName, price, Quantity) VALUES (1, 'Product A', 100.0, 10);
            INSERT INTO cart_items (cart_id, product_id, quantity) VALUES (1, 1, 2);
        ");
    }

    public function testPlaceOrder(): void {
        // Fetch cart total
        $stmt = $this->pdo->prepare("SELECT SUM(products.price * cart_items.quantity) AS total_price
            FROM cart_items
            JOIN products ON cart_items.product_id = products.product_id
            WHERE cart_items.cart_id = ?");
        $stmt->execute([1]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->assertEquals(200.0, $result['total_price'], "Cart total should match");

        // Place order
        $order_date = date('Y-m-d');
        $stmt = $this->pdo->prepare("INSERT INTO orders (custumer_name, order_date, totalPrice, customer_id, cart_id) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute(['John Doe', $order_date, $result['total_price'], 1, 1]);

        // Verify order creation
        $stmt = $this->pdo->prepare("SELECT * FROM orders WHERE customer_id = ?");
        $stmt->execute([1]);
        $order = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->assertNotEmpty($order, "Order should be created");
    }
}
