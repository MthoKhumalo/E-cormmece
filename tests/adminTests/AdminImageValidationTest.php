<?php
use PHPUnit\Framework\TestCase;

class AdminImageValidationTest extends TestCase
{
    public function testAdminValidBase64Image()
    {
        $validImage = 'data:image/png;base64,' . base64_encode('test_image_data');
        $pattern = '/^data:image\/[a-z]+;base64,[a-zA-Z0-9\/+=]+$/';

        $this->assertMatchesRegularExpression($pattern, $validImage, "Image should match Base64 format.");
    }

    public function testAdminInvalidBase64Image()
    {
        $invalidImage = 'invalid_base64_string';
        $pattern = '/^data:image\/[a-z]+;base64,[a-zA-Z0-9\/+=]+$/';

        $this->assertDoesNotMatchRegularExpression($pattern, $invalidImage, "Invalid image should not match Base64 format.");
    }
}
