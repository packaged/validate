<?php
namespace Packaged\Validate\Validators;

use Generator;
use Packaged\Validate\AbstractValidator;

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

  protected function _doValidate($value): Generator
  {
    $len = strlen($value);
    if($len < $this->_minLength)
    {
      yield $this->_makeError('must be at least ' . $this->_minLength . ' characters');
    }
    else if(($this->_maxLength > 0) && ($len > $this->_maxLength))
    {
      yield $this->_makeError('must be no more than ' . $this->_maxLength . ' characters');
    }
  }
}
