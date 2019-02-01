<?php
namespace Packaged\Validate\Validators\Tests;

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

  /**
   * @expectedException \Packaged\Validate\ValidationException
   * @expectedExceptionMessage must be an array
   */
  public function testNotArray()
  {
    $validator = new ArrayValidator(new StringValidator());
    $validator->assert('string');
  }

  /**
   * @expectedException \Packaged\Validate\ValidationException
   * @expectedExceptionMessage must contain at least 2 items
   */
  public function testSubValidator()
  {
    $validator = new ArrayValidator(new IntegerValidator(3, 5), 2, 2);
    $this->assertEquals(true, $validator->isValid([3, 5]));

    $validator = new ArrayValidator(new IntegerValidator(3, 5), 2, 2);
    $this->assertEquals(false, $validator->isValid([2, 6]));

    $validator->assert([7]);
  }
}
