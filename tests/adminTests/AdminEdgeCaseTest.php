<?php
use PHPUnit\Framework\TestCase;

class AdminEdgeCaseTest extends TestCase
{
    public function testAdminAddDuplicateProduct()
    {
        $existingProducts = [
            ['id' => 1, 'name' => 'Laptop', 'price' => 1000],
        ];

        $newProduct = ['id' => 1, 'name' => 'Laptop', 'price' => 1000]; // Duplicate ID

        $isDuplicate = in_array($newProduct['id'], array_column($existingProducts, 'id'));

        $this->assertTrue($isDuplicate, "Product ID {$newProduct['id']} should be flagged as duplicate.");
    }

    public function testAdminHandleLargeDataSet()
    {
        $products = array_map(function ($i) {
            return ['id' => $i, 'name' => "Product {$i}", 'price' => rand(10, 1000)];
        }, range(1, 10000)); // Large data set

        $filteredProducts = array_filter($products, function ($product) {
            return $product['price'] > 500;
        });

        $this->assertGreaterThan(0, count($filteredProducts), "Filtered products should not be empty for a large data set.");
    }

    public function testAdminProcessInvalidJSON()
    {
        $invalidJSON = '{"name": "Laptop", "price": 100'; // Missing closing brace

        $decoded = json_decode($invalidJSON, true);

        $this->assertNull($decoded, "Invalid JSON should result in null when decoded.");
        $this->assertEquals(JSON_ERROR_SYNTAX, json_last_error(), "JSON error should indicate syntax error.");
    }
}