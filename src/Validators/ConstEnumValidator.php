<?php
namespace Packaged\Validate\Validators;

/**
 * Same as EnumValidator but takes its values from the constants in the
 * given class
 */
class ConstEnumValidator extends EnumValidator
{
  /**
   * @param string $className
   * @param bool   $caseSensitive
   *
   * @throws \ReflectionException
   */
  public function __construct($className, $caseSensitive = false)
  {
    parent::__construct((new \ReflectionClass($className))->getConstants(), $caseSensitive);
  }
}
