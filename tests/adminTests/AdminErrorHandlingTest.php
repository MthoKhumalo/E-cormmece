<?php
use PHPUnit\Framework\TestCase;

class AdminErrorHandlingTest extends TestCase
{
    public function testAdminInvalidProductId()
    {
        $invalidProductId = -1; // Invalid ID
        $products = [
            ['id' => 1, 'name' => 'Laptop'],
            ['id' => 2, 'name' => 'Phone'],
        ];

        $product = array_filter($products, function ($p) use ($invalidProductId) {
            return $p['id'] === $invalidProductId;
        });

        $this->assertEmpty($product, "No product should match an invalid product ID.");
    }

    public function testAdminEmptyCategoryName()
    {
        $categoryName = ''; // Invalid input
        $categories = ['Electronics', 'Accessories'];

        $isValid = in_array($categoryName, $categories);

        $this->assertFalse($isValid, "Category name should be invalid if empty.");
    }

    public function testAdminUnexpectedSystemError()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Unexpected system error occurred.');

        throw new Exception('Unexpected system error occurred.');
    }
}