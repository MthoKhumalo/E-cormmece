<?php

use PHPUnit\Framework\TestCase;

class adminOrderWorkflowTest extends TestCase
{
    use ApiRequestHelper;

    public function testPlaceOrderWorkflow()
    {
        $token = $this->authenticateUser();

        // Step 1: Add items to cart
        $cartResponse = $this->post('/cart/add', [
            'product_id' => 1,
            'quantity' => 2,
        ], $token);
        $this->assertEquals(200, $cartResponse['status']);

        // Step 2: Place order
        $orderResponse = $this->post('/orders/place', [], $token);
        $this->assertEquals(200, $orderResponse['status']);
        $this->assertArrayHasKey('order_id', $orderResponse['data']);

        // Step 3: Check order status
        $orderStatusResponse = $this->get("/orders/status/{$orderResponse['data']['order_id']}", $token);
        $this->assertEquals(200, $orderStatusResponse['status']);
        $this->assertEquals('Processing', $orderStatusResponse['data']['status']);
    }

    private function authenticateUser()
    {
        $authResponse = $this->post('/auth/login', [
            'email' => 'user@example.com',
            'password' => 'securePassword',
        ]);
        $this->assertEquals(200, $authResponse['status']);
        return $authResponse['data']['token'];
    }
}
