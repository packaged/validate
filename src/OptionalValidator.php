<?php
namespace Packaged\Validate;

class OptionalValidator extends AbstractValidator
{
  private $_validator;

  public function __construct(IValidator $validator)
  {
    $this->_validator = $validator;
  }

  public function validate($value)
  {
    return $this->_validator->validate($value);
  }

  public function tidy($value)
  {
    return $this->_validator->tidy($value);
  }
}