<?php
namespace Packaged\Validate\Tests;

use Packaged\Validate\Validators\FileSizeValidator;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileSizeValidatorTest extends TestCase
{
  public function testNumberValidation()
  {
    $upload = ['size' => 1159144];
    $validator = new FileSizeValidator(3);

    $largeUpload = ['size' => 5123224];

    $this->assertTrue($validator->isValid($upload));
    $this->assertFalse($validator->isValid($largeUpload));

  }
}
