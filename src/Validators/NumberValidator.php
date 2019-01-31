<?php
namespace Packaged\Validate\Validators;

use Generator;
use Packaged\Validate\AbstractValidator;

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

  protected function _doValidate($value): Generator
  {
    if(!is_numeric($value))
    {
      yield $this->_makeError('must be a number');
    }
    else if(($this->_minValue !== null) && ($value < $this->_minValue))
    {
      yield $this->_makeError('must be more than ' . $this->_minValue);
    }
    else if(($this->_maxValue !== null) && ($value > $this->_maxValue))
    {
      yield $this->_makeError('must be less than ' . $this->_maxValue);
    }
  }
}
