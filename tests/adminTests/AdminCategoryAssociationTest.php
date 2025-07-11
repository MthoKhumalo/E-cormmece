<?php
use PHPUnit\Framework\TestCase;

class AdminCategoryAssociationTest extends TestCase
{
    public function testAdminProductBelongsToCorrectCategory()
    {
        $product = ['id' => 1, 'name' => 'Laptop', 'category_id' => 2];
        $categories = [
            ['id' => 1, 'name' => 'Accessories'],
            ['id' => 2, 'name' => 'Electronics'],
        ];

        $categoryName = array_filter($categories, function ($category) use ($product) {
            return $category['id'] === $product['category_id'];
        });

        $this->assertNotEmpty($categoryName, "The product should belong to an existing category.");
        $this->assertEquals('Electronics', reset($categoryName)['name'], "The product should belong to the Electronics category.");
    }

    public function testAdminProductCategoryMismatch()
    {
        $product = ['id' => 1, 'name' => 'Laptop', 'category_id' => 99];
        $categories = [
            ['id' => 1, 'name' => 'Accessories'],
            ['id' => 2, 'name' => 'Electronics'],
        ];

        $categoryName = array_filter($categories, function ($category) use ($product) {
            return $category['id'] === $product['category_id'];
        });

        $this->assertEmpty($categoryName, "The product's category should not exist.");
    }
}