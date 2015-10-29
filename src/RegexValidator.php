<?php
namespace Packaged\Validate;

class RegexValidator extends AbstractValidator
{
  private $_pattern;

  /**
   * @param string $pattern
   */
  public function __construct($pattern)
  {
    $this->_pattern = $pattern;
  }

  public function validate($value)
  {
    $result = preg_match($this->_pattern, $value) == 1;
    if(!$result)
    {
      $this->_setLastError('does not match regular expression');
    }
    return $result;
  }

  public function tidy($value)
  {
    return $value;
  }
}
