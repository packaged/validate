<?php
namespace Packaged\Validate;

class EmailValidator extends RegexValidator
{
  public function __construct()
  {
    parent::__construct(
      '/^[_a-zA-Z0-9\-]+(\.[_a-zA-Z0-9\-]+)*@[a-zA-Z0-9\-]+(\.[a-zA-Z0-9\-]+)*(\.[a-zA-Z]{2,})$/'
    );
  }

  public function validate($value)
  {
    $result = parent::validate($value);
    if(!$result)
    {
      $this->_setLastError('invalid email address');
    }
    return $result;
  }
}
