<?php
namespace Packaged\Validate\Validators;

use Generator;

class PropertiesValidator extends ArrayKeysValidator
{
  protected function _doValidate($value): Generator
  {
    if(!is_object($value))
    {
      return $this->_makeError('must be an object');
    }

    foreach(parent::_doValidate(get_object_vars($value)) as $error)
    {
      yield $error;
    }
  }
}
