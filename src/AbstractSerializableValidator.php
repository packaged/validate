<?php
namespace Packaged\Validate;

abstract class AbstractSerializableValidator extends AbstractValidator implements SerializableValidator
{
  public static function serializeType(): string
  {
    return substr(basename(str_replace('\\', '/', static::class)), 0, -9);
  }

  #[\ReturnTypeWillChange]
  final public function jsonSerialize(): array
  {
    return [
      't' => $this::serializeType(),
      'c' => $this->serialize(),
      'd' => $this->getDictionary(),
    ];
  }
}
