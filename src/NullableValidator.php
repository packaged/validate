<?php
namespace Packaged\Validate;

class NullableValidator extends AbstractValidator
{
  private $_validator;

  public function __construct(IValidator $validator)
  {
    $this->_validator = $validator;
  }

  public function validate($value)
  {
    return ($value === null) || $this->_validator->validate($value);
  }

  public function tidy($value)
  {
    if($value !== null)
    {
      $value = $this->_validator->tidy($value);
    }
    return $value;
  }
}
