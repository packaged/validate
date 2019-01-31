<?php
namespace Packaged\Validate\Validators;

use Generator;

class IntegerValidator extends NumberValidator
{
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
        yield $this->_makeError('must be an integer');
      }
    }
  }
}
