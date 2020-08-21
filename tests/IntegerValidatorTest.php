<?php
namespace Packaged\Validate\Tests;

use Packaged\Validate\Validators\IntegerValidator;
use PHPUnit\Framework\TestCase;

class IntegerValidatorTest extends TestCase
{
  public function intArrayProvider()
  {
    return [
      [0, false],
      [1, false],
      [2, true],
      [3, true],
      [4, true],
      [5, false],
      [3.2, false],
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
    $validator = new IntegerValidator(2, 4);
    $this->assertEquals($expectedValid, $validator->isValid($testArray));
  }
}
