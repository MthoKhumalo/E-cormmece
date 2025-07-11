<?php

use PHPUnit\Framework\TestCase;

class adminProductWorkflowTest extends TestCase
{
    use ApiRequestHelper;

    public function testFetchAndFilterProducts()
    {
        // Fetch all products
        $productResponse = $this->get('/products');
        $this->assertEquals(200, $productResponse['status']);
        $this->assertNotEmpty($productResponse['data']);

        // Filter products by category
        $filterResponse = $this->get('/products?category=electronics');
        $this->assertEquals(200, $filterResponse['status']);
        foreach ($filterResponse['data'] as $product) {
            $this->assertEquals('electronics', $product['category']);
        }
    }

    public function testAddProductToCart()
    {
        $token = $this->authenticateUser();

        // Add product to cart
        $addResponse = $this->post('/cart/add', [
            'product_id' => 1,
            'quantity' => 2,
        ], $token);
        $this->assertEquals(200, $addResponse['status']);

        // Verify cart contents
        $cartResponse = $this->get('/cart/details', $token);
        $this->assertEquals(200, $cartResponse['status']);
        $this->assertEquals(1, count($cartResponse['data']));
        $this->assertEquals(2, $cartResponse['data'][0]['quantity']);
    }

    private function authenticateUser()
    {
        $loginResponse = $this->post('/auth/login', [
            'email' => 'user@example.com',
            'password' => 'securePassword',
        ]);
        $this->assertEquals(200, $loginResponse['status']);
        return $loginResponse['data']['token'];
    }
}
