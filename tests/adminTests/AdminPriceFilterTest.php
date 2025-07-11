<?php
use PHPUnit\Framework\TestCase;

class AdminPriceFilterTest extends TestCase
{
    public function testAdminProductsWithinPriceRange()
    {
        $products = [
            ['id' => 1, 'name' => 'Laptop', 'price' => 1200],
            ['id' => 2, 'name' => 'Camera', 'price' => 700],
            ['id' => 3, 'name' => 'Phone', 'price' => 300],
        ];

        $minPrice = 500;
        $maxPrice = 1300;

        $filteredProducts = array_filter($products, function ($product) use ($minPrice, $maxPrice) {
            return $product['price'] >= $minPrice && $product['price'] <= $maxPrice;
        });

        $this->assertCount(2, $filteredProducts, "Two products should fall within the price range.");
        $this->assertEquals(['Laptop', 'Camera'], array_column($filteredProducts, 'name'), "Laptop and Camera should match the range.");
    }

    public function testAdminNoProductsWithinPriceRange()
    {
        $products = [
            ['id' => 1, 'name' => 'Laptop', 'price' => 1200],
            ['id' => 2, 'name' => 'Camera', 'price' => 700],
        ];

        $minPrice = 1300;
        $maxPrice = 1500;

        $filteredProducts = array_filter($products, function ($product) use ($minPrice, $maxPrice) {
            return $product['price'] >= $minPrice && $product['price'] <= $maxPrice;
        });

        $this->assertEmpty($filteredProducts, "No products should match the given price range.");
    }
}