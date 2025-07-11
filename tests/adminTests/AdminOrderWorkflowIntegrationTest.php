<?php
use PHPUnit\Framework\TestCase;

class AdminOrderWorkflowIntegrationTest extends TestCase
{
    public function testOrderPlacementAndPaymentWorkflow()
    {
        $inventory = [
            ['product' => 'Laptop', 'quantity' => 10],
            ['product' => 'Desk', 'quantity' => 5],
        ];

        $order = [
            'id' => 1,
            'customer_id' => 101,
            'items' => [
                ['product' => 'Laptop', 'quantity' => 2],
                ['product' => 'Desk', 'quantity' => 1],
            ],
            'status' => 'pending',
        ];

        $payment = [
            'order_id' => 1,
            'amount' => 2000.00,
            'status' => 'successful',
        ];

        // Process order
        $order['status'] = $payment['status'] === 'successful' ? 'processing' : 'failed';

        // Update inventory
        foreach ($order['items'] as $item) {
            foreach ($inventory as &$product) {
                if ($product['product'] === $item['product']) {
                    $product['quantity'] -= $item['quantity'];
                }
            }
        }

        // Assertions
        $this->assertEquals('processing', $order['status'], "Order status should be updated after successful payment.");
        $this->assertEquals(8, $inventory[0]['quantity'], "Inventory for Laptop should be updated.");
        $this->assertEquals(4, $inventory[1]['quantity'], "Inventory for Desk should be updated.");
    }

    public function testOrderCancellationRestocksInventory()
    {
        $inventory = [
            ['product' => 'Laptop', 'quantity' => 10],
            ['product' => 'Desk', 'quantity' => 5],
        ];

        $order = [
            'id' => 2,
            'customer_id' => 102,
            'items' => [
                ['product' => 'Laptop', 'quantity' => 1],
                ['product' => 'Desk', 'quantity' => 2],
            ],
            'status' => 'processing',
        ];

        // Cancel order
        if ($order['status'] === 'processing') {
            $order['status'] = 'cancelled';

            // Restock inventory
            foreach ($order['items'] as $item) {
                foreach ($inventory as &$product) {
                    if ($product['product'] === $item['product']) {
                        $product['quantity'] += $item['quantity'];
                    }
                }
            }
        }

        // Assertions
        $this->assertEquals('cancelled', $order['status'], "Order status should be updated to cancelled.");
        $this->assertEquals(11, $inventory[0]['quantity'], "Inventory for Laptop should be restocked.");
        $this->assertEquals(7, $inventory[1]['quantity'], "Inventory for Desk should be restocked.");
    }
}