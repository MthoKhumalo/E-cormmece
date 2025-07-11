<?php
use PHPUnit\Framework\TestCase;

class AdminFetchProductsTest extends TestCase
{
    public function testAdminFetchAllProducts()
    {
        $db = DatabaseConnection::getInstance()->getConnection();
        $query = "SELECT * FROM products";
        $stmt = $db->prepare($query);
        $stmt->execute();
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $this->assertIsArray($products, "Products should be fetched as an array.");
        $this->assertNotEmpty($products, "Products array should not be empty.");
    }
}