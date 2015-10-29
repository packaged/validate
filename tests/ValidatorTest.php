<?php
namespace Packaged\Validate\Tests;

use Packaged\Validate\ArrayValidator;
use Packaged\Validate\ConstEnumValidator;
use Packaged\Validate\EnumValidator;
use Packaged\Validate\IntegerValidator;
use Packaged\Validate\NullableValidator;
use Packaged\Validate\NumberValidator;
use Packaged\Validate\OptionalValidator;
use Packaged\Validate\RegexValidator;
use Packaged\Validate\StringValidator;
use Packaged\Validate\ValidatorCollection;

class ValidatorTest extends \PHPUnit_Framework_TestCase
{
  public function testStringValidator()
  {
    $this->_doTestStringValidator(0, 0);
    $this->_doTestStringValidator(0, 1);
    $this->_doTestStringValidator(0, 2);
    $this->_doTestStringValidator(0, 9);
    $this->_doTestStringValidator(0, 10);
    $this->_doTestStringValidator(0, 99);
    $this->_doTestStringValidator(0, 100);
    $this->_doTestStringValidator(1, 0);
    $this->_doTestStringValidator(10, 0);
    $this->_doTestStringValidator(11, 0);
    $this->_doTestStringValidator(100, 0);
    $this->_doTestStringValidator(101, 0);
    $this->_doTestStringValidator(5, 15);
  }

  private function _doTestStringValidator($minLen, $maxLen)
  {
    $validator = new StringValidator($minLen, $maxLen);

    $strings = [
      '',
      'a',
      str_repeat('a', 10),
      str_repeat('a', 100)
    ];

    foreach($strings as $string)
    {
      $expected = (strlen($string) >= $minLen)
        && (($maxLen <= 0) || (strlen($string) <= $maxLen));
      $this->assertEquals($expected, $validator->validate($string));
    }
  }

  public function testValidatorCollection()
  {
    // TODO: Make this do more testing
    $collection = new ValidatorCollection(
      [
        'string1'           => new StringValidator(),
        'int1'              => new IntegerValidator(),
        'float1'            => new NumberValidator(),
        'regex1'            => new RegexValidator('/^A.*z$/'),
        'card'              => [
          'number'     => new IntegerValidator(),
          'nameOnCard' => new StringValidator(),
        ],
        'numbers'           => new ArrayValidator(new IntegerValidator()),
        'namesAndAddresses' => new ArrayValidator(
          new ValidatorCollection(
            [
              'name'    => new StringValidator(),
              'address' => new StringValidator(),
            ]
          ),
          1
        ),
      ]
    );

    $data = json_decode(
      '
{
    "string1":"a string",
    "int1":12,
    "float1":"123",
    "regex1":"Abcdefz",
    "card":{
        "number":123,"nameOnCard":"me"

    },
    "numbers": [123, 456, 789, 101112],
    "namesAndAddresses": [
        {
            "name": "My Name",
            "address": "My Address"
        },
        {
            "name": "My Other Name",
            "address": "My Other Address"
        }
    ]
}'
    );

    $this->assertTrue($collection->validate($data));
  }

  public function testEnumValidatorCaseInsensitive()
  {
    $allowed = ['string1', 'String2', 'STRING3'];
    $validator = new EnumValidator($allowed, false);
    foreach($allowed as $value)
    {
      $this->assertTrue($validator->validate(strtolower($value)));
    }
    $this->assertFalse($validator->validate('Unknown string'));
  }

  public function testEnumValidatorCaseSensitive()
  {
    $allowed = ['string1', 'String2', 'STRING3'];
    $validator = new EnumValidator($allowed, true);

    $this->assertTrue($validator->validate('string1'));
    $this->assertFalse($validator->validate('string2'));
    $this->assertFalse($validator->validate('string3'));
    foreach($allowed as $value)
    {
      $this->assertTrue($validator->validate($value));
    }
    $this->assertFalse($validator->validate('Unknown string'));
  }

  public function testEnumTidy()
  {
    $allowed = ['string1', 'String2', 'STRING3'];
    $csValidator = new EnumValidator($allowed, true);
    $ciValidator = new EnumValidator($allowed, false);

    $this->assertEquals('string1', $csValidator->tidy('string1'));
    $this->assertEquals(null, $csValidator->tidy('string2'));
    $this->assertEquals(null, $csValidator->tidy('string3'));

    $this->assertEquals('string1', $ciValidator->tidy('string1'));
    $this->assertEquals('String2', $ciValidator->tidy('string2'));
    $this->assertEquals('STRING3', $ciValidator->tidy('string3'));

    $this->assertEquals(null, $csValidator->tidy('Unknown string'));
    $this->assertEquals(null, $ciValidator->tidy('Unknown string'));
  }

  public function testConstEnum()
  {
    $validator = new ConstEnumValidator(ConstTestClass::class);
    $this->assertTrue($validator->validate(ConstTestClass::TEST_CONST_1));
    $this->assertTrue($validator->validate(ConstTestClass::TEST_CONST_2));
    $this->assertTrue($validator->validate(ConstTestClass::TEST_CONST_3));
    $this->assertTrue($validator->validate(ConstTestClass::TEST_CONST_4));
    $this->assertFalse($validator->validate('unknown value'));
  }

  public function testNullable()
  {
    $validator = new ValidatorCollection(
      [
        'required1' => new StringValidator(2, 10),
        'nullable1' => new NullableValidator(new StringValidator(2, 10)),
      ]
    );

    $this->assertTrue(
      $validator->validate(
        [
          'required1' => 'some data',
          'nullable1' => 'other data',
        ]
      )
    );
    $this->assertFalse(
      $validator->validate(
        [
          'required1' => 'some data',
          'nullable1' => 'a',
        ]
      )
    );
    $this->assertFalse(
      $validator->validate(
        [
          'required1' => 'some data',
        ]
      )
    );
    $this->assertTrue(
      $validator->validate(
        [
          'required1' => 'some data',
          'nullable1' => null,
        ]
      )
    );
  }

  public function testOptional()
  {
    $validator = new ValidatorCollection(
      [
        'required1' => new StringValidator(2, 10),
        'optional1' => new OptionalValidator(new StringValidator(2, 10)),
      ]
    );

    $this->assertTrue(
      $validator->validate(
        [
          'required1' => 'some data',
          'optional1' => 'other data',
        ]
      )
    );
    $this->assertFalse(
      $validator->validate(
        [
          'required1' => 'some data',
          'optional1' => 'a',
        ]
      )
    );
    $this->assertTrue(
      $validator->validate(
        [
          'required1' => 'some data',
        ]
      )
    );
    $this->assertFalse(
      $validator->validate(
        [
          'optional1' => 'other data',
        ]
      )
    );
    $this->assertFalse(
      $validator->validate(
        [
          'required1' => 'some data',
          'optional1' => null,
        ]
      )
    );
  }
}

class ConstTestClass
{
  const TEST_CONST_1 = 'string 1';
  const TEST_CONST_2 = 'string 2';
  const TEST_CONST_3 = 'string 3';
  const TEST_CONST_4 = 'string 4';
}
