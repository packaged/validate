<?php
namespace Packaged\Validate;

use Packaged\Validate\Validators\ArrayKeysValidator;
use Packaged\Validate\Validators\ArrayValidator;
use Packaged\Validate\Validators\BoolValidator;
use Packaged\Validate\Validators\ConstEnumValidator;
use Packaged\Validate\Validators\DecimalValidator;
use Packaged\Validate\Validators\EmailValidator;
use Packaged\Validate\Validators\EnumValidator;
use Packaged\Validate\Validators\EqualValidator;
use Packaged\Validate\Validators\IntegerValidator;
use Packaged\Validate\Validators\IPv4AddressValidator;
use Packaged\Validate\Validators\MultiValidator;
use Packaged\Validate\Validators\NotEqualValidator;
use Packaged\Validate\Validators\NullableValidator;
use Packaged\Validate\Validators\NumberValidator;
use Packaged\Validate\Validators\OptionalValidator;
use Packaged\Validate\Validators\PropertiesValidator;
use Packaged\Validate\Validators\RegexValidator;
use Packaged\Validate\Validators\RemoteValidator;
use Packaged\Validate\Validators\RequiredValidator;
use Packaged\Validate\Validators\SchemaValidator;
use Packaged\Validate\Validators\StringValidator;

class Validation
{
  protected static $_validators = [];

  public static function bind()
  {
    if(empty(static::$_validators))
    {
      static::$_validators = $_validators = [
        ArrayKeysValidator::serializeType()   => ArrayKeysValidator::class,
        ArrayValidator::serializeType()       => ArrayValidator::class,
        BoolValidator::serializeType()        => BoolValidator::class,
        ConstEnumValidator::serializeType()   => ConstEnumValidator::class,
        DecimalValidator::serializeType()     => DecimalValidator::class,
        EmailValidator::serializeType()       => EmailValidator::class,
        EnumValidator::serializeType()        => EnumValidator::class,
        EqualValidator::serializeType()       => EqualValidator::class,
        NotEqualValidator::serializeType()    => NotEqualValidator::class,
        IntegerValidator::serializeType()     => IntegerValidator::class,
        IPv4AddressValidator::serializeType() => IPv4AddressValidator::class,
        MultiValidator::serializeType()       => MultiValidator::class,
        NullableValidator::serializeType()    => NullableValidator::class,
        NumberValidator::serializeType()      => NumberValidator::class,
        OptionalValidator::serializeType()    => OptionalValidator::class,
        PropertiesValidator::serializeType()  => PropertiesValidator::class,
        RegexValidator::serializeType()       => RegexValidator::class,
        RemoteValidator::serializeType()      => RemoteValidator::class,
        RequiredValidator::serializeType()    => RequiredValidator::class,
        SchemaValidator::serializeType()      => SchemaValidator::class,
        StringValidator::serializeType()      => StringValidator::class,
      ];
    }
  }

  public static function fromJsonObject(\stdClass $o): ?SerializableValidator
  {
    static::bind();

    /** @var string $classAlias */
    $classAlias = $o->t ?? null;
    /** @var object $classConfiguration */
    $classConfiguration = $o->c ?? null;
    $dictionary = $o->d ?? null;

    if(isset(static::$_validators[$classAlias]))
    {
      /** @var SerializableValidator $class */
      $class = static::$_validators[$classAlias];
      if(is_subclass_of($class, SerializableValidator::class))
      {
        $class = $class::deserialize($classConfiguration);

        if($class instanceof AbstractValidator)
        {
          $class->setDictionary(json_decode(json_encode($dictionary), true));
        }

        return $class;
      }
    }
    return null;
  }
}
