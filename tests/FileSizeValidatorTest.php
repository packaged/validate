<?php
namespace Packaged\Validate\Tests;

use Packaged\Validate\Validators\FileSizeValidator;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileSizeValidatorTest extends TestCase
{
  public function testNumberValidation()
  {
    $file = __DIR__ . DIRECTORY_SEPARATOR . '_resources' . DIRECTORY_SEPARATOR . '78kb.png';
    $upload = new UploadedFile($file, '78kb.png');
    $validator = new FileSizeValidator(3);

    $file = __DIR__ . DIRECTORY_SEPARATOR . '_resources' . DIRECTORY_SEPARATOR . '4mb.jpeg';
    $largeUpload = new UploadedFile($file, '78kb.png');

    $this->assertTrue($validator->isValid($upload));
    $this->assertFalse($validator->isValid($largeUpload));

  }
}
