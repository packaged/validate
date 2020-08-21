<?php
namespace Packaged\Validate;

abstract class AbstractSerializableValidator extends AbstractValidator implements SerializableValidator
{
  public static function serializeType(): string
  {
    return substr(basename(str_replace('\\', '/', static::class)), 0, -9);
  }

  public function jsonSerialize()
  {
    return [
      'type'   => $this::serializeType(),
      'config' => $this->validateSerialize(),
    ];
  }
}
