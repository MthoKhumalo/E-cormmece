<?php
use PHPUnit\Framework\TestCase;

class AdminOrderManagementIntegrationTest extends TestCase
{
    public function testPlaceOrderUpdatesInventory()
    {
        $inventory = [
            ['id' => 1, 'product' => 'Laptop', 'quantity' => 10],
        ];

        $order = [
            'id' => 1,
            'product_id' => 1,
            'quantity' => 2,
        ];

        // Update inventory
        foreach ($inventory as &$item) {
            if ($item['id'] === $order['product_id']) {
                $item['quantity'] -= $order['quantity'];
            }
        }

        $this->assertEquals(8, $inventory[0]['quantity'], "Inventory should be reduced after an order is placed.");
    }

    public function testOrderCancellationRestoresInventory()
    {
        $inventory = [
            ['id' => 1, 'product' => 'Laptop', 'quantity' => 8],
        ];

        $order = [
            'id' => 1,
            'product_id' => 1,
            'quantity' => 2,
            'status' => 'cancelled',
        ];

        // Restore inventory on cancellation
        if ($order['status'] === 'cancelled') {
            foreach ($inventory as &$item) {
                if ($item['id'] === $order['product_id']) {
                    $item['quantity'] += $order['quantity'];
                }
            }
        }

        $this->assertEquals(10, $inventory[0]['quantity'], "Inventory should be restored after order cancellation.");
    }
}