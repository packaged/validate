<?php
namespace Packaged\Validate\Validators;

use Generator;

class IntegerValidator extends NumberValidator
{
  public function __construct($minValue = null, $maxValue = null)
  {
    parent::__construct($minValue, $maxValue);
    $this->_dictionary[self::DICT_INVALID] = 'must be an integer';
  }

  protected function _doValidate($value): Generator
  {
    $passParent = true;
    foreach(parent::_doValidate($value) as $error)
    {
      yield $error;
      $passParent = false;
    }
    if($passParent)
    {
      if(floor($value) != $value)
      {
        yield $this->_makeError($this->getDictionary()[self::DICT_INVALID]);
      }
    }
  }
}
