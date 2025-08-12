<?php
namespace Packaged\Validate\Tests;

use Packaged\Validate\Validation;
use Packaged\Validate\Validators\RegexValidator;
use PHPUnit\Framework\TestCase;

class RegexValidatorTest extends TestCase
{
  public function testRegexValidatorMessage()
  {
    $v1 = new RegexValidator('/^[0-9]{6}$/');
    $v2 = RegexValidator::withDictionary([RegexValidator::DICT_INVALID => 'test failure message'], '/^[0-9]{6}$/');
    $v1err = $v1->validate('123');
    $this->assertNotEmpty($v1err);
    $this->assertEquals('Does not match regular expression', $v1err[0]->getMessage());
    $v2err = $v2->validate('123');
    $this->assertNotEmpty($v2err);
    $this->assertEquals('Test failure message', $v2err[0]->getMessage());
  }

  public function testSerialize()
  {
    $validator = new RegexValidator('/^[0-9]{6}$/');
    $this->assertTrue($validator->isValid('123456'));

    $jsn = json_encode($validator);
    $unsValidator = Validation::fromJsonObject(json_decode($jsn));
    $this->assertInstanceOf(get_class($validator), $unsValidator);
    $this->assertTrue($validator->isValid('123456'));
    $this->assertEquals(json_encode($validator), json_encode($unsValidator));
  }
}
