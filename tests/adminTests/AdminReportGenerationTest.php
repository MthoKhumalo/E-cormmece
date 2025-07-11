<?php
use PHPUnit\Framework\TestCase;

class AdminReportGenerationTest extends TestCase
{
    public function testGenerateOrderReport()
    {
        $orders = [
            ['id' => 1, 'status' => 'completed', 'amount' => 500],
            ['id' => 2, 'status' => 'pending', 'amount' => 300],
            ['id' => 3, 'status' => 'cancelled', 'amount' => 0],
        ];

        $completedOrders = array_filter($orders, function ($order) {
            return $order['status'] === 'completed';
        });

        $totalAmount = array_sum(array_column($completedOrders, 'amount'));

        // Assertions
        $this->assertCount(1, $completedOrders, "Only completed orders should be included in the report.");
        $this->assertEquals(500, $totalAmount, "Total amount should match the sum of completed orders.");
    }

    public function testGenerateInventoryReport()
    {
        $inventory = [
            ['product' => 'Laptop', 'quantity' => 10],
            ['product' => 'Phone', 'quantity' => 0],
        ];

        $lowStockItems = array_filter($inventory, function ($item) {
            return $item['quantity'] < 5;
        });

        // Assertions
        $this->assertCount(1, $lowStockItems, "Low stock items should be flagged in the report.");
        $this->assertEquals('Phone', $lowStockItems[array_key_first($lowStockItems)]['product'], "Phone should be flagged as low stock.");
    }
}