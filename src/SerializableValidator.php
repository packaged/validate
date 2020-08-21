<?php
namespace Packaged\Validate;

interface SerializableValidator extends \JsonSerializable, IValidator
{
  public static function serializeType(): string;

  public static function validateUnserialize($configuration): SerializableValidator;

  public function validateSerialize();
}
