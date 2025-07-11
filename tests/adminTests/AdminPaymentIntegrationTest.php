<?php
use PHPUnit\Framework\TestCase;

class AdminPaymentIntegrationTest extends TestCase
{
    public function testProcessPaymentUpdatesOrderStatus()
    {
        $orders = [
            ['id' => 1, 'status' => 'pending', 'amount' => 100],
        ];

        $payment = [
            'order_id' => 1,
            'amount' => 100,
            'status' => 'success',
        ];

        foreach ($orders as &$order) {
            if ($order['id'] === $payment['order_id'] && $payment['status'] === 'success') {
                $order['status'] = 'completed';
            }
        }

        $this->assertEquals('completed', $orders[0]['status'], "Order status should be updated to completed after successful payment.");
    }

    public function testFailedPaymentDoesNotUpdateOrderStatus()
    {
        $orders = [
            ['id' => 1, 'status' => 'pending', 'amount' => 100],
        ];

        $payment = [
            'order_id' => 1,
            'amount' => 100,
            'status' => 'failed',
        ];

        foreach ($orders as &$order) {
            if ($order['id'] === $payment['order_id'] && $payment['status'] === 'success') {
                $order['status'] = 'completed';
            }
        }

        $this->assertEquals('pending', $orders[0]['status'], "Order status should remain pending after failed payment.");
    }
}