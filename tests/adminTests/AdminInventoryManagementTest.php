<?php
use PHPUnit\Framework\TestCase;

class AdminInventoryManagementTest extends TestCase
{
    public function testAddNewInventoryItem()
    {
        $inventory = [
            ['product_id' => 1, 'name' => 'Laptop', 'quantity' => 10],
        ];

        // Admin adds a new product
        $newProduct = ['product_id' => 2, 'name' => 'Phone', 'quantity' => 50];
        $inventory[] = $newProduct;

        // Assertions
        $this->assertCount(2, $inventory, "Inventory count should increase after adding a new product.");
        $this->assertEquals('Phone', $inventory[1]['name'], "New product should be correctly added.");
        $this->assertEquals(50, $inventory[1]['quantity'], "New product quantity should be correct.");
    }

    public function testUpdateInventoryStock()
    {
        $inventory = [
            ['product_id' => 1, 'name' => 'Laptop', 'quantity' => 10],
        ];

        // Admin updates stock
        foreach ($inventory as &$item) {
            if ($item['product_id'] === 1) {
                $item['quantity'] += 5;
            }
        }

        // Assertions
        $this->assertEquals(15, $inventory[0]['quantity'], "Product stock should increase correctly.");
    }

    public function testHandleInsufficientStockError()
    {
        $inventory = [
            ['product_id' => 1, 'name' => 'Laptop', 'quantity' => 2],
        ];

        $requestedQuantity = 5;
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Insufficient stock for product Laptop");

        foreach ($inventory as $item) {
            if ($item['product_id'] === 1 && $requestedQuantity > $item['quantity']) {
                throw new Exception("Insufficient stock for product " . $item['name']);
            }
        }
    }
}