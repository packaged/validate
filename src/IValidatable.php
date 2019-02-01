<?php
namespace Packaged\Validate;

interface IValidatable
{
  /**
   * Check if a value is valid for this field's type
   *
   * @return ValidationException[]
   */
  public function validate(): array;

  /**
   * @return bool
   */
  public function isValid(): bool;

  /**
   * @throws ValidationException
   */
  public function assert();
}
