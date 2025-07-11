<?php

use PHPUnit\Framework\TestCase;

class RepairsTest extends TestCase
{
    private $pdo;

    protected function setUp(): void
    {
        // Mock database connection
        $this->pdo = $this->createMock(PDO::class);
    }

    public function testRepairFormSubmission()
    {
        // Mock user session
        $_SESSION['user_id'] = 1;

        $_SERVER["REQUEST_METHOD"] = "POST";

        // Prepare test data
        $_POST = [
            'description' => 'Sample repair request',
            'monday' => '08:00:00-10:00:00'
        ];

        $_FILES = [
            'image' => ['tmp_name' => '/path/to/image.jpg']
        ];

        // Mock database statement
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->expects($this->once())->method('execute')->willReturn(true);

        // Mock PDO prepare method
        $this->pdo->expects($this->once())
            ->method('prepare')
            ->willReturn($stmt);

        // Execute repairs.php logic
        ob_start();
        include 'repairs.php';
        $output = ob_get_clean();

        $this->assertStringContainsString('Repair request submitted successfully', $output);
    }
}
