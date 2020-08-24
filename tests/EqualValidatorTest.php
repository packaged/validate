<?php
namespace Packaged\Validate\Tests;

use Packaged\Validate\Validators\EqualValidator;
use Packaged\Validate\Validators\NotEqualValidator;
use PHPUnit\Framework\TestCase;

class EqualValidatorTest extends TestCase
{
  public function testEqual()
  {
    $validator = new EqualValidator('abc');
    $this->assertTrue($validator->isValid('abc'));
    $this->assertFalse($validator->isValid('xyz'));
  }

  public function testNotEqual()
  {
    $validator = new NotEqualValidator('abc');
    $this->assertFalse($validator->isValid('abc'));
    $this->assertTrue($validator->isValid('xyz'));
  }
}
