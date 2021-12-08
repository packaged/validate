<?php
namespace Packaged\Validate\Tests;

use Packaged\Validate\Validation;
use Packaged\Validate\ValidationException;
use Packaged\Validate\Validators\ArrayValidator;
use Packaged\Validate\Validators\IntegerValidator;
use Packaged\Validate\Validators\StringValidator;
use PHPUnit\Framework\TestCase;

class ArrayValidatorTest extends TestCase
{
  public function intArrayProvider()
  {
    return [
      [[], false],
      [[1], false],
      [[1, 2, 3, 4, 5], true],
      [[1, 2, 3, 4, 5, 6], false],
      [[1, 2, 3], true],
      [[1, 2, 3], true],
    ];
  }

  /**
   * @dataProvider intArrayProvider
   *
   * @param $testArray
   * @param $expectedValid
   */
  public function testIntArray($testArray, $expectedValid)
  {
    $validator = new ArrayValidator(new IntegerValidator(), 3, 5);
    $this->assertEquals($expectedValid, $validator->isValid($testArray));
  }

  public function testNotArray()
  {
    $this->expectException(ValidationException::class);
    $this->expectExceptionMessage('must be an array');
    $validator = new ArrayValidator(new StringValidator());
    $validator->assert('string');
  }

  public function testSubValidator()
  {
    $this->expectException(ValidationException::class);
    $this->expectExceptionMessage('must contain at least 2 items');
    $validator = new ArrayValidator(new IntegerValidator(3, 5), 2, 2);
    $this->assertEquals(true, $validator->isValid([3, 5]));

    $validator = new ArrayValidator(new IntegerValidator(3, 5), 2, 2);
    $this->assertEquals(false, $validator->isValid([2, 6]));

    $validator->assert([7]);
  }

  public function testSerialize()
  {
    $validator = new ArrayValidator(new IntegerValidator(3, 5), 2, 2);
    $this->assertEquals(true, $validator->isValid([3, 5]));

    $jsn = json_encode($validator);
    $unsValidator = Validation::fromJsonObject(json_decode($jsn));
    $this->assertInstanceOf(get_class($validator), $unsValidator);
    $this->assertEquals(true, $validator->isValid([3, 5]));
    $this->assertEquals(json_encode($validator), json_encode($unsValidator));
  }
}
