<?php

use PHPUnit\Framework\TestCase;

class SellTest extends TestCase
{
    private $pdo;

    protected function setUp(): void
    {
        // Mock database connection
        $this->pdo = $this->createMock(PDO::class);
    }

    public function testSellFormSubmission()
    {
        // Mock user session
        $_SESSION['user_id'] = 1;

        $_SERVER["REQUEST_METHOD"] = "POST";

        // Prepare test data
        $_POST = [
            'description' => 'Sample product',
            'price' => '100.00',
        ];

        $_FILES = [
            'front_image' => ['tmp_name' => '/path/to/front/image.jpg'],
            'back_image' => ['tmp_name' => '/path/to/back/image.jpg'],
            'aerial_image' => ['tmp_name' => '/path/to/aerial/image.jpg']
        ];

        // Mock database statement
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->expects($this->once())->method('execute')->willReturn(true);

        // Mock PDO prepare method
        $this->pdo->expects($this->once())
            ->method('prepare')
            ->willReturn($stmt);

        // Execute sell.php logic
        ob_start();
        include 'sell.php';
        $output = ob_get_clean();

        $this->assertStringContainsString('Review request in progress', $output);
    }
}
