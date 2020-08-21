<?php
namespace Packaged\Validate\Tests;

use Packaged\Validate\IValidator;
use Packaged\Validate\Tests\Supporting\ConstTestClass;
use Packaged\Validate\Validation;
use Packaged\Validate\Validators\ConstEnumValidator;
use Packaged\Validate\Validators\EnumValidator;
use Packaged\Validate\Validators\SchemaValidator;
use PHPUnit\Framework\TestCase;

class EnumValidatorTest extends TestCase
{
  private function _makeCollection($values, IValidator $validator)
  {
    return new SchemaValidator(array_fill(0, count($values), $validator));
  }

  public function testEnumValidatorCaseInsensitive()
  {
    $allowed = ['string1', 'String2', 'STRING3'];
    $validator = new EnumValidator($allowed, false);
    $collection = $this->_makeCollection($allowed, $validator);
    $this->assertEmpty($collection->validate(array_map('strtolower', $allowed)));
    $this->assertNotEmpty($validator->validate('Unknown string'));
  }

  public function testEnumValidatorCaseSensitive()
  {
    $allowed = ['string1', 'String2', 'STRING3'];
    $validator = new EnumValidator($allowed, true);

    $this->assertEmpty($validator->validate('string1'));
    $this->assertNotEmpty($validator->validate('string2'));
    $this->assertNotEmpty($validator->validate('string3'));
    $this->assertNotEmpty($validator->validate('Unknown string'));

    $collection = $this->_makeCollection($allowed, $validator);
    $this->assertEmpty($collection->validate($allowed));
  }

  public function testConstEnum()
  {
    $validator = new ConstEnumValidator(ConstTestClass::class);
    $this->assertEmpty($validator->validate(ConstTestClass::TEST_CONST_1));
    $this->assertEmpty($validator->validate(ConstTestClass::TEST_CONST_2));
    $this->assertEmpty($validator->validate(ConstTestClass::TEST_CONST_3));
    $this->assertEmpty($validator->validate(ConstTestClass::TEST_CONST_4));
    $this->assertNotEmpty($validator->validate('unknown value'));
  }

  public function testEmptyValues()
  {
    $validator = new EnumValidator([]);
    $this->assertTrue($validator->isValid(null));
    $this->assertTrue($validator->isValid(''));
    $this->assertFalse($validator->isValid('test'));

    $validator = new EnumValidator(['test']);
    $this->assertFalse($validator->isValid(null));
    $this->assertFalse($validator->isValid(''));
    $this->assertTrue($validator->isValid('test'));
  }

  public function testSerialize()
  {
    $validator = new EnumValidator(['TesT'], true);
    $this->assertFalse($validator->isValid('test'));
    $this->assertTrue($validator->isValid('TesT'));

    $jsn = json_encode($validator);
    $unsValidator = Validation::fromJsonObject(json_decode($jsn));
    $this->assertInstanceOf(get_class($validator), $unsValidator);
    $this->assertFalse($validator->isValid('test'));
    $this->assertTrue($validator->isValid('TesT'));
    $this->assertEquals(json_encode($validator), json_encode($unsValidator));
  }

  public function testSerializeConst()
  {
    $validator = new ConstEnumValidator(ConstTestClass::class);
    $this->assertTrue($validator->isValid(ConstTestClass::TEST_CONST_1));

    $jsn = json_encode($validator);
    $unsValidator = Validation::fromJsonObject(json_decode($jsn));
    $this->assertInstanceOf(get_class($validator), $unsValidator);
    $this->assertTrue($validator->isValid(ConstTestClass::TEST_CONST_1));
    $this->assertEquals(json_encode($validator), json_encode($unsValidator));
  }
}
