<?php
use PHPUnit\Framework\TestCase;

class ProductTest extends TestCase
{
    public function testProductName()
    {
        $productName = "Laptop";
        $this->assertEquals("Laptop", $productName);
    }
}
