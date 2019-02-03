<?php
namespace Packaged\Validate\Validators;

use Generator;
use Packaged\Validate\AbstractValidator;

class RegexValidator extends AbstractValidator
{
  protected $_pattern;
  protected $_message;

  /**
   * @param string $pattern
   * @param string $message
   */
  public function __construct($pattern, $message = 'does not match regular expression')
  {
    $this->_pattern = $pattern;
    $this->_message = $message;
  }

  protected function _doValidate($value): Generator
  {
    if(preg_match($this->_pattern, $value) !== 1)
    {
      yield $this->_makeError($this->_message);
    }
  }
}
