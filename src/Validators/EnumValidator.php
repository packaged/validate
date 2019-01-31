<?php
namespace Packaged\Validate\Validators;

use Generator;
use Packaged\Validate\AbstractValidator;

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

  protected function _doValidate($value): Generator
  {
    if($this->_caseSensitive)
    {
      if(!in_array($value, $this->_allowedValues))
      {
        return $this->_makeError('not a valid value');
      }
    }

    $result = false;
    foreach($this->_allowedValues as $allowedValue)
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
}
