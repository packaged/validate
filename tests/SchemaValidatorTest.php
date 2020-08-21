<?php
namespace Packaged\Validate\Tests;

use Packaged\Validate\Validators\ArrayKeysValidator;
use Packaged\Validate\Validators\ArrayValidator;
use Packaged\Validate\Validators\IntegerValidator;
use Packaged\Validate\Validators\NullableValidator;
use Packaged\Validate\Validators\NumberValidator;
use Packaged\Validate\Validators\OptionalValidator;
use Packaged\Validate\Validators\PropertiesValidator;
use Packaged\Validate\Validators\RegexValidator;
use Packaged\Validate\Validators\SchemaValidator;
use Packaged\Validate\Validators\StringValidator;
use PHPUnit\Framework\TestCase;

class SchemaValidatorTest extends TestCase
{
  public function testValidatorCollectionArray()
  {
    // TODO: Make this do more testing
    $collection = new SchemaValidator(
      [
        'string1'           => new StringValidator(),
        'int1'              => new IntegerValidator(),
        'float1'            => new NumberValidator(),
        'regex1'            => new RegexValidator('/^A.*z$/'),
        'card'              => new SchemaValidator(
          [
            'number'     => new IntegerValidator(),
            'nameOnCard' => new StringValidator(),
          ],
          new ArrayKeysValidator(['number', 'nameOnCard'], true)
        ),
        'numbers'           => new ArrayValidator(new IntegerValidator()),
        'namesAndAddresses' => new ArrayValidator(
          new SchemaValidator(
            [
              'name'    => new StringValidator(),
              'address' => new StringValidator(),
            ],
            new ArrayKeysValidator(['name', 'address'])
          ),
          1
        ),
      ]
    );

    $this->assertEmpty($collection->validate($this->_getCollectionData(true)));
  }

  public function testValidatorCollectionObj()
  {
    // TODO: Make this do more testing
    $collection = new SchemaValidator(
      [
        'string1'           => new StringValidator(),
        'int1'              => new IntegerValidator(),
        'float1'            => new NumberValidator(),
        'regex1'            => new RegexValidator('/^A.*z$/'),
        'card'              => new SchemaValidator(
          [
            'number'     => new IntegerValidator(),
            'nameOnCard' => new StringValidator(),
          ],
          new PropertiesValidator(['number', 'nameOnCard'], true)
        ),
        'numbers'           => new ArrayValidator(new IntegerValidator()),
        'namesAndAddresses' => new ArrayValidator(
          new SchemaValidator(
            [
              'name'    => new StringValidator(),
              'address' => new StringValidator(),
            ],
            new PropertiesValidator(['name', 'address'])
          ),
          1
        ),
      ],
      new PropertiesValidator(['string1', 'int1', 'float1', 'regex1', 'card', 'numbers', 'namesAndAddresses'])
    );

    $this->assertEmpty($collection->validate($this->_getCollectionData(false)));
  }

  private function _getCollectionData(bool $assoc)
  {
    return json_decode(
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
}',
      $assoc
    );
  }

  public function testNullable()
  {
    $validator = new SchemaValidator(
      [
        'required1' => new StringValidator(2, 10),
        'nullable1' => new NullableValidator(new StringValidator(2, 10)),
      ], new ArrayKeysValidator(['required1', 'nullable1'])
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
    $validator = new SchemaValidator(
      [
        'required1' => new StringValidator(2, 10),
        'optional1' => new OptionalValidator(new StringValidator(2, 10)),
      ], new ArrayKeysValidator(['required1'], true)
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
    $this->assertEmpty(
      $validator->validate(
        [
          'required1' => 'some data',
          'optional1' => null,
        ]
      )
    );
  }
}
