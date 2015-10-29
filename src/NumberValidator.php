<?php
namespace Packaged\Validate;

class NumberValidator extends AbstractValidator
{
  protected $_minValue;
  protected $_maxValue;

  public function __construct($minValue = null, $maxValue = null)
  {
    if(($maxValue !== null) && ($minValue !== null) && ($maxValue < $minValue))
    {
      throw new \InvalidArgumentException(
        'maxLength must be greater than or equal to minLength'
      );
    }
    $this->_minValue = $minValue;
    $this->_maxValue = $maxValue;
  }

  public function validate($value)
  {
    $result = true;
    if(!is_numeric($value))
    {
      $result = false;
      $this->_setLastError('must be a number');
    }
    else if(($this->_minValue !== null) && ($value < $this->_minValue))
    {
      $result = false;
      $this->_setLastError('must be more than ' . $this->_minValue);
    }
    else if(($this->_maxValue !== null) && ($value > $this->_maxValue))
    {
      $result = false;
      $this->_setLastError('must be less than ' . $this->_maxValue);
    }
    return $result;
  }

  public function tidy($value)
  {
    if(is_int($value) || is_float($value))
    {
      return $value;
    }

    if(is_string($value))
    {
      if(strpos($value, '.') !== false)
      {
        return (float)$value;
      }
      else
      {
        return (int)$value;
      }
    }
    throw new \Exception('Unable to tidy the value');
  }
}
