<?php
use PHPUnit\Framework\TestCase;

class ProductGroupingTest extends TestCase
{
    public function testProductGrouping()
    {
        $products = [
            ["category" => "Electronics", "pName" => "Laptop"],
            ["category" => "Electronics", "pName" => "Smartphone"],
            ["category" => "Home", "pName" => "Vacuum Cleaner"],
        ];

        $groupedProducts = [];
        foreach ($products as $product) {
            $groupedProducts[$product["category"]][] = $product;
        }

        $this->assertArrayHasKey("Electronics", $groupedProducts);
        $this->assertCount(2, $groupedProducts["Electronics"]);
        $this->assertCount(1, $groupedProducts["Home"]);
    }
}

