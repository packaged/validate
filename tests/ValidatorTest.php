<?php
namespace Packaged\Validate\Validators\Tests;

use Packaged\Validate\IValidator;
use Packaged\Validate\ValidationException;
use Packaged\Validate\Validators\ArrayValidator;
use Packaged\Validate\Validators\ConstEnumValidator;
use Packaged\Validate\Validators\EmailValidator;
use Packaged\Validate\Validators\EnumValidator;
use Packaged\Validate\Validators\IntegerValidator;
use Packaged\Validate\Validators\IPv4AddressValidator;
use Packaged\Validate\Validators\MultiValidator;
use Packaged\Validate\Validators\NullableValidator;
use Packaged\Validate\Validators\NumberValidator;
use Packaged\Validate\Validators\OptionalValidator;
use Packaged\Validate\Validators\RegexValidator;
use Packaged\Validate\Validators\StringValidator;
use Packaged\Validate\Validators\ValidatorCollection;
use PHPUnit\Framework\TestCase;

class ValidatorTest extends TestCase
{
  public function dataProvider()
  {
    return [
      [0, 0],
      [0, 1],
      [0, 2],
      [0, 9],
      [0, 10],
      [0, 99],
      [0, 100],
      [1, 0],
      [10, 0],
      [11, 0],
      [100, 0],
      [101, 0],
      [5, 15],
    ];
  }

  /**
   * @param $minLen
   * @param $maxLen
   *
   * @dataProvider dataProvider
   */
  public function testStringValidator($minLen, $maxLen)
  {
    $validator = new StringValidator($minLen, $maxLen);

    $strings = [
      '',
      'a',
      str_repeat('a', 10),
      str_repeat('a', 100),
    ];

    foreach($strings as $string)
    {
      $errors = $validator->validate($string);

      $expected = (strlen($string) >= $minLen) && (($maxLen <= 0) || (strlen($string) <= $maxLen));
      if($expected)
      {
        $this->assertEmpty($errors);
      }
      else
      {
        $this->assertNotEmpty($errors);
        $this->assertEquals(1, count($errors));
        $this->assertContainsOnly(ValidationException::class, $errors);
      }
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
      '{
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

    $this->assertEmpty($collection->validate($data));
  }

  private function _makeCollection($values, IValidator $validator)
  {
    return new ValidatorCollection(array_fill(0, count($values), $validator));
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
    /** @noinspection PhpUnhandledExceptionInspection */
    $validator = new ConstEnumValidator(ConstTestClass::class);
    $this->assertEmpty($validator->validate(ConstTestClass::TEST_CONST_1));
    $this->assertEmpty($validator->validate(ConstTestClass::TEST_CONST_2));
    $this->assertEmpty($validator->validate(ConstTestClass::TEST_CONST_3));
    $this->assertEmpty($validator->validate(ConstTestClass::TEST_CONST_4));
    $this->assertNotEmpty($validator->validate('unknown value'));
  }

  public function testNullable()
  {
    $validator = new ValidatorCollection(
      [
        'required1' => new StringValidator(2, 10),
        'nullable1' => new NullableValidator(new StringValidator(2, 10)),
      ]
    );

    $this->assertEmpty(
      $validator->validate(
        [
          'required1' => 'some data',
          'nullable1' => 'other data',
        ]
      )
    );
    $this->assertNotEmpty(
      $validator->validate(
        [
          'required1' => 'some data',
          'nullable1' => 'a',
        ]
      )
    );
    $this->assertNotEmpty(
      $validator->validate(
        [
          'required1' => 'some data',
        ]
      )
    );
    $this->assertEmpty(
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

    $this->assertEmpty(
      $validator->validate(
        [
          'required1' => 'some data',
          'optional1' => 'other data',
        ]
      )
    );
    $this->assertNotEmpty(
      $validator->validate(
        [
          'required1' => 'some data',
          'optional1' => 'a',
        ]
      )
    );
    $this->assertEmpty(
      $validator->validate(
        [
          'required1' => 'some data',
        ]
      )
    );
    $this->assertNotEmpty(
      $validator->validate(
        [
          'optional1' => 'other data',
        ]
      )
    );
    $this->assertNotEmpty(
      $validator->validate(
        [
          'required1' => 'some data',
          'optional1' => null,
        ]
      )
    );
  }

  public function emailProvider()
  {
    return [
      ['test@here', false],
      ['test@test.com', true],
      ['TeSt@TeSt.CoM', true],
      ['123-test.user.one@test.domain.com', true],
      ['-_@-.co', true],
      ['.test@test.com', false],
    ];
  }

  /**
   * @dataProvider emailProvider
   *
   * @param string $emailAddress
   * @param bool   $isValid
   */
  public function testEmailValidator($emailAddress, $isValid)
  {
    $validator = new EmailValidator();
    $errors = $validator->validate($emailAddress);
    if($isValid)
    {
      $this->assertEmpty($errors);
    }
    else
    {
      $this->assertNotEmpty($errors);
    }
  }

  public function ipv4Provider()
  {
    return [
      ['0.0.0.0', true],
      ['1.2.3.4', true],
      ['255.255.255.255', true],
      ['256.255.255.255', false],
      ['255.256.255.255', false],
      ['255.255.256.255', false],
      ['255.255.255.256', false],
      ['31.32.33.34', true],
      ['a.b.c.d', false],
    ];
  }

  /**
   * @dataProvider ipv4Provider
   *
   * @param string $address
   * @param bool   $isValid
   */
  public function testIpv4Validator($address, $isValid)
  {
    $validator = new IPv4AddressValidator();
    $errors = $validator->validate($address);
    if($isValid)
    {
      $this->assertEmpty($errors);
    }
    else
    {
      $this->assertNotEmpty($errors);
    }
  }

  public function testRegexValidatorMessage()
  {
    $v1 = new RegexValidator('/^[0-9]{6}$/');
    $v2 = new RegexValidator('/^[0-9]{6}$/', 'test failure message');
    $v1err = $v1->validate('123');
    $this->assertNotEmpty($v1err);
    $this->assertEquals('does not match regular expression', $v1err[0]->getMessage());
    $v2err = $v2->validate('123');
    $this->assertNotEmpty($v2err);
    $this->assertEquals('test failure message', $v2err[0]->getMessage());
  }

  public function testMultiValidator()
  {
    $validator = new MultiValidator(new StringValidator(10), new EmailValidator());
    $test1 = $validator->validate('t@jdiio');
    $this->assertNotEmpty($test1);
    $this->assertEquals('must be at least 10 characters', $test1[0]->getMessage());
    $this->assertEquals('invalid email address', $test1[1]->getMessage());

    $test2 = $validator->validate('t@jdi.io');
    $this->assertNotEmpty($test2);
    $this->assertEquals('must be at least 10 characters', $test2[0]->getMessage());

    $test3 = $validator->validate('tom.kay@jdi.io');
    $this->assertEmpty($test3);
  }
}

class ConstTestClass
{
  const TEST_CONST_1 = 'string 1';
  const TEST_CONST_2 = 'string 2';
  const TEST_CONST_3 = 'string 3';
  const TEST_CONST_4 = 'string 4';
}
