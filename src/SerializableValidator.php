<?php
namespace Packaged\Validate;

interface SerializableValidator extends \JsonSerializable, IValidator
{
  public static function serializeType(): string;

  public static function deserialize($configuration): SerializableValidator;

  public function serialize();
}
