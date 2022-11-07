<?php
namespace Packaged\Validate;

abstract class AbstractSerializableValidator extends AbstractValidator implements SerializableValidator
{
  public static function serializeType(): string
  {
    return substr(basename(str_replace('\\', '/', static::class)), 0, -9);
  }

  final public function jsonSerialize(): mixed
  {
    return [
      't' => $this::serializeType(),
      'c' => $this->serialize(),
    ];
  }
}
