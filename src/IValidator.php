<?php
namespace Packaged\Validate;

interface IValidator
{
  /**
   * Check if a value is valid for this field's type
   *
   * @param mixed $value
   *
   * @return ValidationException[]
   */
  public function validate($value): array;

  /**
   * @param mixed $value
   *
   * @return bool
   */
  public function isValid($value): bool;

  /**
   * @param mixed $value
   *
   * @throws ValidationException
   */
  public function assert($value);
}
