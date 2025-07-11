<?php
use PHPUnit\Framework\TestCase;

class AdminNotificationsAdvancedIntegrationTest extends TestCase
{
    public function testBulkSendOrderUpdates()
    {
        $orders = [
            ['id' => 1, 'status' => 'shipped', 'customer_email' => 'customer1@example.com'],
            ['id' => 2, 'status' => 'delivered', 'customer_email' => 'customer2@example.com'],
            ['id' => 3, 'status' => 'cancelled', 'customer_email' => 'customer3@example.com'],
        ];

        $notifications = [];
        foreach ($orders as $order) {
            if ($order['status'] === 'shipped' || $order['status'] === 'delivered') {
                $notifications[] = "Order #{$order['id']} status: {$order['status']}. Email sent to {$order['customer_email']}.";
            }
        }

        // Assertions
        $this->assertCount(2, $notifications, "Notifications should only be sent for shipped or delivered orders.");
        $this->assertStringContainsString('shipped', $notifications[0], "Notification should correctly include the status.");
        $this->assertStringContainsString('customer2@example.com', $notifications[1], "Notification should include the correct email.");
    }

    public function testSendHighPriorityNotifications()
    {
        $notificationsQueue = [
            ['type' => 'low_stock', 'priority' => 'low'],
            ['type' => 'payment_failed', 'priority' => 'high'],
        ];

        $highPriorityNotifications = array_filter($notificationsQueue, function ($notification) {
            return $notification['priority'] === 'high';
        });

        // Assertions
        $this->assertCount(1, $highPriorityNotifications, "Only high-priority notifications should be processed.");
        $this->assertEquals('payment_failed', $highPriorityNotifications[array_key_first($highPriorityNotifications)]['type'], "Notification type should be 'payment_failed'.");
    }
}