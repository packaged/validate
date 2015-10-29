<?php
namespace Packaged\Validate;

interface IValidator
{
  /**
   * Check if a value is valid for this field's type
   *
   * @param mixed $value
   *
   * @return bool
   */
  public function validate($value);

  /**
   * Tidy up a value so it conforms to this field's type (if possible)
   *
   * @param mixed $value
   *
   * @return mixed
   */
  public function tidy($value);

  /**
   * @return string
   */
  public function getLastError();
}
