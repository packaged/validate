<?php
namespace Packaged\Validate;

class EnumValidator extends AbstractValidator
{
  private $_allowedValues;
  private $_caseSensitive;

  /**
   * @param string[] $allowedValues
   * @param bool     $caseSensitive
   */
  public function __construct(array $allowedValues, $caseSensitive = false)
  {
    $this->_allowedValues = $allowedValues;
    $this->_caseSensitive = $caseSensitive;
  }

  public function validate($value)
  {
    if($this->_caseSensitive)
    {
      return in_array($value, $this->_allowedValues);
    }

    $result = false;
    foreach($this->_allowedValues as $allowedValue)
    {
      if(strcasecmp($allowedValue, $value) == 0)
      {
        $result = true;
        break;
      }
    }
    return $result;
  }

  public function tidy($value)
  {
    if($this->_caseSensitive)
    {
      return in_array($value, $this->_allowedValues) ? $value : null;
    }

    foreach($this->_allowedValues as $allowedValue)
    {
      if(strcasecmp($allowedValue, $value) == 0)
      {
        return $allowedValue;
      }
    }
    return null;
  }
}
