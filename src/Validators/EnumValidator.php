<?php
namespace Packaged\Validate\Validators;

use Generator;
use Packaged\Validate\AbstractValidator;

class EnumValidator extends AbstractValidator
{
  protected $_allowedValues;
  protected $_caseSensitive;

  /**
   * @param string[] $allowedValues
   * @param bool     $caseSensitive
   */
  public function __construct(array $allowedValues, $caseSensitive = false)
  {
    $this->_allowedValues = $allowedValues;
    $this->_caseSensitive = $caseSensitive;
  }

  protected function _doValidate($value): Generator
  {
    if($this->_getCaseSensitive())
    {
      if(!in_array($value, $this->_getAllowedValues()))
      {
        return $this->_makeError('not a valid value');
      }
    }

    $result = false;
    foreach($this->_getAllowedValues() as $allowedValue)
    {
      if(strcasecmp($allowedValue, $value) == 0)
      {
        $result = true;
      }
    }
    if(!$result)
    {
      yield $this->_makeError('not a valid value');
    }
  }

  protected function _getAllowedValues()
  {
    return $this->_allowedValues;
  }

  protected function _getCaseSensitive()
  {
    return $this->_caseSensitive;
  }
}