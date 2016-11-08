<?php
namespace Packaged\Validate;

class NullableValidator extends AbstractValidator
{
  private $_validator;
  private $_allowEmptyString = false;

  /**
   * @param IValidator $validator
   * @param bool       $allowEmptyString If true then treat empty strings as nulls
   */
  public function __construct(IValidator $validator, $allowEmptyString = false)
  {
    $this->_validator = $validator;
    $this->_allowEmptyString = $allowEmptyString;
  }

  public function validate($value)
  {
    return ($value === null)
    || ($this->_allowEmptyString && ($value === ''))
    || $this->_validator->validate($value);
  }

  public function tidy($value)
  {
    if($this->_allowEmptyString && ($value === ''))
    {
      $value = null;
    }
    if($value !== null)
    {
      $value = $this->_validator->tidy($value);
    }
    return $value;
  }

  public function getLastError($asString = false)
  {
    return $this->_validator->getLastError();
  }
}
