<?php
namespace Packaged\Validate\Tests;

use Packaged\Validate\Validation;
use Packaged\Validate\Validators\BoolValidator;
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
    $this->assertTrue($validator->isValid(false));
    $this->assertTrue($validator->isValid(true));
    $this->assertEquals($validator->validate('')[0]->getMessage(), 'Required');

    $validator = RequiredValidator::withDictionary([
      RequiredValidator::DICT_INVALID => 'This field is required',
    ]);
    $this->assertFalse($validator->isValid(null));
    $this->assertFalse($validator->isValid(''));
    $this->assertEquals($validator->validate('')[0]->getMessage(), 'This field is required');
    // test the error message
  }

    public function testSerialize()
  {
    $validator = new RequiredValidator();
    $this->assertEquals(true, $validator->isValid('Something'));
    $jsn = json_encode($validator);

    $unsValidator = Validation::fromJsonObject(json_decode($jsn));
    $this->assertInstanceOf(get_class($validator), $unsValidator);
    $this->assertEquals(true, $validator->isValid('Something'));
    $this->assertEquals(json_encode($validator), json_encode($unsValidator));
  }

  public function testSerializeWithDictionary()
  {
    $validator = RequiredValidator::withDictionary([
      RequiredValidator::DICT_INVALID => 'This field is required',
    ]);
    $this->assertEquals(false, $validator->isValid(null));
    $this->assertEquals(false, $validator->isValid(''));
    $this->assertEquals('This field is required', $validator->validate('')[0]->getMessage());

    $jsn = json_encode($validator);
    $unsValidator = Validation::fromJsonObject(json_decode($jsn));
    $this->assertInstanceOf(get_class($validator), $unsValidator);
    $this->assertEquals(false, $unsValidator->isValid(null));
    $this->assertEquals(false, $unsValidator->isValid(''));
    $this->assertEquals('This field is required', $unsValidator->validate('')[0]->getMessage());
  }
}
