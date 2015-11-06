<?php
namespace Packaged\Validate;

class BoolValidator extends AbstractValidator
{
  public function validate($value)
  {
    if(is_string($value))
    {
      $result = in_array(strtolower($value), ['true', 'false', '1', '0']);
    }
    else if(is_int($value))
    {
      $result = in_array($value, [0, 1]);
    }
    else
    {
      $result = is_bool($value);
    }
    if(!$result)
    {
      $this->_setLastError('Invalid boolean value');
    }
    return $result;
  }

  public function tidy($value)
  {
    if(is_string($value) && in_array(strtolower($value), ['true', '1', 'false', '0']))
    {
      return in_array(strtolower($value), ['true', '1']);
    }
    if(is_int($value))
    {
      return $value == 1;
    }
    if(is_bool($value))
    {
      return $value;
    }
    throw new \Exception('Unable to tidy the value');
  }
}
