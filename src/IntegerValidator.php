<?php
namespace Packaged\Validate;

class IntegerValidator extends NumberValidator
{
  public function validate($value)
  {
    $result = parent::validate($value);
    if($result)
    {
      if(floor($value) != $value)
      {
        $this->_setLastError('must be an integer');
        $result = false;
      }
    }
    return $result;
  }

  public function tidy($value)
  {
    return floor(parent::tidy($value));
  }
}
