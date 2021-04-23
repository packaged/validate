<?php
namespace Packaged\Validate\Tests;

use Packaged\Validate\Validators\RequiredValidator;
use PHPUnit\Framework\TestCase;

class RequiredValidatorTest extends TestCase
{
  public function testRequired()
  {
    $validator = new RequiredValidator();
    $this->assertFalse($validator->isValid(null));
    $this->assertFalse($validator->isValid(''));
    $this->assertTrue($validator->isValid('test'));
    $this->assertTrue($validator->isValid(0));
    $this->assertFalse($validator->isValid(false));
    $this->assertTrue($validator->isValid('false'));
    $this->assertTrue($validator->isValid(true));
  }
}
