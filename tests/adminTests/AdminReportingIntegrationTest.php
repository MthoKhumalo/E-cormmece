<?php
use PHPUnit\Framework\TestCase;

class AdminReportingIntegrationTest extends TestCase
{
    public function testGenerateSalesReportForDateRange()
    {
        $orders = [
            ['id' => 1, 'date' => '2024-11-01', 'amount' => 150.00],
            ['id' => 2, 'date' => '2024-11-05', 'amount' => 200.00],
            ['id' => 3, 'date' => '2024-11-10', 'amount' => 100.00],
        ];

        $startDate = '2024-11-01';
        $endDate = '2024-11-07';

        $filteredOrders = array_filter($orders, function ($order) use ($startDate, $endDate) {
            return $order['date'] >= $startDate && $order['date'] <= $endDate;
        });

        $totalSales = array_sum(array_column($filteredOrders, 'amount'));

        $this->assertCount(2, $filteredOrders, "Report should include only orders within the date range.");
        $this->assertEquals(350.00, $totalSales, "Total sales for the date range should be accurate.");
    }

    public function testGenerateInventoryReport()
    {
        $inventory = [
            ['product' => 'Laptop', 'quantity' => 10],
            ['product' => 'Desk', 'quantity' => 5],
        ];

        $threshold = 6;

        $lowStockItems = array_filter($inventory, function ($item) use ($threshold) {
            return $item['quantity'] < $threshold;
        });

        $this->assertCount(1, $lowStockItems, "Report should include only low-stock items.");
        $this->assertEquals('Desk', $lowStockItems[array_key_first($lowStockItems)]['product'], "Low-stock item should be identified correctly.");
    }
}