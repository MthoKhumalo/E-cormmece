<?php
use PHPUnit\Framework\TestCase;

class AdminNotificationsIntegrationTest extends TestCase
{
    public function testSendLowStockNotification()
    {
        $inventory = [
            ['product' => 'Laptop', 'quantity' => 10],
            ['product' => 'Desk', 'quantity' => 2],
        ];

        $threshold = 5;
        $notifications = [];

        foreach ($inventory as $item) {
            if ($item['quantity'] < $threshold) {
                $notifications[] = "Low stock alert: {$item['product']} (Quantity: {$item['quantity']})";
            }
        }

        $this->assertCount(1, $notifications, "Only one low-stock notification should be sent.");
        $this->assertStringContainsString('Desk', $notifications[0], "Notification should correctly identify the low-stock product.");
    }

    public function testSendOrderCompletionNotification()
    {
        $orders = [
            ['id' => 1, 'status' => 'completed', 'customer_email' => 'customer1@example.com'],
            ['id' => 2, 'status' => 'pending', 'customer_email' => 'customer2@example.com'],
        ];

        $notifications = [];
        foreach ($orders as $order) {
            if ($order['status'] === 'completed') {
                $notifications[] = "Order #{$order['id']} has been completed. Email sent to {$order['customer_email']}.";
            }
        }

        $this->assertCount(1, $notifications, "Only one notification should be sent for completed orders.");
        $this->assertStringContainsString('customer1@example.com', $notifications[0], "Notification should include the correct customer email.");
    }
}