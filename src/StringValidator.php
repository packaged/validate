<?php
namespace Packaged\Validate;

class StringValidator extends AbstractValidator
{
  protected $_minLength;
  protected $_maxLength;

  /**
   * @param int $minLength Min length in bytes, 0 to disable
   * @param int $maxLength Max length in bytes, 0 to disable
   */
  public function __construct($minLength = 0, $maxLength = 0)
  {
    if(($maxLength > 0) && ($maxLength < $minLength))
    {
      throw new \InvalidArgumentException(
        'maxLength must be greater than or equal to minLength'
      );
    }
    $this->_minLength = $minLength;
    $this->_maxLength = $maxLength;
  }

  public function validate($value)
  {
    $len = strlen($value);
    $result = true;
    if($len < $this->_minLength)
    {
      $this->_setLastError(
        'must be at least ' . $this->_minLength . ' characters'
      );
      $result = false;
    }
    else if(($this->_maxLength > 0) && ($len > $this->_maxLength))
    {
      $this->_setLastError(
        'must be no more than ' . $this->_maxLength . ' characters'
      );
      $result = false;
    }
    return $result;
  }

  public function tidy($value)
  {
    return (string)$value;
  }
}
