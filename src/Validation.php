<?php
namespace Packaged\Validate;

use Packaged\Validate\Validators\ArrayKeysValidator;

class Validation
{
  protected static $_validators = [];

  public function __construct()
  {

  }

  public static function bind()
  {
    if(empty(static::$_validators))
    {
      static::$_validators = $_validators = [
        ArrayKeysValidator::serializeType() => ArrayKeysValidator::class,
        //ArrayValidator::serializeType()      => ArrayValidator::class,
        //BoolValidator::serializeType()       => BoolValidator::class,
        //ConstEnumValidator::serializeType()  => ConstEnumValidator::class,
        //DecimalValidator::serializeType()    => DecimalValidator::class,
        //EmailValidator::serializeType()      => EmailValidator::class,
        //EnumValidator::serializeType()       => EnumValidator::class,
        //EqualValidator::serializeType()      => EqualValidator::class,
        //IntegerValidator::serializeType()    => IntegerValidator::class,
        //IPv4ValidatorTest::serializeType()   => IPv4ValidatorTest::class,
        //MultiValidator::serializeType()      => MultiValidator::class,
        //NullableValidator::serializeType()   => NullableValidator::class,
        //NumberValidator::serializeType()     => NumberValidator::class,
        //OptionalValidator::serializeType()   => OptionalValidator::class,
        //PropertiesValidator::serializeType() => PropertiesValidator::class,
        //RegexValidator::serializeType()      => RegexValidator::class,
        //SchemaValidator::serializeType()     => SchemaValidator::class,
        //StringValidator::serializeType()     => StringValidator::class,
      ];
    }
  }

  public static function fromJsonObject(\stdClass $o): ?SerializableValidator
  {
    if(isset($o->type) && isset($o->config))
    {
      static::bind();
      /** @var SerializableValidator $class */
      $class = static::$_validators[$o->type];
      return $class::validateUnserialize($o->config);
    }
    return null;
  }
}