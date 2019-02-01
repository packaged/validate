<?php
namespace Packaged\Validate\Validators\Tests;

use Packaged\Validate\Tests\ObjectTest;
use Packaged\Validate\Validators\PropertyValidator;
use PHPUnit\Framework\TestCase;

class PropertyValidatorTest extends TestCase
{
  public function objectProvider()
  {
    return [
      [['test1', 'test2', 'test3'], false, true],
      [['test1', 'test2'], false, false],
      [['test2', 'test3'], false, false],
      [['test1', 'test2'], true, true],
    ];
  }

  /**
   * @dataProvider objectProvider
   *
   * @param $requiredFields
   * @param $allowUnknown
   * @param $expectedValid
   */
  public function testObjectProperties($requiredFields, $allowUnknown, $expectedValid)
  {
    $validator = new PropertyValidator($requiredFields, $allowUnknown);
    $this->assertEquals($expectedValid, $validator->isValid(new ObjectTest()));
  }

  public function testNotAnObject()
  {
    $validator = new PropertyValidator();
    $this->assertEquals(false, $validator->isValid('string'));
  }
}
