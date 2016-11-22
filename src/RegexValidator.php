<?php
namespace Packaged\Validate;

class RegexValidator extends AbstractValidator
{
  private $_pattern;
  private $_message;

  /**
   * @param string $pattern
   * @param string $message
   */
  public function __construct(
    $pattern, $message = 'does not match regular expression'
  )
  {
    $this->_pattern = $pattern;
    $this->_message = $message;
  }

  public function validate($value)
  {
    $result = preg_match($this->_pattern, $value) == 1;
    if(!$result)
    {
      $this->_setLastError($this->_message);
    }
    return $result;
  }

  public function tidy($value)
  {
    return $value;
  }
}
