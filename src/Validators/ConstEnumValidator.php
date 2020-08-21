<?php
namespace Packaged\Validate\Validators;

use Packaged\Validate\SerializableValidator;

/**
 * Same as EnumValidator but takes its values from the constants in the
 * given class
 */
class ConstEnumValidator extends EnumValidator
{
  protected $_className;

  /**
   * @param string $className
   * @param bool   $caseSensitive
   *
   * @throws \ReflectionException
   */
  public function __construct($className, $caseSensitive = false)
  {
    $this->_className = $className;
    parent::__construct((new \ReflectionClass($className))->getConstants(), $caseSensitive);
  }

  public static function deserialize($configuration): SerializableValidator
  {
    return new static($configuration->className, $configuration->caseSensitive);
  }

  public function serialize(): array
  {
    return parent::serialize() + [
        'className' => $this->_className,
      ];
  }
}
