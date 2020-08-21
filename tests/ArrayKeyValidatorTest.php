<?php
namespace Packaged\Validate\Tests;

use Packaged\Validate\Validation;
use Packaged\Validate\Validators\ArrayKeysValidator;
use PHPUnit\Framework\TestCase;

class ArrayKeyValidatorTest extends TestCase
{
  public function arrayProvider()
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
   * @dataProvider arrayProvider
   *
   * @param $test
   * @param $allowUnknown
   * @param $expectedValid
   */
  public function testArray($test, $allowUnknown, $expectedValid)
  {
    $validator = new ArrayKeysValidator(['test1', 'test2', 'test3'], $allowUnknown);
    $this->assertEquals($expectedValid, $validator->isValid($test));
  }

  /**
   * @dataProvider arrayProvider
   *
   * @param $test
   * @param $allowUnknown
   * @param $expectedValid
   */
  public function testSerialize($test, $allowUnknown, $expectedValid)
  {
    $validator = new ArrayKeysValidator(['test1', 'test2', 'test3'], $allowUnknown);
    $this->assertEquals($expectedValid, $validator->isValid($test));

    $jsn = json_encode($validator);
    $unsValidator = Validation::fromJsonObject(json_decode($jsn));
    $this->assertInstanceOf(get_class($validator), $unsValidator);
    $this->assertEquals($expectedValid, $unsValidator->isValid($test));
    $this->assertEquals(json_encode($validator), json_encode($unsValidator));
  }
}
