<?php
namespace Packaged\Validate\Validators\Tests;

use Packaged\Validate\Validators\DecimalValidator;
use Packaged\Validate\Validators\NumberValidator;
use PHPUnit\Framework\TestCase;

class NumericValidatorTest extends TestCase
{
  public function testNumberException()
  {
    $this->expectException(\InvalidArgumentException::class);
    $this->expectExceptionMessage('maxValue must be greater than or equal to minValue');
    new NumberValidator(3, 1);
  }

  public function testNumberValidation()
  {
    $validator = new NumberValidator(3, 5);
    $this->assertFalse($validator->isValid('test'));
    $this->assertFalse($validator->isValid(2));
    $this->assertFalse($validator->isValid(6));
    $this->assertFalse($validator->isValid(5.5));
    $this->assertTrue($validator->isValid(4.5));
    $this->assertTrue($validator->isValid(4));
  }

  public function testDecimalValidation()
  {
    $validator = new DecimalValidator(3);
    $this->assertFalse($validator->isValid('test'));
    $this->assertTrue($validator->isValid(0.5));
    $this->assertTrue($validator->isValid(0.533));
    $this->assertFalse($validator->isValid(0.5333));

    $validator = new DecimalValidator(3, 0.5, 5.5);
    $this->assertFalse($validator->isValid('test'));
    $this->assertTrue($validator->isValid(0.5));
    $this->assertFalse($validator->isValid(2.9999));
    $this->assertTrue($validator->isValid(3.439));
    $this->asserttrue($validator->isValid(5.499));
    $this->assertFalse($validator->isValid(5.4999));
    $this->assertFalse($validator->isValid(5.533));
    $this->assertFalse($validator->isValid(5.5333));
    $this->assertTrue($validator->isValid(5.5));

    $this->assertNotEmpty($validator->validate('nan'));
  }
}
