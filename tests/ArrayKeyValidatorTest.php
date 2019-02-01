<?php
namespace Packaged\Validate\Validators\Tests;

use Packaged\Validate\Validators\ArrayKeyValidator;
use PHPUnit\Framework\TestCase;

class ArrayKeyValidatorTest extends TestCase
{
  public function intArrayProvider()
  {
    return [
      ['invalid', false, false],
      [['test1' => 'test', 'test2' => 'test', 'test3' => 'test'], false, true],
      [['test1' => 'test', 'test2' => 'test'], false, false],
      [['test2' => 'test', 'test3' => 'test'], false, false],
      [['test1' => 'test', 'test2' => 'test', 'test3' => 'test', 'test4' => 'test'], false, false],
      [['test1' => 'test', 'test2' => 'test', 'test3' => 'test', 'test4' => 'test'], true, true],
    ];
  }

  /**
   * @dataProvider intArrayProvider
   *
   * @param $test
   * @param $allowUnknown
   * @param $expectedValid
   */
  public function testIntArray($test, $allowUnknown, $expectedValid)
  {
    $validator = new ArrayKeyValidator(['test1', 'test2', 'test3'], $allowUnknown);
    $this->assertEquals($expectedValid, $validator->isValid($test));
  }
}
