<?php
use PHPUnit\Framework\TestCase;

class AdminGroupProductsTest extends TestCase
{
    public function testAdminGroupProductsByCategory()
    {
        $products = [
            ['id' => 1, 'category' => 'Electronics', 'name' => 'Laptop'],
            ['id' => 2, 'category' => 'Electronics', 'name' => 'Camera'],
            ['id' => 3, 'category' => 'Furniture', 'name' => 'Table'],
        ];
        
        $groupedProducts = [];
        foreach ($products as $product) {
            $category = $product['category'];
            $groupedProducts[$category][] = $product;
        }
        
        $this->assertArrayHasKey('Electronics', $groupedProducts);
        $this->assertArrayHasKey('Furniture', $groupedProducts);
        $this->assertCount(2, $groupedProducts['Electronics']);
    }
}